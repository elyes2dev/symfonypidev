<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowEventController extends AbstractController
{
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    #[Route('/ShowEvent', name: 'ShowEvent')]
    public function index(): Response
    {
        $events = $this->eventRepository->findAll();

        return $this->render('dashboard/ShowEvent.html.twig', [
            'events' => $events,
        ]);
    }
}
