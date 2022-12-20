<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemCategoryGroupSection;

class ItemGroupSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ItemCategoryGroupSection::create([
            'title' => 'TEST SECTION',
            'report_sub_total_footer' => 'TOTAL TEST SECTION',
            'order' => 1
        ]);
    }
}
