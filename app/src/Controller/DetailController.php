<?php

namespace App\Controller;

use App\Repository\TripRepository;
use App\Service\AverageRatingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class DetailController extends AbstractController
{
    #[Route('/detail/{id}', name: 'app_detail')]
    public function show(int $id,TripRepository $tripRepository,AverageRatingService $averageRatingService): Response
    {

        $trip=$tripRepository->find($id);
        $user= $trip->getDriver();
        $average = $averageRatingService->getAverageRating($user);
        $user->setAverageRating($average);

        if(!$trip){
            throw $this->createNotFoundException('trajet introuvable');
        }

        return $this->render('detail/index.html.twig',[
            'trip'=>$trip,
            'user'=>$user,
            'average'=>$average
        ]);
    }
}
