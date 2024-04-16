<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/dashboard/users', name: 'dashUsers')]
    public function DashUsers(EntityManagerInterface $entityManager): Response
    {
        // Fetch all users using Doctrine's Repository
        $userRepository = $entityManager->getRepository(User::class); // Replace 'User' with your actual user entity class
        $users = $userRepository->findAll();
    
        // Optional: Filter or sort users if needed (explained later)
    
        return $this->render('dashbord_users/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'users' => $users, // Pass the retrieved users to the template
        ]);
    }




    
    #[Route('/index', name: 'app_dashboard')]
    public function index2(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
