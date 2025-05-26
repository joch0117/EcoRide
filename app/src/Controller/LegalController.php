<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    //page de condition général d'utilisation
    #[Route('/cgu', name: 'app_cgu')]
    public function CGU(): Response
    {
        return $this->render('legal/cgu.html.twig');
    }
    //page mention légale
    #[Route('/mentions-legales', name: 'app_mentions')]
    public function mention(): Response
    {
        return $this->render('legal/mentions.html.twig');
    }
}
