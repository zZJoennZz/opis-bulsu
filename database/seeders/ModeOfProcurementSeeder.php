<?php

namespace Database\Seeders;

use App\Models\ModeOfProcurement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModeOfProcurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $mop = [
            'Small Value',
            'Competitive Bidding',
            'Direct Contracting',
            'Shopping',
            'Agency-to-Agency',
        ];

        foreach ($mop as $m) {
            ModeOfProcurement::create([
                'name' => $m ,
                'is_delete' => 0,
                'added_by' => 1,
            ]);
        }
    }
}
