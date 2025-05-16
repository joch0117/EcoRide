<?php

namespace App\Controller;

use App\Entity\IncidentReport;
use App\Entity\Review;
use App\Enum\StatusReview;
use App\Service\EmployeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYE')]
#[Route('/employe')]
final class EmployeController extends AbstractController
{
    #[Route('', name: 'employe_dashboard')]
    public function dashboard(EmployeService $employeService): Response
    {
        $pendingReviews = $employeService->getPendingReviewsCount();
        $pendingIncidents =$employeService->getNocheckedIncidentsCount();

        return $this->render('employe/dashboard.html.twig',[
            'pending_reviews'=> $pendingReviews,
            'pending_incidents'=> $pendingIncidents,
        ]);
    }

    #[Route('/avis', name: 'employe_avis')]
    public function avis(EmployeService $employeService): Response
    {
        $pendingReviews = $employeService->getPendingReviews();

        return $this->render('employe/avis.html.twig',[
            'pending_reviews' =>$pendingReviews,
        ]);
    }


    #[Route('/avis/{id}/valider', name: 'employe_avis_valider', methods: ['POST'])]
    public function validerAvis(Request $request , Review $review, EmployeService $service): Response
    {

        if(!$this->isCsrfTokenValid('validate-review-' . $review->getId(),$request->request->get('_token'))){
            throw $this->createAccessDeniedException('Accés refusé ');
        }


        $service->updateReviewStatus($review, StatusReview::APPROVED);
        return $this->redirectToRoute('employe_avis');
    }

    #[Route('/avis/{id}/rejeter', name: 'employe_avis_rejeter', methods: ['POST'])]
    public function rejeterAvis(Request $request ,Review $review, EmployeService $service): Response
    {

        if(!$this->isCsrfTokenValid('reject-review-' . $review->getId(),$request->request->get('_token'))){
            throw $this->createAccessDeniedException('Accés refusé ');
        }

        $service->updateReviewStatus($review, StatusReview::REJECTED);

        return $this->redirectToRoute('employe_avis');
    }

    #[Route('/incidents', name: 'employe_incidents')]
    public function incidents(EmployeService $employeService): Response
    {

        $incidents=$employeService->getNoCheckedIncident();

        return $this->render('employe/incidents.html.twig',[
            'incidents' => $incidents,
        ]);
    }

    #[Route('/incidents/{id}', name: 'employe_incident_detail')]
    public function incidentDetail(IncidentReport $incidents): Response
    {
        return $this->render('employe/incident_show.html.twig', [
            'incidents' => $incidents,
        ]);
    }


    #[Route('/incidents/{id}/checked', name: 'employe_incident_checked',methods:['POST'])]
    public function checkIncident(EmployeService $service , IncidentReport $incidents,Request $request): Response
    {

        if(!$this->isCsrfTokenValid('checked-incident' . $incidents->getId(),$request->request->get('_token'))){
            throw $this->createAccessDeniedException('Accés refusé ');
        }
        $service->CheckedIncident($incidents);

        return $this->redirectToRoute('employe_incidents');
    }

}
