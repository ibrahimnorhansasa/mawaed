<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MedicalCenter::factory(10)->create();


    }
}
