<?php

namespace App\Controller;

use App\Entity\Event;
use App\Services\QrcodeService;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TCPDF;

class QrCodeController extends AbstractController
{


    private $entityManager;

    #[Route('/payment/{id}', name: 'payment')]
    public function payment(Request $request, int $id): Response
    {
        // Retrieve the panier entity based on the id
        $event = $this->entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Panier not found');
        }


        // Set your Stripe API key
        Stripe::setApiKey('sk_test_51Ovrmh08QtU0D1sdk6RQOQgjTXrmyUHCPk7DebzM4OYACK2CD3Ghc0BO9QyvPWr6lQvZfVSEaVE0k4ljHlwgpgCF00OgB4llLk');

        try {
            // Create a PaymentIntent with the amount (in cents) and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => $event->getPrice()* 100, // Convert salary to cents
                'currency' => 'usd', // Change to your currency code if different
            ]);

            // Redirect to the payment success route
            return $this->redirectToRoute('payment_success', ['paymentIntentId' => $paymentIntent->id]);
        } catch (\Exception $e) {
            // Handle payment failure if necessary
            // Render an error page or return a response with an appropriate message
            $errorMessage = $e->getMessage();
            return $this->render('payment/error.html.twig', ['errorMessage' => $errorMessage]);
        }
    }

    #[Route('payment/success/{paymentIntentId}', name: 'payment_success')]
    public function paymentSuccess(string $paymentIntentId): Response
    {
        // Handle successful payment
        // You can retrieve payment details using $paymentIntentId
        // Render a success page or perform any other actions
        return $this->render('payment/success.html.twig', ['paymentIntentId' => $paymentIntentId]);
    }

    #[Route('payment/cancel', name: 'payment_cancel')]
    public function paymentCancel(): Response
    {
        // Handle cancelled payment
        // Render a cancellation page or perform any other actions
        return $this->render('payment/cancel.html.twig');
    }


    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/qrcode/{eventId}', name: 'generate_qrcode')]
    public function generateQRCode(Request $request, $eventId, QrcodeService $qrcodeService, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
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
}
