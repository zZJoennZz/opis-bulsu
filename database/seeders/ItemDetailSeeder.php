<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemDetail;

class ItemDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $articleForTest = "For testing";
        ItemDetail::create([
            'description' => 'Pencil',
            'article' => $articleForTest,
            'price_catalogue' => 50,
            'category_id' => 1,
            'unit_id' => 1,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => 'Ballpen',
            'article' => $articleForTest,
            'price_catalogue' => 50,
            'category_id' => 1,
            'unit_id' => 1,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => 'Plastic Folder',
            'article' => $articleForTest,
            'price_catalogue' => 50,
            'category_id' => 1,
            'unit_id' => 1,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => 'A4 Paper',
            'article' => $articleForTest,
            'price_catalogue' => 50,
            'category_id' => 1,
            'unit_id' => 1,
            'added_by' => 1,
            'is_approve' => 1
        ]);
    }
}
