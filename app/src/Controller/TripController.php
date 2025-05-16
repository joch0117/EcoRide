<?php

namespace App\Controller;

use App\Form\MiniSearchTripType;
use App\Form\SearchTripType;
use App\Service\TripSearchService;
use App\Service\AverageRatingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class TripController extends AbstractController
{
#[Route('/recherche-covoiturage', name: 'app_trip_search')]
public function search(TripSearchService $tripSearchService, Request $request, AverageRatingService $averageRatingService): Response
{
    // Formulaire principal de recherche
    $searchForm = $this->createForm(SearchTripType::class);
    $searchForm->handleRequest($request);

    //formulaire de recherche du menu
    $miniSearchForm = $this->createForm(MiniSearchTripType::class,null,[
        'method' =>'GET'
    ]);
    $miniSearchForm->handleRequest($request);

    $trips = [];

    if ($request->query->has('mini_search_trip')) {
    $searchData = $miniSearchForm->getData();

    $trips = $tripSearchService->searchTrip(
        $searchData['departureCity'] ?? null,
        $searchData['arrivalCity'] ?? null,
        $searchData['date'] ?? null
    );
    }elseif ($searchForm->isSubmitted() && $searchForm->isValid()) {
        $searchData = $searchForm->getData();

        $trips = $tripSearchService->searchTrip(
            $searchData['departureCity'] ?? null,
            $searchData['arrivalCity'] ?? null,
            $searchData['date'] ?? null, 
        );
    }

    foreach ($trips as $trip) {
    $driver = $trip->getDriver();
    $note = $averageRatingService->getAverageRating($driver);
    $driver->setAverageRating( $note); 
    }

    return $this->render('trip/search.html.twig', [
        'form' => $searchForm->createView(),
        'miniSearchForm' => $miniSearchForm->createView(),
        'trips' => $trips,
    ]);
    }
}
