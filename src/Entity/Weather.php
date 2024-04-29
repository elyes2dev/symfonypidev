<?php

namespace App\Entity;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Weather
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getWeatherForecast(string $location, int $days = 7): array
    {
        $client = HttpClient::create();
        $url = sprintf('https://api.weatherapi.com/v1/forecast.json?key=%s&q=%s&days=%d', $this->apiKey, $location, $days);
        try {
            $response = $client->request('GET', $url);
        } catch (TransportExceptionInterface $e) {
        }

        return $response->toArray();
    }
}
