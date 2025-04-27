<?php

namespace App\Enum;



enum StatusFeedback: string
{
    case PENDING= 'pending';
    case VALIDATED='validated';
    case REJECTED='rejected';
}