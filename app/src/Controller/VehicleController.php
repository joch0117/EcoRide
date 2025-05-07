<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Entity\User;
use App\Form\VehiculeType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function add( Request $request,EntityManagerInterface $em): Response
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
        
        $session=$request->getSession();
        $redirectUrl = $session->get('redirect_after_vehicle',$this->generateUrl('app_dashboard'));
        $session->remove('redirect_after_vehicle');

        if($form->isSubmitted()&& $form->isValid()){
            $existing=$em->getRepository(Vehicle::class)->findOneBy(['plate'=>$vehicle->getPlate()]);
            if($existing){
                $form->addError(new FormError('Un véhicule avec cette plaque existe déjà.'));
            }
            if(count($form->getErrors(true))===0){
                $em->persist($vehicle);
                $em->flush();
            }
            

            $this->addFlash('success','Véhicule ajouté avec succès.');
            return $this->redirect($redirectUrl);
        }

        return $this->render('vehicle/index.html.twig',[
    'form'=>$form->createView(),
    ]);
    }
}
