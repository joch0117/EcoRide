<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Preference;
use Doctrine\ORM\EntityManagerInterface;

class PreferenceService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    //créer les préférences par defaut
    public function createUserWithDefaultPreferences(User $user): void
    {
        // Préférences par défaut
        $defaultPreferences = [
            ['label' => 'Fumeur', 'value' => false],
            ['label' => 'Animal', 'value' => false],
        ];

        foreach ($defaultPreferences as $item) {
            $preference = new Preference();
            $preference->setLabel($item['label']);
            $preference->setValue($item['value']);
            $preference->setUser($user);
            $this->em->persist($preference);
        }
    }
}
