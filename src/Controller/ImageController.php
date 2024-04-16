<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig', [
            'controller_name' => 'ImageController',
        ]);
    }

    
    #[Route('/remove-image', name:"remove_image", methods: ['GET'])]
    public function removeImage(Request $request): Response
    {
        // Handle the image removal logic here
        // Retrieve the image ID from the request
        $imageId = $request->request->get('image_id');

        // Delete the image from the database using the ID

        // Return a JSON response indicating success or failure
        return $this->json(['message' => 'Image removed successfully']);
    }
}
