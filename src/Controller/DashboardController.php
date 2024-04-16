<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request): Response
    {
        // Create a new instance of your Product entity
        $product = new Product();

        // Create the form
        $form = $this->createForm(ProductType::class, $product);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission logic (e.g., saving the product)
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            // Redirect to a success page or render the dashboard again
            return $this->redirectToRoute('app_dashboard');
        }

        // Pass the form to the Twig template
        return $this->render('dashboard/dashboard.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/index', name: 'app_index')]
    public function index2(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}

