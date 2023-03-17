<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EquipmentCode;

class EquipmentCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ec = [
            [
                "code" => "1-06-05-070",
                "description" => "COMMUNICATION EQUIPMENT"
            ],
            [
                "code" => "1-06-05-110",
                "description" => "MEDICAL EQUIPMENT"
            ],
            [
                "code" => "1-06-05-010",
                "description" => "MACHINERY"
            ],
            [
                "code" => "1-06-05-120",
                "description" => "PRINTING EQUIPMENT"
            ],
            [
                "code" => "1-06-05-030",
                "description" => "ICT-EQUIPMENT"
            ],
            [
                "code" => "1-06-05-130",
                "description" => "SPORTS EQUIPMENT"
            ],
            [
                "code" => "1-06-06-010",
                "description" => "MOTOR VEHICLES"
            ],
            [
                "code" => "1-06-99-990",
                "description" => "OTHER PROPERTY, PLANT & EQUIPMENT"
            ],
            [
                "code" => "1-06-05-100",
                "description" => "MILITARY, POLICE & SECURITY EQUIPMENT"
            ],
            [
                "code" => "1-06-05-990",
                "description" => "OTHER MACHINERY & EQUIPMENT"
            ],
            [
                "code" => "1-06-05-020",
                "description" => "OFFICE EQUIPMENT"
            ],
            [
                "code" => "1-08-01-020",
                "description" => "COMPUTER SOFTWARE"
            ],
            [
                "code" => "1-06-05-140",
                "description" => "TECHNICAL & SCIENTIFIC EQUIPMENT"
            ],
            [
                "code" => "1-06-07-010",
                "description" => "FURNITURES & FIXTURES"
            ],
        ];

        foreach ($ec as $e) {
            EquipmentCode::create([
                'equipment_code' => $e['code'],
                'description' => $e['description'],
                'added_by' => 1
            ]);
        }
    }
}
