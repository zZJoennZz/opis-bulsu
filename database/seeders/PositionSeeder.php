<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            'description' => 'Admin',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        Position::create([
            'description' => 'Budget Office',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        Position::create([
            'description' => 'Procurement Office',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        Position::create([
            'description' => 'End User',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        Position::create([
            'description' => 'Supplier',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
    }
}
