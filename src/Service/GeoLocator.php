<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\{ClientExceptionInterface,
    DecodingExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface};
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeoLocator
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string              $ipstackAccessKey,
    )
    {
    }

    /**
     * @return array<float>
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getLocation(string $ipAddress): array
    {
        $location = $this->httpClient
            ->request(
                'GET',
                'http://api.ipstack.com/' . $ipAddress,
                [
                    'query' => [
                        'access_key' => $this->ipstackAccessKey
                    ]
                ]
            )->toArray();

        return [$location['latitude'], $location['longitude']];
    }
}