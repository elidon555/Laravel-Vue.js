<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subscription;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'creator_id' => User::inRandomOrder()->first()->id,
            'name' => $this->faker->unique()->word,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->sentence,
            'is_active' => $this->faker->boolean,
        ];
    }
}
