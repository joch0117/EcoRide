<?php

namespace App\Service;


use App\Entity\CreditTransaction;
use App\Enum\StateBooking;
use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Trip;
use App\Enum\CreditTransactionType;
use App\Enum\StatusFeedback;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookingService
{
    public function __construct(private EntityManagerInterface $em,private UserRepository $userRepo,private CreditService $creditService ){}

    //fonction de vérification de condition pour la reservation
    public function canUserBook(User $user,Trip $trip): ?string
    {
        if ($trip->getSeatsAvailable()<=0){
            return "ce trajet est complet.";
        }

        if($trip->isUserBooked($user)){
            return " Vous êtes déjà inscrit sur ce trajet.";
        }

        if ($trip->getDriver() && $trip->getDriver()->getId() === $user->getId()) {
        return "Vous ne pouvez pas réserver votre propre trajet en tant que passager.";
        }

        $total=$trip->getPrice()+2;
        if($user->getCredit()<$total){
            return "Vous n'avez pas assez de crédits.";
        }

        return null;
    }

    //création d'une réservation :table booking +table crédit transaction +table trip mis à jour
    public function createBooking(User $user, Trip $trip):Booking
    {
        $user = $this->userRepo->find($user->getId());
        $total = $trip->getPrice() + 2;
        $now= new \DateTimeImmutable();

        //déduire les crédits du passager
        $user->removeCredits($total);

        //transaction
        $platformTx = new CreditTransaction();
        $platformTx->setUser($user);
        $platformTx->setTrip($trip);
        $platformTx->setAmount(2);
        $platformTx->setDescription('Commission plateforme');
        $platformTx->setCreatedAt($now);
        $platformTx->setType(CreditTransactionType::PLATFORM_FEE);
        $this->em->persist($platformTx);

        $heldTx = new CreditTransaction();
        $heldTx->setUser($user);
        $heldTx->setTrip($trip);
        $heldTx->setAmount(-$trip->getPrice());
        $heldTx->setDescription('Crédits en attente pour le chauffeur');
        $heldTx->setCreatedAt($now);
        $heldTx->setType(CreditTransactionType::DEBIT);
        $this->em->persist($heldTx);

        //création de la réservation
        $booking = new Booking();
        $booking ->setUser($user);
        $booking ->setTrip($trip);
        $booking ->setSeats(1);
        $booking ->setState(StateBooking::CONFIRMED);
        $booking ->setCreatedAt($now);
        $booking ->setFeedbackStatus(StatusFeedback::PENDING);
        $this->em->persist($booking);

        $trip->setSeatsAvailable($trip->getSeatsAvailable()-1);

        return $booking;
    }

    //annulation d'une reservation
    public function cancelBooking(Booking $booking, $currentUser): bool
    {
        if ($booking->getUser() !== $currentUser) {
            return false;
        }

        $trip = $booking->getTrip();
        $trip->setSeatsAvailable($trip->getSeatsAvailable() + $booking->getSeats());

        $this->creditService->rembourserBooking($booking);

        $this->em->remove($booking);
        $this->em->flush();

        return true;
    }
}