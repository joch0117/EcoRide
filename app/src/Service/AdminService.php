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


    public function suspendUser()
    {

    }
    public function autoriseUser()
    {

    }

    public function searchUser()
    {

    }
    public function deleteUser()
    {

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