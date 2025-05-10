<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehiculeType;
use App\Services\VehicleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\FormError;

#[IsGranted('ROLE_USER')]
final class VehicleController extends AbstractController
{
    #[Route('/vehicule/ajouter', name: 'app_vehicle')]
    public function add( Request $request,VehicleService $vehicleService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this ->getUser();

        if(!$user->isDriver()){
            throw $this->createAccessDeniedException("Seul les chauffeurs peuvent ajouter un vehicule");
        }

        $vehicle = new Vehicle();
        $vehicle->setUser($user);

        $form=$this->createForm(VehiculeType::class , $vehicle);
        $form->handleRequest($request);
        
        $redirectUrl = $request->query->get('from') === 'publish'
                        ? $this->generateUrl('app_publish')
                        : $this->generateUrl('app_dashboard');

        if($form->isSubmitted()&& $form->isValid()){
            if($vehicleService->isPlateAlreadyUsed($vehicle->getPlate(),$user)){
                $form->get('plate')->addError(new FormError('Un vehicule avec cette plaque existe déjà.'));
            }
            if(count($form->getErrors(true))===0){
                $vehicleService->createVehicle($user,$vehicle);
                $this->addFlash('success','Véhicule ajouté avec succès.');
                return $this->redirect($redirectUrl);
            }
        }

        return $this->render('vehicle/vehicule.html.twig',[
        'form'=>$form->createView(),
    ]);
    }
}
