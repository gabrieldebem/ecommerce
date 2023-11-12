<?php

namespace App\Enums;

enum ProductStatus: int
{
    case Draft = 0;
    case Available = 1;
    case Unavailable = 2;
}
