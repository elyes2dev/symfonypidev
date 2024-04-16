<?php

namespace App\Controller;

use App\Entity\Event;

use App\Entity\Image;

use App\Form\EventType2;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\FormError;

class UpdateEventController extends AbstractController
{
   
    #[Route('/event/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $isNewEvent=false;
        $uploadedImages = $event->getIdimage();
       
        $form = $this->createForm(EventType2::class, $event);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if (
                empty($event->getName()) ||
                empty($event->getDatedeb()) ||
                empty($event->getDatefin()) ||
                empty($event->getStarttime()) ||
                empty($event->getEndtime()) ||
                empty($event->getNbrparticipants()) ||
                empty($event->getPrice())
            ) {
                $form->addError(new FormError('Veuillez remplir tous les champs obligatoires'));
            } else {$images = $form['images']->getData();

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
                $image->setType('event');

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



            $entityManager->flush();

            return $this->redirectToRoute('show_event', [], Response::HTTP_SEE_OTHER);
        }
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Le formulaire contient des erreurs.');
        }

    
        return $this->renderForm('dashboard/UpdateEvent.html.twig', [
            
            'event' => $event,
            'form' => $form,
            'isNewEvent' => $isNewEvent,
            'uploadedImages' => $uploadedImages,

        ]);
    }
    
  
}
