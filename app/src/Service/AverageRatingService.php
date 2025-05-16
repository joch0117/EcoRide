<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\StatusReview;

class AverageRatingService

{
    public function getAverageRating(User $driver ): ?float
    {
    $sum = 0;
    $count = 0;

    foreach ($driver->getDriverReviews() as $review) {
        if ($review->getStatus() === StatusReview::APPROVED && $review->getRating() !== null) {
            $sum += $review->getRating();
            $count++;
        }
    }
    
    $note= $count > 0 ? (int) floor($sum / $count) : null;
    
    return $note;
    }
}