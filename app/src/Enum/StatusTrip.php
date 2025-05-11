<?php

namespace App\Enum;

enum StatusTrip : string
{
    case SCHEDULED = "scheduled";
    case STARTED = "started";
    case FINISHED = "finished";
    case CANCELLED = "cancelled";

        public function label(): string
    {
        return match($this) {
            self::SCHEDULED => 'Prévu',
            self::STARTED => 'En cours',
            self::FINISHED => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }
}
