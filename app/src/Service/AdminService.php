<?php

namespace App\Service;

use App\Document\SiteStat;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\CreditTransactionRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private BookingRepository $bookingRepository,
        private CreditTransactionRepository $creditTransactionRepository,
        private DocumentManager $dm,
        private TripRepository $tripRepository,
        private CreditTransactionRepository $creditTransaction,
        private UserRepository $userRepository
    ){}


    public function toggleSuspension(User $user)
    {
        $user->setIsSuspended(!$user->isSuspended());
        $this->em->flush();
    }

    public function getUserGrouped()
    {
        $allUsers=$this->em->getRepository(User::class)->findAll();
        $employees=[];
        $users =[];

        foreach($allUsers as $user){
            if(in_array('ROLE_EMPLOYEE',$user->getRoles())){
                $employees[] = $user;
            } elseif (in_array('ROLE_USER', $user->getRoles())){
                $users[]=$user;
            }
        }
        return[
            'employees'=>$employees,
            'users'=>$users
        ];
    }

    public function searchUsers(string $term): array
    {
        $users = $this->em->getRepository(User::class)->findAll();

        $filtered=array_filter($users, function(User $user) use ($term){
            return str_contains(strtolower($user->getEmail()),strtolower($term)) ||
                    str_contains(strtolower($user->getUsername()),strtolower($term));
        });

        $employed = [];
        $regularUsers = [];

        foreach ($filtered as $user){
            if(in_array('ROLE_EMPLOYEE', $user->getRoles())){
                $employees[] = $user;
            }elseif (in_array('ROLE_USER',$user->getRoles())){
                $regularUsers[] = $user;
            }
        }
            return ['employees' => $employees,'users'=>$regularUsers];
    }
    //futur fonctionnalité supression de compte
    //public function deleteUser(User $user): bool 
    //{
        //$this->em->remove($user);
        //$this->em->flush();
        //return true;
    //}
    public function createEmploye(string $plainPassword,User $user)
    {
        $hashedPassword= $this->hasher->hashPassword($user,$plainPassword);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_EMPLOYEE']);
        $user->setIsSuspended(false);
        
        $this->em->persist($user);
        $this->em->flush();

    }


    //stat

    public function getTrajetsRealisesParJour(): array
    {
    $rows = $this->bookingRepository->countRealizedByDay();

    $labels = [];
    $values = [];

    foreach ($rows as $row) {
        $labels[] = $row['jour'];
        $values[] = (int) $row['total'];
    }

    return [
        'labels' => $labels,
        'values' => $values
    ];
    }

    public function getCreditsGagnesParJour(): array
    {
    $rows = $this->creditTransactionRepository->creditsByDay();

    $labels = [];
    $values = [];

    foreach ($rows as $row) {
        $labels[] = $row['jour'];
        $values[] = (int) $row['total'];
    }

    return [
        'labels' => $labels,
        'values' => $values
    ];
    }


/*
    public function dataPlateform(){
        $nbTrajets = $this->tripRepository->count([]);
        $totalCredits = $this->creditTransactionRepository->sumPlatformWin();
        $nbUsers = $this->userRepository->count([]);

        return[
            'nbTrajets'=>$nbTrajets,
            'creditsGagnes'=>$totalCredits,
            'nbUtilisateurs'=>$nbUsers,
        ];
    }
        */

    ///fonction mongodb
    public function getLastSnapshot(): ?SiteStat
    {
        return $this->dm->getRepository(SiteStat::class)
            ->createQueryBuilder()
            ->sort('date', 'desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult();
    }
}