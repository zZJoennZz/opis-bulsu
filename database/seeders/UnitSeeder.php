<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $uom = array("kg", "m", "cm", "L", "pc", "box", "case", "pallet", "gal", "oz", "pack", "asd", "sd", "sss", "dddd", "ddxzc");

        foreach ($uom as $u) {
            Unit::create(['uom' => $u, 'is_delete' => 0, 'added_by' => 1]);
        }
    }
}
