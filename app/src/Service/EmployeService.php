<?php

namespace App\Service;

use App\Entity\IncidentReport;
use App\Entity\Review;
use App\Enum\StateChecked;
use App\Enum\StatusReview;
use App\Repository\IncidentReportRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;

class EmployeService
{
    public function __construct(
        private ReviewRepository $reviewRepo,
        private IncidentReportRepository $incidentRepo,
        private EntityManagerInterface $em,
        private CreditService $creditService 
    ) {}
    

    //compte le nombre de reviews au status pending
    public function getPendingReviewsCount(): int
    {
    return $this->reviewRepo->count(['status' => 'pending']);
    }

    //compte le nombre d'incidents non vérifié
    public function getNocheckedIncidentsCount(): int
    {
    return $this->incidentRepo->count(['incident_status' => 'nochecked']);
    }

    //récupére les avis au statu pending
    public function getPendingReviews(): array
    {
        return $this->reviewRepo->findBy(['status'=>'pending']);
    }

    //récupére les incident nochecked
    public function getNoCheckedIncident():array
    {
        return $this->incidentRepo->findby(['incident_status'=>'nochecked']);
    }
    //change le status d'un incident en checked
    public function CheckedIncident(IncidentReport $incident):void
    {
        $incident->setIncidentStatus(StateChecked::CHECKED);

        $this->em->flush();
    }

    //change le status d'un avis
    public function updateReviewStatus(Review $review, StatusReview $status): void
    {

    $review->setStatus($status);

    $this->em->flush();
    }
}