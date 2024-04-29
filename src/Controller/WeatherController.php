<?php

namespace App\Controller;

use App\Entity\Weather;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/weather', name: 'weather')]
    public function showWeatherForecast(Weather $weather)
    {
        $weatherForecast = $weather->getWeatherForecast('Paris', 7);

        // Rendre la vue et passer les données météorologiques
        return $this->render('home/weather.html.twig', [
            'weatherForecast' => $weatherForecast,
        ]);
    }
}
