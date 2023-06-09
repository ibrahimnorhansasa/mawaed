<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->unique()->word,
            'image'       =>$this->faker->imageUrl(640,480, null, false),
            'description' => $this->faker->text,
            'center_id'   => MedicalCenter::all()->random()->id ,
            'created_at'  => now()->toDateTimeString(),
            'updated_at'  => now()->toDateTimeString(),
        ];
    }
}
