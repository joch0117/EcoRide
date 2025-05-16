<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Trip;
use App\Form\FeedBackType;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class FeedbackController extends AbstractController
{
    #[Route('/feedback/{id}', name: 'app_feedback')]
    public function index(Request $request, Trip $trip, ReviewService $reviewService): Response
    {
        $review = new Review();
        $form = $this->createForm(FeedBackType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isValidated = $request->get('is_validated') === '1';

            if (!$isValidated && !$review->getComment()) {
                $form->get('comment')->addError(new FormError(
                    'Un commentaire est obligatoire si vous signalez un problème.'
                ));
            }

            if ($form->isValid()) {
                $user = $this->getUser();

                if ($isValidated) {
                    $reviewService->validateTrip($review, $trip, $user);
                } else {
                    $reviewService->problemSignaled($trip, $review, $user);
                }

                $this->addFlash('success', 'Merci pour votre retour !');
                return $this->redirectToRoute('app_history');
            }
        }

        return $this->render('feedback/feedback.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

