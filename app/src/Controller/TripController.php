<?php

namespace App\Controller;

use App\Form\FilterTripType;
use App\Form\SearchTripType;
use App\Services\TripSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TripController extends AbstractController
{
#[Route('/recherche-covoiturage', name: 'app_trip_search')]
public function search(TripSearchService $tripSearchService, Request $request): Response
{
    // Formulaire principal de recherche
    $searchForm = $this->createForm(SearchTripType::class);
    $searchForm->handleRequest($request);

    // Formulaire secondaire pour les filtres
    $filterForm = $this->createForm(FilterTripType::class,null,['method'=>"GET",'csrf_protection'=>false,]);
    $filterForm->handleRequest($request);

    // On affiche les données brutes pour debugger
    dump($searchForm->getData());
    dump($filterForm->getData());

    $trips = [];

    if ($searchForm->isSubmitted() && $searchForm->isValid()) {
        $searchData = $searchForm->getData();
        $filterData = $filterForm->getData() ??[];

        $trips = $tripSearchService->searchTrip(
            $searchData['departureCity'] ?? null,
            $searchData['arrivalCity'] ?? null,
            $searchData['date'] ?? null, // attention ici à bien mettre 'date'
            $filterData
        );
    }

    return $this->render('trip/search.html.twig', [
        'form' => $searchForm->createView(),
        'filterForm' => $filterForm->createView(),
        'trips' => $trips,
    ]);
}

}
