<?php

namespace App\Enum;



enum EnergyType: string
{
    case ESSENCE = 'essence';
    case DIESEL = 'diesel';
    case ELECTRIQUE = 'electrique';
    case HYBRIDE = 'hybride';
    case GPL = 'gpl';


public function label(): string
{
    return match($this) {
        self::ELECTRIQUE => 'Électrique',
        self::HYBRIDE => 'Hybride',
        self::ESSENCE => 'Essence',
        self::DIESEL => 'Diesel',
        self::GPL => 'GPL',
    };
}

}