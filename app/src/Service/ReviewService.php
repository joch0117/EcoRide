<?php

namespace App\Service;


use App\Entity\IncidentReport;
use App\Entity\Review;
use App\Entity\Trip;
use App\Entity\User;
use App\Service\CreditService;
use App\Enum\StateChecked;
use App\Enum\StatusReview;
use Doctrine\ORM\EntityManagerInterface;


class ReviewService
{

    public function __construct(
        private EntityManagerInterface $em,
        private CreditService $creditService
        ){}

    public function validateTrip(Review $review,Trip $trip,User $user){
        
        $driver= $trip->getDriver();
        $price= $trip->getPrice();
        
        $review->setWriter($user);
        $review->setTrip($trip);
        $review->setDriver($driver);
        $review->setStatus(StatusReview::PENDING);

        $this->em->persist($review);

        $this->creditService->creditDriver($driver , $trip, $price);


        $this->em->flush();
    }
    public function problemSignaled(Trip $trip,Review $review , User $user){
        
        $driver = $trip->getDriver();
        $now= new \DateTimeImmutable();

        $review->setWriter($user);
        $review->setTrip($trip);
        $review->setDriver($driver);
        $review->setStatus(StatusReview::PENDING);

        $this->em->persist($review);

        $incident= new IncidentReport();
        $incident->setTrip($trip);
        $incident->setReporter($user);
        $incident->setDescription($review->getComment());
        $incident->setIncidentStatus(StateChecked::NOCHECKED);

        $this->em->persist($incident);

        $this->em->flush();

    }
}