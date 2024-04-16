<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EventRepository;
class HomeController extends AbstractController

{
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }


    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $events = $this->eventRepository->findAll();

    return $this->render('home/index.html.twig', [
        'events' => $events,
    ]);
    }
}
