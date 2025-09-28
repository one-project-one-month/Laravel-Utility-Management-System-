<?php
namespace App\Enums;

enum PaymentMethod: string {
    case Cash = 'Cash';
    case MobileBanking = 'Mobile Banking';

}