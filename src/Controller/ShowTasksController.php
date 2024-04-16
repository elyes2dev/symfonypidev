<?php

namespace App\Controller;

use App\Entity\TaskEvent;
use App\Repository\TaskEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowTaskController extends AbstractController
{
    private $TaskeventRepository;

    public function __construct(TaskEventRepository $TaskeventRepository)
    {
        $this->TaskeventRepository = $TaskeventRepository;
    }

    #[Route('/ShowTask/{eventId}', name: 'show_task_by_event')]
    public function index(int $eventId): Response
    {
        $tasks = $this->TaskeventRepository->findAllByIdEvent($eventId);

        return $this->render('dashboard/ShowTask.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
