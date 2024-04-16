<?php

namespace App\Controller;
use App\Entity\Event;
use App\Entity\TaskEvent;
use App\Form\TaskEventType;
use App\Repository\TaskEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/task/event')]
class TaskEventController extends AbstractController
{
    #[Route('/', name: 'app_task_event_index', methods: ['GET'])]
    public function index(TaskEventRepository $taskEventRepository): Response
    {
        return $this->render('task_event/index.html.twig', [
            'task_events' => $taskEventRepository->findAll(),
        ]);
    }

    #[Route('/add_task/{eventId}', name: 'app_add_task', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, int $eventId): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        $task = new TaskEvent();
        $task->setEventId($event);

        // Set the creation date to the current date
        $currentDate = new \DateTime();
       

        $form = $this->createForm(TaskEventType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set the updated date to the current date
            $task->setUpdatedDate($currentDate);
            $task->setCreationDate($currentDate);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('show_task_by_event', ['eventId' => $eventId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/AddTask.html.twig', [
            'task_event' => $task,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_task_event_show', methods: ['GET'])]
    public function show(TaskEvent $taskEvent): Response
    {
        return $this->render('task_event/show.html.twig', [
            'task_event' => $taskEvent,
        ]);
    }

    #[Route('/Task/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskEvent $taskEvent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskEventType::class, $taskEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $taskEvent->setUpdatedDate(new DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('show_task_by_event', ['eventId' => $taskEvent->getEventId()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/UpdateTaskEvent.html.twig', [
            
            'taskEvent' => $taskEvent,

            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_event_delete', methods: ['POST'])]
    public function delete(Request $request, TaskEvent $taskEvent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taskEvent->getId(), $request->request->get('_token'))) {
            
            $entityManager->remove($taskEvent);
            
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_task_by_event', ['eventId' => $taskEvent->getEventId()->getId()], Response::HTTP_SEE_OTHER);
    }
}
