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
            'title' => 'MAINTENANCE & OTHER OPERATING EXPENSES',
            'report_sub_total_footer' => 'MOOE',
            'order' => 1
        ]);
        ItemCategoryGroupSection::create([
            'title' => 'CAPITAL OUTLAYS',
            'report_sub_total_footer' => 'CAPITAL OUTLAYS',
            'order' => 2
        ]);
    }
}
