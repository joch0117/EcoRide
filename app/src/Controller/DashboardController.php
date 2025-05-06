<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/espace-utilisateur', name: 'app_dashboard')]
    public function index(ReviewRepository $reviewRepo ): Response
    {
        /**
         * @var User $user
         */
        $user= $this->getUser();
        
        if(!$user->isProfilComplet()){
            return $this->redirectToRoute('app_user_profile');
        }

        if($user->isDriver() && $user->getVehicles()->isEmpty()){
            $this->addFlash('warning','Ajoutez un vehicule pour accéder à votre espace chauffeur');
            return $this->redirectToRoute('app_vehicle');
        }

        $average= $reviewRepo->getAverageForDriver($user) ?? 0.0;
        $vehicles= $user->getVehicles();
        
        return $this->render('dashboard/index.html.twig',
    [
        'user'=>$user,
        'average'=>$average,
        'vehicles'=> $vehicles,
    ]);
    }
}
