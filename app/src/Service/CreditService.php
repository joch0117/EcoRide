<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\CreditTransaction;
use App\Enum\CreditTransactionType;
use App\Repository\CreditTransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreditService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CreditTransactionRepository $creditTransaction
    ){}

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
}