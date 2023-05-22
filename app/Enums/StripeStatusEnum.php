<?php

namespace App\Enums;

enum StripeStatusEnum:string {
    case Active = 'active';
    case Canceled = 'canceled';
}