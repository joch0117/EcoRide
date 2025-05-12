<?php

namespace App\Service;

use App\Entity\Trip;
use App\Enum\StatusTrip;
use App\Service\MailerService;
use App\Repository\BookingRepository;
use App\Repository\CreditTransactionRepository;
use App\Service\MailerService as ServiceMailerService;
use Doctrine\ORM\EntityManagerInterface;

class HistoryService
{
    public function __construct(
        private EntityManagerInterface $em,
        private BookingRepository $bookingRepository,
        private CreditTransactionRepository $creditTransaction,
        private CreditService $creditService,
        private ServiceMailerService $mailerService
    ){}


    public function cancelTrip(Trip $trip)
    {
        foreach ($trip->getBookings() as $booking) {
            $this->mailerService->sendCancelTrip($trip , $booking->getUser());
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

        foreach ($trip->getBookings() as $booking) {
        $this->mailerService->sendRatingRequest($booking->getUser(), $trip);
        }
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