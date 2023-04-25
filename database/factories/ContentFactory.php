<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Content;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition()
    {
        $fileType = ['video', 'photo']; // You can add more file types if you want
        $randomFileType = $this->faker->randomElement($fileType);
        $filePath = Storage::disk('public')->putFile('contents', $this->faker->image(), 'public');

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'type' => $randomFileType,
            'file_path' => $filePath,
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
