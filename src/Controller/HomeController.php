<?php

namespace App\Controller;
use App\Entity\Event;
use App\Entity\Likedevent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventRepository;
use Symfony\Component\Validator\Constraints\Date;

class HomeController extends AbstractController

{
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }


    #[Route('/home', name: 'app_home')]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        // Get the field to order by from the request query parameters
        $orderByField = $request->query->get('orderBy');

        // Check if orderByField is provided
        if ($orderByField) {
            // Get all events ordered by the specified field
            $events = $eventRepository->findAllOrderedByField($orderByField);
        } else {
            // If no orderByField is provided, retrieve all events
            $events = $eventRepository->findAll();
        }

        // Render the template with the events
        return $this->render('home/Events.html.twig', [
            'events' => $events,
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
}