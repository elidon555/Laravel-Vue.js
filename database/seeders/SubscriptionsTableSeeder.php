<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionsTableSeeder extends Seeder
{
    public function run()
    {
        Subscription::factory()->count(10)->create();
    }
}

