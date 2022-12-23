<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Branch::create([
            'branch_name' => 'Campus 1',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
        ]);
        Branch::create([
            'branch_name' => 'Campus 2',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
        ]);
        Branch::create([
            'branch_name' => 'Campus 3',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
        ]);

        Branch::create([
            'branch_name' => 'Budget Office',
            'type' => 'OFFICE',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
        ]);

        Branch::create([
            'branch_name' => 'Procurement Office',
            'type' => 'OFFICE',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
        ]);
    }
}
