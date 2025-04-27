<?php

namespace App\Enum;

enum StateBooking: string
{
    case PENDING = "pending";
    case CONFIRMED = "confirmed";
    case CANCELLED = "cancelled";
}