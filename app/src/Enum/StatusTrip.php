<?php

namespace App\Enum;

enum StatusTrip : string
{
    case SCHEDULED = "scheduled";
    case STARTED = "started";
    case FINISHED = "finished";
    case CANCELLED = "cancelled";
}