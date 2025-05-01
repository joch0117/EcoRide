<?php

namespace App\Controller;

use App\Form\FilterTripType;
use App\Form\SearchTripType;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TripController extends AbstractController
{
    #[Route('/recherche-covoiturage', name: 'app_trip_search')]
    public function search(TripRepository $tripRepository,Request $request): Response
    {
        $searchData =[
            'departureCity'=>$request->query->get('departureCity'),
            'arrivalCity'=>$request->query->get('arrivalCity'),
            'date'=>$request->query->get('date'),
        ];

        $form = $this->createForm(SearchTripType::class,$searchData);
        $form->handleRequest($request);
        $search=$form->getData();

        $filterData = [
            'maxPrice' => $request->query->get('maxPrice'),
            'vehicleType' => $request->query->get('vehicleType'),
            'sortBy' => $request->query->get('sortBy'),
            'isEcological' => $request->query->getBoolean('isEcological'),
            'minRating' => $request->query->get('minRating'),
            'maxDuration' => $request->query->get('maxDuration'),
        ];

        $filterForm = $this->createForm(FilterTripType::class, $filterData, [
            'method' => 'GET'
        ]);

        $filterForm->handleRequest($request);
        $filters = $filterForm->getData();
        $trips = [];
    
        $trips = $tripRepository->findBySearch(
            $search['departureCity'] ?? null,
            $search['arrivalCity'] ?? null,
            $search['date'] ?? null,
            $filters
        );
    
        return $this->render('trip/search.html.twig', [
            'form' => $form->createView(),
            'filterForm' => $filterForm->createView(),
            'trips' => $trips,
        ]);
    }
}
