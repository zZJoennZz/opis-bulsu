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
            'value' => 'Assoc. Prof. JOSEPH ROY F. CELESTINO',
        ]);
        Setting::create([
            'name' => 'university_president',
            'value' => 'CECILIA N. GASCON, Ph. D.',
        ]);
    }
}
