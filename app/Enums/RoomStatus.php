<?php

namespace App\Enums;

enum RoomStatus: string
{
    case Available     = 'Available';
    case Rented        = 'Rented';
    case Purchased     = 'Purchased';
    case InMaintenance = 'In Maintenance';
}
