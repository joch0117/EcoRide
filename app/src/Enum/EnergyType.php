<?php

namespace App\Enum;



enum EnergyType: string
{
    case ELECTRIC= 'electric';
    case HYBRID='hybrid';
    case DIESEL='diesel';
    case PETROL='petrol';
}