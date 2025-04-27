<?php

namespace App\Enum;



enum CreditTransactionType: string
{
    case CREDIT= 'credit';//ajout par résa 
    case DEBIT='debit';//retrait pour résa
    case PLATFORM_FEE='plateform_fee';//"gain de la plateforme prélévement + 2 credit par résa"
    case BONUS='bonus';//gain "parainage ect"
    case REFUND='refund';//remboursement trajet

}