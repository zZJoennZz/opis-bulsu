<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemCategoryGroup;

class ItemGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ItemCategoryGroup::create([
            'title' => 'TEST GROUP',
            'report_sub_total_footer' => 'TOTAL TEST GROUP',
            'order' => 1,
            'under_of_section' => 1
        ]);
    }
}
