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

    return $count > 0 ? round($sum / $count, 2) : null;
    }
}