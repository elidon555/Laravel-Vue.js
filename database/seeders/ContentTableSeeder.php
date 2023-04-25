<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Seeder;

class ContentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all users
        $users = \App\Models\User::all();

        // Create 5 photo and 5 video contents for each user
        foreach ($users as $user) {
            \App\Models\Content::factory()->count(5)->create([
                'user_id' => $user->id,
                'type' => 'photo',
            ]);

            \App\Models\Content::factory()->count(5)->create([
                'user_id' => $user->id,
                'type' => 'video',
            ]);
        }
    }
}







