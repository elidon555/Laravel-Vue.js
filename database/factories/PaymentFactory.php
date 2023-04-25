<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Content;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'subscriber_id' => Subscription::inRandomOrder()->first()->id,
            'content_id' => Content::inRandomOrder()->first()->id,
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'transaction_id' => $this->faker->uuid,
            'paid_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
