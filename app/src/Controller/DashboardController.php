<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/espace-utilisateur', name: 'app_dashboard')]
    public function index( ): Response
    {
        $user= $this->getUser();

        return $this->render('dashboard/index.html.twig',
    [
        'user'=>$user,
    ]);
    }
}
