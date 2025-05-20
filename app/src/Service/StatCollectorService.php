<?php

namespace App\Service;

use App\Document\SiteStat;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Repository\CreditTransactionRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class StatCollectorService
{
    public function __construct(
        private TripRepository $tripRepository,
        private CreditTransactionRepository $transactionRepository,
        private UserRepository $userRepository,
        private DocumentManager $documentManager
    ) {}
    
    public function collect(): SiteStat
    {
        $nbTrajets = $this->tripRepository->count([]);
        $totalCredits = $this->transactionRepository->sumPlatformWin();
        $nbUsers = $this->userRepository->count([]);

        $stat = new SiteStat();
        $stat->setDate(new \DateTime());
        $stat->setNbTrajets($nbTrajets);
        $stat->setCreditsGagnes($totalCredits);
        $stat->setNbUtilisateurs($nbUsers);

        $this->documentManager->persist($stat);
        $this->documentManager->flush();

        return $stat;
    }
}
