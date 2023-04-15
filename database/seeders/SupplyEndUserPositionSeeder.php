<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplyEndUserPositions;

class SupplyEndUserPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $supply_end_user_positions = [
            'INSTRUCTOR I',
            'INSTRUCTOR II',
            'INSTRUCTOR III',
            'UNIVERSITY PROFESSOR',
            'PROFESSOR I',
            'PROFESSOR II',
            'PROFESSOR III',
            'PROFESSOR IV',
            'PROFESSOR V',
            'PROFESSOR VI',
            'ASSISTANT PROFESSOR I',
            'ASSISTANT PROFESSOR II',
            'ASSISTANT PROFESSOR III',
            'ASSISTANT PROFESSOR IV',
            'ASSISTANT PROFESSOR V',
            'ASSOCIATE PROFESSOR I',
            'ASSOCIATE PROFESSOR II',
            'ASSOCIATE PROFESSOR III',
            'ASSOCIATE PROFESSOR IV',
            'ASSOCIATE PROFESSOR V',
            'ASSOCIATE PROFESSOR VI',
        ];

        foreach ($supply_end_user_positions as $position) {
            SupplyEndUserPositions::create([
                'position_name' => $position,
                'added_by' => 1
            ]);
        }
    }
}
