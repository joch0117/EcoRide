<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\PublishType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublishController extends AbstractController
{
    #[Route('/publier-trajet', name: 'app_publish')]
    public function publish(Request $request , EntityManagerInterface $em): Response
    {
        //récupération du trajet vehicule et user
        $user = $this->getUser();
        $trip = new Trip();
        $form = $this->createForm(PublishType::class,$trip,['user'=>$user]);
        $form->handleRequest($request);

        //vérification utilisateur
        if(!$user){
            $this->addFlash('error','Vous  devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        
        //vérification et validation du formulaire + récupération valeur date arrivé et départ
        if ($form->isSubmitted()) {
            $departure = $trip->getDepartureDatetime();
            $arrival = $trip->getArrivalDatetime();
        
            //vérification du nombre de place cohérence vehicule  et trip
            $vehicle =$trip->getVehicle();
            $placeDispo = $trip->getSeatsAvailable();
            if ($vehicle && $placeDispo > $vehicle->getSeatsTotal()){
                $form->get('seats_available')->addError(new FormError(
                    'Le nombre de places disponibles dépasses la capacité du vehicule ('.$vehicle->getSeatsTotal().'places).'
                ));
            }
            if (!$departure || !$arrival) {
                $form->addError(new FormError("Vous devez renseigner les dates de départ et d'arrivée."));
            } elseif ($arrival <= $departure) {
                $form->addError(new FormError("La date d'arrivée doit être postérieure à la date de départ."));
            } else {
                $diff = $departure->diff($arrival);
                $duration = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                $trip->setDuration($duration);
            }
            
            
            if ($form->isValid()) {
                // Déterminer si écologique
                $vehicle = $trip->getVehicle();
                $trip->setIsEcological(
                    $vehicle && $vehicle->getEnergyType()?->value === 'electrique'
                );
        
                $trip->setDriver($this->getUser());
        
                $em->persist($trip);
                $em->flush();
        
                $this->addFlash('success', 'Trajet publié avec succès !');
                return $this->redirectToRoute('app_dashboard');
            }
        }
        

            //retour de la vue et du formulaire
        return $this->render('publish/publish.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
}
