<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\TaskEvent;
use App\Entity\Club;
use App\Entity\Image;
use App\Entity\User;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AddEventController extends AbstractController
{
    #[Route('/AddEvent', name: 'AddEvent')]
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        // Create new instance of the Event entity
        $event = new Event();
        $user = $entityManager->getRepository(User::class)->find(1);
        $event->addIdplayer($user);

        // Get the Club entity by id (assuming idClub is fixed to 1)
        $club = $entityManager->getRepository(Club::class)->find(1);
        $event->setIdClub($club);


        // Create the form using the EventType form type and pass the entity
        $eventForm = $this->createForm(EventType::class, $event);

        // Handle form submission for event form
        $eventForm->handleRequest($request);

        // Check if the event form is submitted and valid
        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $taskEvent = new TaskEvent();

            // Set properties of the TaskEvent from form data
            $taskEvent->setDescription($eventForm->get('description')->getData());
            $taskEvent->setEtat($eventForm->get('etat')->getData());
            $taskEvent->setCreationDate(new \DateTime());
            $taskEvent->setUpdatedDate(new \DateTime());

            // Add the TaskEvent to the Event
            $event->addTaskEvent($taskEvent);

            $images = $eventForm['images']->getData();

            foreach ($images as $imageFile) {
                // Your image upload logic here
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/EventImages',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the image.');
                }

                $image = new Image();
                $image->setName($originalFilename);
                $image->setUrl('EventImages/' . $newFilename);
                $image->setType('Event');

                $entityManager->persist($image);
                $event->addIdimage($image);
            }
            // Handle image deletion
            $removedImages = $request->request->get('removed_images');
            if (is_iterable($removedImages) && !empty($removedImages)) {
                foreach ($removedImages as $removedImageId) {
                    // Find the image entity
                    $image = $entityManager->getRepository(Image::class)->find($removedImageId);
    
                    if ($image) {
                        // Remove the association between the club and the image
                        $event->removeIdimage($image);
                        // Remove the image entity from the database
                        $entityManager->remove($image);
                    }
                }
            }

            
            $entityManager->persist($event);

            $entityManager->flush();

            return $this->redirectToRoute('show_event', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('dashboard/AddEvent.html.twig', [
            'event' => $event,
            'form' => $eventForm->createView(),
        ]);
        
    }
}
