<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
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

    public function searchusers(string $term): array
    {
        $users = $this->em->getRepository(User::class)->findAll();

        $filtered=array_filter($users, function(User $user) use ($term){
            return str_contains(strtolower($user->getEmail()),strtolower($term)) ||
                    str_contains(strtolower($user->getUsername()),strtolower($term));
        });

        $employeed = [];
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
    public function deleteUser(User $user): bool
    {
        $this->em->remove($user);
        $this->em->flush();
        return true;
    }
    public function createEmploye(string $plainPassword,User $user)
    {
        $hashedPassword= $this->hasher->hashPassword($user,$plainPassword);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_EMPLOYEE']);
        $user->setIsSuspended(false);
        
        $this->em->persist($user);
        $this->em->flush();

    }

}