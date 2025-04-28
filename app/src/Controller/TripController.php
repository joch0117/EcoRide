<?php

namespace App\Controller;

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
        $form = $this->createForm(SearchTripType::class);
        $form->handleRequest($request);
    
        $trips = [];
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $trips = $tripRepository->findBySearch($data['departureCity'], $data['arrivalCity'], $data['date']);
        }
    
        return $this->render('trip/search.html.twig', [
            'form' => $form->createView(),
            'trips' => $trips,
        ]);
    }
}
