<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\User;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\CityService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;
use DateInterval;


class ClubController extends AbstractController
{
    #[Route('/test', name: 'app_club_index1', methods: ['GET'])]
    public function index1(ClubRepository $clubRepository): Response
    {
        return $this->render('home/index.html.twig', [
        ]);
    }

    #[Route('/club', name: 'app_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    #[Route('/club/new', name: 'app_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

           // Set a flag to indicate new club creation

        $club = new Club();
        
    
        // Retrieve the user with ID 2 from the database
        $user = $entityManager->getRepository(User::class)->find(2);
    
        // If the user with ID 2 does not exist, handle the error appropriately
        if (!$user) {
            throw $this->createNotFoundException('User with ID 2 not found');
        }
    
        // Set the user for the club
        $club->setIduser($user);
    
        // Set the start time to the current time
        $club->setStarttime(new DateTime());
    
        // Set the end time to 24 hours later
        $endTime = new DateTime();
        $endTime->add(new DateInterval('PT24H'));
        $club->setEndtime($endTime);
    
        // Set stadiumnbr, longitude, and latitude to 0
        $club->setStadiumnbr(0);
        $club->setLongitude(0);
        $club->setLatitude(0);

        // Create and handle the form
        $form = $this->createForm(ClubType::class, $club, [
            'is_edit_form' => true, // Pass the flag to the form builder

        ]);
        $form->handleRequest($request);
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form['images']->getData();

            foreach ($images as $imageFile) {
                // Your image upload logic here
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/Images',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the image.');
                }

                $image = new Image();
                $image->setName($originalFilename);
                $image->setUrl('Images/' . $newFilename);
                $image->setType('club');

                $entityManager->persist($image);
                $club->addIdimage($image);
            }
            $removedImages = $request->request->get('removed_images');
            if (is_iterable($removedImages) && !empty($removedImages)) {
                foreach ($removedImages as $removedImageId) {
                    // Find the image entity
                    $image = $entityManager->getRepository(Image::class)->find($removedImageId);
    
                    if ($image) {
                        // Remove the association between the club and the image
                        $club->removeIdimage($image);
                        // Remove the image entity from the database
                        $entityManager->remove($image);
                    }
                }
            }

            $entityManager->persist($club);
            $entityManager->flush();

            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render the new club form
        return $this->renderForm('club/new.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }
    #[Route('/club/{id}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
           // Get the selected governorate and city from the club
    $selectedGovernorate = $club->getGovernorate();
    $selectedCity = $club->getCity();

    // Create the edit form and pass the selected governorate and city values
    $form = $this->createForm(ClubType::class, $club, [
        'selected_governorate' => $selectedGovernorate,
        'selected_city' => $selectedCity,
        'is_edit_form' => false, // Pass the flag to the form builder

    ]);
    
        $form->handleRequest($request);

        dump($form);
        dump($selectedCity);
        dump($selectedGovernorate);
        
    
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form['images']->getData();
            foreach ($images as $imageFile) {
                // Your image upload logic here
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/Images',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the image.');
                }

                $image = new Image();
                $image->setName($originalFilename);
                $image->setUrl('Images/' . $newFilename);
                $image->setType('club');

                $entityManager->persist($image);
                $club->addIdimage($image);
            }
            
            // Handle image deletion
            $removedImages = $request->request->get('removed_images');
            if (is_iterable($removedImages) && !empty($removedImages)) {
                foreach ($removedImages as $removedImageId) {
                    // Find the image entity
                    $image = $entityManager->getRepository(Image::class)->find($removedImageId);
    
                    if ($image) {
                        // Remove the association between the club and the image
                        $club->removeIdimage($image);
                        // Remove the image entity from the database
                        $entityManager->remove($image);
                    }
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }
    

    
    
#[Route('/club/{id}/delete', name: 'app_club_delete', methods: ['POST'])]
public function delete(Request $request, Club $club, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $club->getId(), $request->request->get('_token'))) {
        // Remove images associated with the club
        foreach ($club->getIdimage() as $image) {
            $entityManager->remove($image);
        }

        // Remove the club itself
        $entityManager->remove($club);
        $entityManager->flush();
    }

    // Redirect to index page after deletion
    return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
}

    

#[Route('/club/{id}/show', name: 'app_club_show', methods: ['GET'])]
public function show(Club $club): Response
{
    return $this->render('club/show.html.twig', [
        'club' => $club,
    ]);
}


#[Route('/club/fetch-cities', name: 'fetch_cities', methods: ['POST'])]
public function fetchCities(Request $request, CityService $cityService): JsonResponse
{
    // Get the selected governorate from the request
    $governorate = $request->request->get('governorate');

    // Fetch cities for the selected governorate using the CityService
    $cities = $cityService->getCitiesForGovernorate($governorate);

    return $this->json(['cities' => $cities]);
}







}
