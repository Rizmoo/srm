<?php

namespace App;

enum OrderStatus:string
{
    case Unprocessed = 'Unprocessed';
    case Processed = 'Processed';
}
