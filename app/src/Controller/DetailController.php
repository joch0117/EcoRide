<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailController extends AbstractController
{
    #[Route('/detail/{id}', name: 'app_detail')]
    public function show(int $id,TripRepository $tripRepository): Response
    {

        $trip=$tripRepository->find($id);

        if(!$trip){
            throw $this->createNotFoundException('trajet introuvable');
        }

        return $this->render('detail/index.html.twig',[
            'trip'=>$trip,
        ]);
    }
}
