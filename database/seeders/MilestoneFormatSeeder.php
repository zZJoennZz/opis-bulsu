<?php

namespace Database\Seeders;

use App\Models\MilestoneFormat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MilestoneFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        MilestoneFormat::create([
            'id' => 1,
            'format_name' => 'monthly',
            'format' => '[{"id":"jan","name":"Jan"},{"id":"feb","name":"Feb"},{"id":"mar","name":"Mar"},{"id":"apr","name":"Apr"},{"id":"may","name":"May"},{"id":"jun","name":"Jun"},{"id":"jul","name":"Jul"},{"id":"aug","name":"Aug"},{"id":"sep","name":"Sept"},{"id":"oct","name":"Oct"},{"id":"nov","name":"Nov"},{"id":"dec","name":"Dec"}]',
        ]);
    }
}
