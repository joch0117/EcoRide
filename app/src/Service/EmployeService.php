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
    

    public function getPendingReviewsCount(): int
    {
    return $this->reviewRepo->count(['status' => 'pending']);
    }

    public function getPendingIncidentsCount(): int
    {
    return $this->incidentRepo->count(['incident_status' => 'pending']);
    }

    public function getPendingReviews(): array
    {
        return $this->reviewRepo->findBy(['status'=>'pending']);
    }

    public function getNoCheckedIncident():array
    {
        return $this->incidentRepo->findby(['incident_status'=>'nochecked']);
    }
    public function CheckedIncident(IncidentReport $incident):void
    {
        $incident->setIncidentStatus(StateChecked::CHECKED);

        $this->em->flush();
    }

    public function updateReviewStatus(Review $review, StatusReview $status): void
    {

    $review->setStatus($status);

    $this->em->flush();
    }
}