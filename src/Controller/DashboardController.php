<?php

namespace App\Controller;
use App\Controller\UserController;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;



class DashboardController extends AbstractController
{   
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(UserRepository $userRepository): Response
    {
        // Get total number of users
        $totalUsers = $userRepository->countAllUsers();

        // Get the number of users per role
            $adminCount = $userRepository->countUsersPerRole('Admin');
            $fieldOwnerCount = $userRepository->countUsersPerRole('FieldOwner');
            $playerCount = $userRepository->countUsersPerRole('Player');

        // Get count of females and males
        $femaleCount = $userRepository->countUsersByGender('female');
        $maleCount = $userRepository->countUsersByGender('male');

         // Get age repartition
        $ageDistribution = $userRepository->getAgeDistribution();
        // Format the data for use in the chart
        $userAges = [];
        foreach ($ageDistribution as $data) {
            $userAges[] = ['ageGroup' => $data['ageGroup'], 'userCount' => $data['userCount']];
        }


        // Retrieve user data with their regions using Doctrine ORM
        $usersByRegion =  $userRepository->getUsersByRegion();




        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
            'fieldOwnerCount' => $fieldOwnerCount,
            'playerCount' => $playerCount,
            'femaleCount' => $femaleCount,
            'maleCount' => $maleCount,
            'userAges' => $userAges,
            'usersByRegion' => $usersByRegion,
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
