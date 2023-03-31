<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SourceOfFund;

class SourceOfFundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        SourceOfFund::create([
            'source_of_fund' => 'N/A',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        SourceOfFund::create([
            'source_of_fund' => 'GAA',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        SourceOfFund::create([
            'source_of_fund' => 'Income',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        SourceOfFund::create([
            'source_of_fund' => 'Fiduciary Fund',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
    }
}
