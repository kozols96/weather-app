<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\
{
    ClientExceptionInterface,
    DecodingExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface
};
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherForecaster
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string              $openWeatherAppId
    )
    {
    }

    /**
     * @param string $latitude
     * @param string $longitude
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCurrentWeather(string $latitude, string $longitude): array
    {
        $response = $this->httpClient->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather',
            [
                'query' => [
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'units' => 'metric',
                    'appid' => $this->openWeatherAppId
                ]
            ]
        );

        return $response->toArray();
    }
}