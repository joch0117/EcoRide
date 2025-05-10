<?php

namespace App\Services;

use App\Entity\Trip;
use App\Entity\User;
use App\Enum\EnergyType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;


class TripService
{
    public function __construct(
        private EntityManagerInterface $em,
        private VehicleRepository $vehicleRepo
    ) {}

    //créer un trajet
    public function createTrip(User $user, Trip $trip): void
    {
        //  véhicule appartient bien à l’utilisateur
        $vehicle = $trip->getVehicle();
        if (!$vehicle || $vehicle->getUser()->getId() !== $user->getId()) {
            throw new \Exception('Véhicule invalide ou non autorisé.');
        }

        //  dates logiques
        if (!$this->validateDates($trip)) {
            throw new \Exception('Les dates du trajet ne sont pas valides.');
        }
        

        //calcul durée
        $duration = $this->calculateDuration($trip);
        $trip->setDuration($duration);

        $isEcologique= $this->isEcological($trip);
        $trip->setIsEcological($isEcologique);

        // Liaison User 
        $trip->setDriver($user);

        //check des places
        $this->checkSeatsVehicle( $trip);
        // Création
        $this->em->persist($trip);
        $this->em->flush();
    }

    //date logique
    public function validateDates(Trip $trip): bool
    {
        $departure = $trip->getDepartureDateTime();
        $arrival = $trip->getArrivalDatetime();

        return $departure instanceof \DateTimeInterface &&
                $arrival instanceof \DateTimeInterface &&
                $departure < $arrival;
    }

    public function calculateDuration(Trip $trip):int {
        $departure = $trip->getDepartureDateTime();
        $arrival = $trip->getArrivalDatetime();

        if(!$this->validateDates($trip)){
            throw new \InvalidArgumentException("les dates de départ et d'arrivé ne sont pas valides.");
        }

        $interval=$departure->diff($arrival);
        $minutes = ($interval->days*24*60) + ($interval->h * 60)+ $interval->i;

        if($minutes<1){
            throw new \InvalidArgumentException("La durée du trajet ne peu pas être inférieur à 1 minute.");
        }

        return $minutes;

    }
    //vérification trajet écologique
    public function isEcological(Trip $trip):bool
    {
        $vehicule= $trip->getVehicle();
        $energy = $vehicule ?-> getEnergyType();

        return $energy === EnergyType::ELECTRIQUE;
    }

    //vérif siege vehicule !> place trajet 
    public function checkSeatsVehicle(Trip $trip):void{
        $seatsTrip= $trip ->getSeatsAvailable();
        $vehicule = $trip ->getVehicle();
        $seatsVehicule = $vehicule ->getSeatsTotal();

        if($seatsTrip > $seatsVehicule){
            throw new \InvalidArgumentException("Le nombre de places dépasse la capacité du vehicule.");
        }
    }
}
