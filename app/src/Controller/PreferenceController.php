<?php

namespace App\Controller;

use App\Entity\Preference;
use App\Repository\PreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/preferences')]
final class PreferenceController extends AbstractController
{
    //récupérer les données via GET 
    #[Route('',methods:['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]

    public function list(PreferenceRepository $repo): JsonResponse
    {
        $user=$this->getUser();
        $prefs=$repo->findBy(['user'=>$user]);

        $data=array_map(fn(Preference $p)=>[
            'id' => $p->getId(),
            'label' => $p->getLabel(),
            'value' => $p->isValue(),
        ],$prefs);

        return $this->json($data);
    }

    //enregistrer via POST
    #[Route('', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $label=isset($data['label']) ? strip_tags(trim($data['label'])):'';
        $value=$data['value']?? null;

        if (
            strlen($label)<2|| !is_bool($value)
        ) {
            return $this->json(['error' => 'Préférence invalide'], 400);
        }

        $preference = new Preference();
        $preference->setLabel($label);
        $preference->setValue($value);
        $preference->setUser($user);

        $em->persist($preference);
        $em->flush();

        return $this->json([
            'id' => $preference->getId(),
            'label' => $preference->getLabel(),
            'value' => $preference->isValue(),
        ]);
    }

    //modifier via PATCH
    #[Route('/{id}',methods:['PATCH'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function update(Request $request , Preference $preference , EntityManagerInterface $em): JsonResponse
    {
        if($preference->getUser() !==$this->getUser()){
            return $this->json(['error'=>'Accès refusé'],403);
        }
        $data = json_decode($request->getContent(),true);

        if(
            !isset($data['label']) || !is_string($data['label']) || strlen(trim($data['label']))<2||
            !array_key_exists('value', $data) || !is_bool($data['value'])
        ){
            return $this->json(['error'=>'Préférence invalide'], 400);
        }

        $preference->setLabel(trim($data['label']));
        $preference->setValue($data['value']);

        $em->flush();

        return $this->json([
            'id'=>$preference->getId(),
            'label'=>$preference->getLabel(),
            'value'=>$preference->isValue(),
        ]);
    }

    //suprimmer via DELETE
    #[Route('/{id}', methods: ['DELETE'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Preference $preference, EntityManagerInterface $em): JsonResponse
    {
        if ($preference->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }

        $em->remove($preference);
        $em->flush();

        return $this->json(['success' => true]);
    }
}

    
