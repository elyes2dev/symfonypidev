<?php

namespace App\Controller;
use App\Entity\Event;
use App\Entity\Likedevent;
use App\Services\QrcodeService;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Date;
use TCPDF;

use Stripe\PaymentIntent;
/**
 * @method countLikedEventsByUserId($getUserId)
 */
class HomeController extends AbstractController

{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }


    #[Route('/home', name: 'app_home')]
    public function index(EventRepository $eventRepository,Request $request): Response
    {
        // Get all events
        $orderByField = $request->query->get('orderBy');

        // Check if orderByField is provided
        if ($orderByField) {
            // Get all events ordered by the specified field
            $events = $eventRepository->findAllOrderedByField($orderByField);
        } else {
            // If no orderByField is provided, retrieve all events
            $events = $eventRepository->findAll();
        }

        // Array to store the count of liked events for each event
        $likedEventCounts = [];
        $joinEventCounts = [];
        // Fetch the count of liked events for each event
        foreach ($events as $event) {
            // Count the number of liked events for the current event
            $likedEventCounts[$event->getId()] = $event->getLikedByUsers()->count();
            $joinEventCounts[$event->getId()] = $event-> getIdplayer()->count();
        }

        return $this->render('home/Events.html.twig', [
            'events' => $events,
            'likedEventCounts' => $likedEventCounts,
            'joinEventCounts' => $joinEventCounts
        ]);
    }

    #[Route('/event/{id}/like', name: 'like_event', methods: ['POST'])]
public function likeEventAction(Event $event): JsonResponse
{
    $entityManager = $this->getDoctrine()->getManager();

    // Retrieve the authenticated user (you may need to adjust this logic based on your authentication system)
    $user = $entityManager->getRepository(User::class)->find(1);
    // Check if the user is authenticated
    if (!$user) {
        return new JsonResponse(['error' => 'User not authenticated'], 401);
    }

    // Check if the user has already liked the event
     // Check if the user has already liked the event
     if ($event->getLikedByUsers()->contains($user)) {
        return new JsonResponse(['message' => 'User has already liked the event']);
    }

    // Create a new LikedEvent instance
    $likedEvent = new Likedevent();
    $likedEvent->setUser($user);
    $likedEvent->setEvent($event);
    $likedEvent->setRating(5); // You may adjust the rating as needed

    // Add the liked event to the event entity
    $event->addLikedByUser($likedEvent);

    // Persist the changes to the database
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($likedEvent);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Event liked successfully']);

}

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/event/{id}/join', name: 'join_event', methods: ['POST'])]
    public function joinEventAction(Event $event, EventRepository $eventRepository, UrlGeneratorInterface $urlGenerator,QrcodeService $qrcodeService, MailerInterface $mailer): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $paymentSuccess = $this->processPayment($event->getId());

        if (!$paymentSuccess) {
            return new JsonResponse(['error' => 'Payment failed. Unable to join the event.'], 400);
        }
        // Retrieve the authenticated user
        $user = $entityManager->getRepository(User::class)->find(1);

        // Check if the user is authenticated
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        // Check if the event allows adding more players
        if ($event->getNbrparticipants() <= count($event->getIdplayer())) {
            return new JsonResponse(['error' => 'Event is full, cannot add more players'], 400);
        }

        // Check if the user is already a player in the event
        if ($event->getIdplayer()->contains($user)) {
            return new JsonResponse(['error' => 'User is already a player in the event']);
        }

        // Add the user to the event as a player
        $event->addIdplayer($user);

        // Persist the changes to the database
        $entityManager->flush();

        try {
            $eventRepository->insertIntoParticipationTable($entityManager, $user->getId(), $event->getId());
        } catch (\Exception $e) {
            // Handle any errors that occur during the insertion process
            return new JsonResponse(['error' => 'Failed to add player to event: ' . $e->getMessage()], 500);
        }

        // Generate QR code URL
        $generateQRCodeUrl = $urlGenerator->generate('generate_qrcode', ['eventId' => $event->getId()]);



        // Construct event data string to encode into QR code
        $eventData = sprintf(
            "Name: %s\nStart Date: %s\nEnd Date: %s\nPrice: %s\nStart Time: %s\nEnd Time: %s",
            $event->getName(),
            $event->getDatedeb()->format('Y-m-d'),
            $event->getDatefin()->format('Y-m-d'),
            $event->getPrice(),
            $event->getStarttime()->format('H:i:s'),
            $event->getEndtime()->format('H:i:s')
        );

        // Generate QR code
        $qrCodeDataUri = $qrcodeService->qrcode($eventData);

        // Create new TCPDF instance
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Add a page
        $pdf->AddPage();

        // Embed QR code image into PDF
        $pdf->Image('@' . $qrCodeDataUri, 10, 10, 100, 100, '', '', '', false, 300, '', false, false, 0, 'CM');

        // Output PDF to specified file path
        $pdfDirectory = dirname(__DIR__, 2) . '/public/pdf/';
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, true);
        }
        $pdfFilePath = $pdfDirectory . uniqid('', '') . '.pdf';
        $pdf->Output($pdfFilePath, 'F');

        // Define email parameters
        $subject = 'QR Code for Event';
        $from = 'bchirben8@gmail.com';
        $to = 'bechirbenslimene8@gmail.com';
        $htmlContent = '<p>Here is your QR code for the event:</p>';
        $htmlContent .= '   http://127.0.0.1:8000/qrcode/'.$event->getId();

        // Send email with PDF attachment
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($htmlContent);

        // Attach PDF to email
        $email->attachFromPath($pdfFilePath);

        // Send email
        $mailer->send($email);


        return new JsonResponse(['message' => 'Player added to event successfully', 'qrCodeUrl' => $generateQRCodeUrl]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/qrcode/{eventId}', name: 'generate_qrcode')]
    public function generateQRCode(Request $request, $eventId, QrcodeService $qrcodeService, MailerInterface $mailer): Response
    {
        // Fetch event data from the database
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Construct event data string to encode into QR code
        $eventData = sprintf(
            "Name: %s\nStart Date: %s\nEnd Date: %s\nPrice: %s\nStart Time: %s\nEnd Time: %s",
            $event->getName(),
            $event->getDatedeb()->format('Y-m-d'),
            $event->getDatefin()->format('Y-m-d'),
            $event->getPrice(),
            $event->getStarttime()->format('H:i:s'),
            $event->getEndtime()->format('H:i:s')
        );

        // Generate QR code
        $qrCodeDataUri = $qrcodeService->qrcode($eventData);

        // Create new TCPDF instance
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Add a page
        $pdf->AddPage();

        // Embed QR code image into PDF
        $pdf->Image('@' . $qrCodeDataUri, 10, 10, 100, 100, '', '', '', false, 300, '', false, false, 0, 'CM');

        // Output PDF to specified file path
        $pdfDirectory = dirname(__DIR__, 2) . '/public/pdf/';
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, true);
        }
        $pdfFilePath = $pdfDirectory . uniqid('', '') . '.pdf';
        $pdf->Output($pdfFilePath, 'F');

        // Define email parameters
        $subject = 'QR Code for Event';
        $from = 'bchirben8@gmail.com';
        $to = 'bechirbenslimene8@gmail.com';
        $htmlContent = '<p>Here is your QR code for the event:</p>';
        $htmlContent .= '   http://127.0.0.1:8000/qrcode/'.$eventId;

        // Send email with PDF attachment
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($htmlContent);

        // Attach PDF to email
        $email->attachFromPath($pdfFilePath);

        // Send email
        $mailer->send($email);

        // Render the QR code page
        return $this->render('home/qrcode.html.twig', [
            'qrCode' => $qrCodeDataUri,
            'qrCodeFileName' => $pdfFilePath // Pass the PDF file path to the view
        ]);
    }

    private function processPayment(int $eventId): bool
    {
        // Retrieve the event entity based on the ID
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if (!$event) {
            // Event not found, handle the error accordingly
            return false;
        }

        // Set your Stripe API key
        Stripe::setApiKey('sk_test_51Ovrmh08QtU0D1sdk6RQOQgjTXrmyUHCPk7DebzM4OYACK2CD3Ghc0BO9QyvPWr6lQvZfVSEaVE0k4ljHlwgpgCF00OgB4llLk');

        try {
            // Create a PaymentIntent with the amount (in cents) and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => $event->getPrice() * 100, // Convert price to cents
                'currency' => 'usd', // Change to your currency code if different
            ]);

            // Payment successful, return true
            return true;
        } catch (\Exception $e) {
            // Handle payment failure if necessary
            // Log error or render an error page
            return false;
        }
    }
    public function filterEvents(Request $request): JsonResponse
    {
        // Fetch events based on the filter criteria
        $filteredEvents = $this->getDoctrine()->getRepository(Event::class)->filterByName($request->query->get('name'));

        // Array to store the count of liked events for each filtered event
        $likedEventCounts = [];
        $joinEventCounts = [];

        // Fetch the count of liked events for each filtered event
        foreach ($filteredEvents as $event) {
            // Count the number of liked events for the current event
            $likedEventCounts[$event->getId()] = $event->getLikedByUsers()->count();
            $joinEventCounts[$event->getId()] = $event->getIdplayer()->count();
        }

        // Prepare data for JSON response
        $responseData = [];

        // Iterate over filtered events to build response data
        foreach ($filteredEvents as $event) {
            // Build event data array
            $eventData = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'startDate' => $event->getDatedeb()->format('Y-m-d'), // Format date as needed
                'endDate' => $event->getDatefin()->format('Y-m-d'), // Format date as needed
                'startTime' => $event->getStarttime()->format('H:i:s'),
                'endTime' => $event->getEndTime()->format('H:i:s'),
                'price' => $event->getPrice(),
                'participant' => $event->getNbrparticipants(), // Corrected line
                'likedCount' => $likedEventCounts[$event->getId()],
                'joinCount' => $joinEventCounts[$event->getId()],
                'image' => $event->getIdimage()->isEmpty() ? null : $event->getIdimage()->first()->getUrl()
            ];

            // Add event data to response array
            $responseData[] = $eventData;
        }

        // Return the rendered events as JSON response
        return new JsonResponse(['events' => $responseData]);
    }



}