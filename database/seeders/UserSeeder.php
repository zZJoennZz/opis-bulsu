<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'account_type' => 'admin',
            'password' => bcrypt('admin'),
            'ppmp_year' => '2022',
            'branches_id' => 5,
            'is_active' => 1,
        ]);

        User::create([
            'username' => 'enduser1',
            'email' => 'enduser1@admin.com',
            'account_type' => 'admin',
            'password' => bcrypt('admin'),
            'ppmp_year' => '2022',
            'branches_id' => 1,
            'is_active' => 1,
        ]);
        User::create([
            'username' => 'enduser2',
            'email' => 'enduser2@admin.com',
            'account_type' => 'admin',
            'password' => bcrypt('admin'),
            'ppmp_year' => '2022',
            'branches_id' => 2,
            'is_active' => 1,
        ]);
        User::create([
            'username' => 'enduser3',
            'email' => 'enduser3@admin.com',
            'account_type' => 'admin',
            'password' => bcrypt('admin'),
            'ppmp_year' => '2022',
            'branches_id' => 3,
            'is_active' => 1,
        ]);

        User::create([
            'username' => 'bouser',
            'email' => 'bouser@admin.com',
            'account_type' => 'BUDGET_OFFICE',
            'password' => bcrypt('admin'),
            'ppmp_year' => '2022',
            'branches_id' => 4,
            'is_active' => 1,
        ]);

        User::create([
            'username' => 'procure',
            'email' => 'procure@admin.com',
            'account_type' => 'PROCUREMENT_OFFICE',
            'password' => bcrypt('admin'),
            'ppmp_year' => '2022',
            'branches_id' => 5,
            'is_active' => 1,
        ]);
    }
}
