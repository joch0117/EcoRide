<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/espace-utilisateur', name: 'app_dashboard')]
    public function index( ): Response
    {
        /**
         * @var User $user
         */
        $user= $this->getUser();
        
        if(!$user->isProfilComplet()){
            return $this->redirectToRoute('app_user_profile');
        }
        return $this->render('dashboard/index.html.twig',
    [
        'user'=>$user,
    ]);
    }
}
