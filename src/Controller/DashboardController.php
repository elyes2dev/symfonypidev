<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/index', name: 'app_dashboard')]
    public function index2(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/claim', name: 'app_claim')]
    public function claim(): Response
    {
        return $this->render('claim.html.twig');
    }
    #[Route('/reservation', name: 'app_calendar')]
    public function calendar(): Response
    {
        return $this->render('reservation/index.html.twig');
    }
}
