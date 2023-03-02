<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrderModeOfPayment;

class PurchaseOrderModeOfPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PurchaseOrderModeOfPayment::create([
            'name' => 'Check and Carry',
        ]);
        PurchaseOrderModeOfPayment::create([
            'name' => 'Government Terms',
        ]);
    }
}
