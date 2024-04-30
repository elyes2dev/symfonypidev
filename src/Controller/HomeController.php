<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClubRepository;
use App\Repository\ImageRepository;
use App\Repository\StadiumRepository;




class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ClubRepository $clubRepository): Response
    {
        $clubs = $clubRepository->findAllWithOneStadium();
        $clubImages = [];

    foreach ($clubs as $club) {
        $images = $clubRepository->findImagesByClubId($club->getId());
        $clubImages[$club->getId()] = $images;
    }
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'clubs' => $clubs,
            'clubImages' => $clubImages,
        ]);
    }
    #[Route('/stade', name: 'app_stade')]
    public function stade(StadiumRepository $stadiumRepository): Response
    {
        $stadiums = $stadiumRepository->findAll(); // Fetch all stadiums
    
        return $this->render('home/stade.html.twig', [
            'stadiums' => $stadiums,
        ]);
    }
    
}
