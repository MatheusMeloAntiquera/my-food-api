<?php

namespace App\Enums;

enum StatusOrder: string
{
    case PENDING = 'P';
    case CONFIRMED = 'C';
    case FINISHED = 'F';
}
