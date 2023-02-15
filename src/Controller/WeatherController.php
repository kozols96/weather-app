<?php

namespace App\Controller;

use App\Entity\ClientData;
use App\Repository\ClientDataRepository;
use App\Service\GeoLocator;
use App\Service\IpLocator;
use App\Service\WeatherForecaster;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class WeatherController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function getWeatherForecast(
        Request $request,
        IpLocator $ipLocator,
        GeoLocator $geoLocator,
        WeatherForecaster $weatherForecaster,
        ClientDataRepository $clientDataRepository,
        ManagerRegistry $doctrine
    ): JsonResponse
    {
        $ipAddress = $ipLocator->getIpAddress($request);
        $clientData = $clientDataRepository->findOneByIpAddress($ipAddress);

        [$latitude, $longitude] = $clientData !== null
            ? [$clientData->getLatitude(), $clientData->getLongitude()]
            : $geoLocator->getLocation($ipAddress);

        $weather = $weatherForecaster->getCurrentWeather($latitude, $longitude);

        if ($clientData === null) {
            $entityManager = $doctrine->getManager();

            $clientData = new ClientData();
            $clientData->setIpAddress($ipAddress)
                ->setLatitude($latitude)
                ->setLongitude($longitude);

            $entityManager->persist($clientData);
            $entityManager->flush();
        }

        return $this->json($weather);
    }
}