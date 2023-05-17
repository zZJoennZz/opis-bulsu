<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Setting::create([
            'name' => 'maintenance_mode',
            'value' => '0',
        ]);
        Setting::create([
            'name' => 'ppmp_due_month',
            'value' => '12',
        ]);
        Setting::create([
            'name' => 'ppmp_due_day',
            'value' => '31',
        ]);
        Setting::create([
            'name' => 'bac_chairman',
            'value' => 'DR. DOLLY P. MAROMA',
        ]);
        Setting::create([
            'name' => 'university_president',
            'value' => 'Prof. CECILIA N. GASCON, Ph. D.',
        ]);
        Setting::create([
            'name' => 'vice_chair_1',
            'value' => 'DR. MARVIN R. TULLAO',
        ]);
        Setting::create([
            'name' => 'vice_chair_2',
            'value' => 'DR. MARVIN R. TULLAO',
        ]);
        Setting::create([
            'name' => 'member_1',
            'value' => 'PROF. JOSEPH ROY CELESTINO',
        ]);
        Setting::create([
            'name' => 'member_2',
            'value' => 'ENGR. DONALD M. LAPIGUERA',
        ]);
        Setting::create([
            'name' => 'member_3',
            'value' => 'ENGR. NEOMI REYES',
        ]);
        Setting::create([
            'name' => 'member_4',
            'value' => 'PROF. YOLANDA ROBERTO',
        ]);
        Setting::create([
            'name' => 'technical_resource_person',
            'value' => 'Default Technical Resources Person',
        ]);
        Setting::create([
            'name' => 'accountants',
            'valuue' => '"[\r\n    {\r\n        \"id\": 1,\r\n        \"full_name\": \"Joanha Christine T. Borja\",\r\n        \"position\": \"Head - System Accounting Office\"\r\n    },  \r\n    {\r\n        \"id\": 2,\r\n        \"full_name\": \"Ma. Carla V Diño\",\r\n        \"position\": \"Director for Finance - Main Campus\"\r\n    },\r\n    {\r\n        \"id\": 3,\r\n        \"full_name\": \"Sheila Marie Domingo\",\r\n        \"position\": \"Director for Finance - External\"\r\n    }\r\n]"'
        ]);
    }
}
