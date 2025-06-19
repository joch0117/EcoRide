<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\CreditTransaction;
use App\Entity\Trip;
use App\Entity\User;
use App\Enum\CreditTransactionType;
use App\Repository\CreditTransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreditService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CreditTransactionRepository $creditTransaction
    ){}

    //anulation de trajet = remboursement reservation
    public function rembourserBooking(Booking $booking)
    {
        $user =$booking->getUser();
        $trip = $booking->getTrip();

        $transactions =$this->creditTransaction->findBy([
            'user'=> $user,
            'trip'=>$trip
        ]);

        $totalToRefund=0;

        foreach($transactions as $transaction){
            if($transaction->getAmount()<0){
                $totalToRefund += abs($transaction->getAmount());
            }
        }

        if ($totalToRefund === 0){
            return false; 
        }

        $lostPlateform = new CreditTransaction();
        $lostPlateform->setUser($user);
        $lostPlateform->setTrip($trip);
        $lostPlateform->setAmount(-2);
        $lostPlateform->setType(CreditTransactionType::PLATFORM_FEE);
        $lostPlateform->setDescription('Remboursement plateform');
        $lostPlateform->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($lostPlateform);

        $this->em->flush();



        $refund = new CreditTransaction();
        $refund->setUser($user);
        $refund->setTrip($trip);
        $refund->setAmount($totalToRefund);
        $refund->setType(CreditTransactionType::REFUND);
        $refund->setDescription('Remboursement suite annulation');
        $refund->setCreatedAt(new \DateTimeImmutable());

        $user->addCredits($totalToRefund);

        $this->em->persist($refund);

        $this->em->flush();

        return true;

        
    }

    //validation d'un trajet mise à jour crédit 
    public function creditDriver(User $chauffeur,Trip $trip, int $montant){
        $chauffeur->addCredits($montant);

        $transaction = new CreditTransaction();
        $transaction->setUser($chauffeur);
        $transaction->setTrip($trip);
        $transaction->setAmount($montant);
        $transaction->setDescription('Crédits suite à la validation d’un trajet');
        $transaction->setType(CreditTransactionType::CREDIT);
        $transaction->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($transaction);
        $this->em->persist($chauffeur);
        $this->em->flush();
    }
}