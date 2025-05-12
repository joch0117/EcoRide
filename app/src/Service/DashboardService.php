<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\VehicleRepository;

class DashboardService
{
    public function __construct(
        private VehicleRepository $vehicleRepo
    ){}

    public function getDashboardData(User $user): array
    {
        $vehicles=$this->vehicleRepo->findby(['user'=>$user]);

        return[
            'vehicles'=>$vehicles,
        ];
    }
}