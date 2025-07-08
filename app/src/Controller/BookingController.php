<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TripRepository;
use App\Service\BookingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class BookingController extends AbstractController
{

    public function __construct(
        private BookingService $bookingService,
        private EntityManagerInterface $em,
    ){}

    #[IsGranted('ROLE_USER')]
    #[Route('/booking/{id}/confirmer', name: 'booking_confirm')]
    public function confirm(int $id, TripRepository $tripRepo,Request $request): Response
    {

        $trip=$tripRepo->find($id);

        if(!$trip){
            throw $this->createNotFoundException('Trajet introuvable');
        }
        return $this->render('booking/index.html.twig', [
            'trip' => $trip,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/booking/{id}/participer', name: 'booking_create', methods: ['POST'])]
    public function create(int $id, TripRepository $tripRepo, Request $request): Response
    {

        $trip = $tripRepo->find($id);

        if (!$trip) {
            throw $this->createNotFoundException('Trajet introuvable');
        }

        // Vérif CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('participate' . $trip->getId(), $token)) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        // Vérif user
        $user = $this->getUser();
        if (!$user || !$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour réserver');
        }

        
        // Vérif des crédits via service
        if ($error = $this->bookingService->canUserBook($user, $trip)) {
            $this->addFlash('danger', $error);
            return $this->redirectToRoute('app_detail', ['id' => $trip->getId()]);
        }

        try{
        // Création réservation
        $booking = $this->bookingService->createBooking($user, $trip);
    
        $this->em->flush();
        $this->addFlash('success','Réservation enregistrée avec succès.');

        }catch(\DomainException $e){
            $this->addFlash('danger',$e->getMessage());
        }catch(\Throwable $e){
            $this->addFlash('danger','Erreur lors de la réservation : ' . $e->getMessage());
        }
    
        
        return $this->redirectToRoute('app_detail', ['id' => $trip->getId()]);
    }

}
