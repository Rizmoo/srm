<?php

namespace App\Providers;

enum ReorderStatus: string
{
    case Pending = 'Pending';
    case Dispatched = 'Dispatched';
}
