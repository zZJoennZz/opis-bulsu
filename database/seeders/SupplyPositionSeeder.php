<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplyPosition;

class SupplyPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $employee_positions = [
            'ADMINISTRATIVE OFFICER I',
            'ADMINISTRATIVE OFFICER II',
            'ADMINISTRATIVE OFFICER III',
            'ADMINISTRATIVE OFFICER IV',
            'ADMINISTRATIVE ASSISTANT I',
            'ADMINISTRATIVE ASSISTANT II',
            'ADMINISTRATIVE ASSISTANT III',
            'ADMINISTRATIVE ASSISTANT IV',
            'ADMINISTRATIVE AIDE I',
            'ADMINISTRATIVE AIDE II',
            'ADMINISTRATIVE AIDE III',
            'ADMINISTRATIVE AIDE IV',
            'EMPLOYEE BY JOB ORDER',
            'SUPERVISING ADMINISTRATIVE OFFICER',
            'STAFF',
        ];

        foreach ($employee_positions as $position) {
            SupplyPosition::create([
                'type' => 'SUPPLY_OFFICE_EMPLOYEE',
                'name' => $position,
                'added_by' => 1
            ]);
        }

        $end_user_positions = [
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

        foreach ($end_user_positions as $position) {
            SupplyPosition::create([
                'type' => 'END_USER',
                'name' => $position,
                'added_by' => 1
            ]);
        }
    }
}
