<?php

namespace App\Enum;



enum EnergyType: string
{
    case ESSENCE = 'essence';
    case DIESEL = 'diesel';
    case ELECTRIQUE = 'electrique';
    case HYBRIDE = 'hybride';
    case GPL = 'gpl';


public function label():string{
    return $this->value;
}

}