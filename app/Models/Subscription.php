<?php

namespace App\Models;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    public function subscribedUser() {
        return $this->hasOne(User::class,'id','name');
    }
    public function plan() {
        return $this->hasOne(SubscriptionPlan::class,'price_id','stripe_price');
    }
}