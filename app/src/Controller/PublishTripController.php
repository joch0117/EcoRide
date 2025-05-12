<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\PublishType;
use App\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class PublishTripController extends AbstractController
{
    #[Route('/publier', name: 'app_publish')]
    public function publish(Request $request, TripService $tripService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user->isDriver()) {
            $this->addFlash('warning', 'Vous devez être chauffeur pour publier un trajet.');
            return $this->redirectToRoute('app_dashboard');
        }

        $trip = new Trip();
        $form = $this->createForm(PublishType::class, $trip,['user'=>$this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $tripService->createTrip($user, $trip);
                $this->addFlash('success', 'Trajet publié avec succès.');
                return $this->redirectToRoute('app_dashboard');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('publish/publish.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

