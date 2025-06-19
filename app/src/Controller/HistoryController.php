<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Trip;
use App\Repository\BookingRepository;
use App\Repository\ReviewRepository;
use App\Repository\TripRepository;
use App\Service\BookingService;
use App\Service\HistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class HistoryController extends AbstractController
{
    //affichage des trip en tant que passagers et en tant que chauffeur
    #[Route('/history', name: 'app_history')]
    public function index(TripRepository $tripRepository,BookingRepository $bookingRepository,ReviewRepository $reviewRepository): Response
    {
        $user =$this->getUser();
        $driverTrips = $tripRepository->findBy(['driver'=>$user]);


        $passengerBookings =$bookingRepository->findBy(['user'=>$user]);
        $passengerTrips = array_map(fn($booking)=>$booking->getTrip(),$passengerBookings);

        $reviewedTripIds = [];

        foreach ($passengerBookings as $booking) {
            if ($reviewRepository->hasReview($user, $booking->getTrip())) {
                    $reviewedTripIds[] = $booking->getTrip()->getId();
                }
            }


        return $this->render('history/history.html.twig',[
            'chauffeurTrips' =>$driverTrips,
            'passengerTrips'=>$passengerTrips,
            'passengerBookings'=>$passengerBookings,
            'user'=>$this->getUser(),
            'reviewedTripIds' => $reviewedTripIds,
        ]);
    }

    //démarre un trajet
    #[Route('/history/start/{id}', name: 'app_trip_start')]
    public function startTrip(Trip $trip, HistoryService $historyService): Response
    {
        $historyService->startTrip($trip);
        $this->addFlash('success', 'Trajet démarré.');
        return $this->redirectToRoute('app_history');
    }

    //annul un trajet
    #[Route('/history/cancel/{id}', name: 'app_trip_cancel')]
    public function cancelTrip(Trip $trip, HistoryService $historyService): Response
    {
        $historyService->cancelTrip($trip);
        $this->addFlash('warning', 'Trajet annulé.');
        return $this->redirectToRoute('app_history');
    }

    //termine un trajet
    #[Route('/history/finish/{id}', name: 'app_trip_finish')]
    public function arriveTrip(Trip $trip, HistoryService $historyService): Response
    {
        $historyService->arrivalTrip($trip);
        $this->addFlash('success', 'Trajet terminé.');
        return $this->redirectToRoute('app_history');
    }

    //annuler une réservation
    #[Route('/history/cancel-booking/{id}', name: 'app_booking_cancel')]
    public function cancelBooking(Booking $booking, BookingService $bookingService): Response
{
    if ($bookingService->cancelBooking($booking, $this->getUser())) {
        $this->addFlash('success', 'Réservation annulée et remboursée.');
    } else {
        $this->addFlash('danger', 'Action non autorisée.');
    }

    return $this->redirectToRoute('app_history');
}
}

