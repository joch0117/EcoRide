<?php

namespace App\Services;

use App\Entity\Trip;
use App\Enum\StatusTrip;
use App\Repository\BookingRepository;
use App\Repository\CreditTransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

class HistoryService
{
    public function __construct(
        private EntityManagerInterface $em,
        private BookingRepository $bookingRepository,
        private CreditTransactionRepository $creditTransaction,
        private CreditService $creditService
    ){}


    public function cancelTrip(Trip $trip)
    {
        foreach ($trip->getBookings() as $booking) {
            $this->creditService->rembourserBooking($booking);
            $this->em->remove($booking);
        }

        $trip->setStatus(StatusTrip::CANCELLED);

        $this->em->flush();
    }

    public function startTrip(Trip $trip)
    {
        $trip->setStatus(StatusTrip::STARTED);
        $this->em->flush();
    }
    public function arrivalTrip(Trip $trip)
    {
        $trip->setStatus(StatusTrip::FINISHED);
        $this->em->flush();
    }

    public function autoRefoundIfExpired(Trip $trip)
    {
        $now = new \DateTimeImmutable();

        if ($trip->getStatus()=== StatusTrip::SCHEDULED && $trip->getDepartureDatetime() < $now){
            $this->cancelTrip($trip);
        }
    }
}