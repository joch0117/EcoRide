<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Preference;
use Doctrine\ORM\EntityManagerInterface;

class PreferenceService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

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
