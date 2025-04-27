<?php

namespace App\Enum;

enum StatusReview:string
{
    case PENDING='pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}