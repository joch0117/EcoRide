<?php

namespace App\Controller;

use App\Document\DailyStat;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestMongoController extends AbstractController
{
    #[Route('/test-daily-stat', name: 'test_daily_stat')]
    public function test(DocumentManager $documentManager): Response
    {
        // Création d'un nouvel objet DailyStat
        $dailyStat = new DailyStat();
        $dailyStat->setDate(new \DateTimeImmutable());
        $dailyStat->setNewUsers(5);

        // Persist et flush dans MongoDB
        $documentManager->persist($dailyStat);
        $documentManager->flush();

        return new Response('✅ DailyStat enregistré dans MongoDB avec succès !');
    }
}
