<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;

class VehicleService
{
    public function __construct(
        private EntityManagerInterface $em,
        private VehicleRepository $vehicleRepo
    ) {}

    //vérification double immatriculation
    public function isPlateAlreadyUsed(string $plate, User $user): bool
    {
        return $this->vehicleRepo->findOneBy(['plate' => $plate, 'user' => $user]) !== null;
    }

    //créer un vehicules
    public function createVehicle(User $user, Vehicle $vehicle): void
    {
        $vehicle->setUser($user);
        $this->em->persist($vehicle);
        $this->em->flush();
    }

}
