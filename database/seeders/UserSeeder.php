<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

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
            'email' => 'joenn.shift101@gmail.com',
            'account_type' => 'admin',
            'password' => 'admin',
            'ppmp_year' => '2022',
            'branches_id' => 1,
            'is_active' => 1,
        ]);
        Position::create([
            'description' => 'Web Developer',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        UserProfile::create([
            'users_id' => 1,
            'first_name' => 'Web',
            'last_name' => 'Developer',
            'positions_id' => 1,
        ]);

        User::create([
            'username' => 'enduser1',
            'email' => 'enduser1@admin.com',
            'account_type' => 'END_USER',
            'password' => 'admin',
            'ppmp_year' => '2022',
            'branches_id' => 7,
            'is_active' => 1,
        ]);
        Position::create([
            'description' => 'Professor',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        UserProfile::create([
            'users_id' => 2,
            'first_name' => 'Jaymark',
            'last_name' => 'Fernandez',
            'positions_id' => 2,
        ]);

        User::create([
            'username' => 'enduser2',
            'email' => 'enduser2@admin.com',
            'account_type' => 'END_USER',
            'password' => 'admin',
            'ppmp_year' => '2022',
            'branches_id' => 8,
            'is_active' => 1,
        ]);
        UserProfile::create([
            'users_id' => 3,
            'first_name' => 'Roncy',
            'last_name' => 'Nava',
            'positions_id' => 2,
        ]);

        User::create([
            'username' => 'enduser3',
            'email' => 'joennsa@gmail.com',
            'account_type' => 'END_USER',
            'password' => 'admin',
            'ppmp_year' => '2022',
            'branches_id' => 9,
            'is_active' => 1,
        ]);
        UserProfile::create([
            'users_id' => 4,
            'first_name' => 'Joenn',
            'last_name' => 'Aquilino',
            'positions_id' => 2,
        ]);

        User::create([
            'username' => 'bouser',
            'email' => 'bouser@admin.com',
            'account_type' => 'BUDGET_OFFICE',
            'password' => 'admin',
            'ppmp_year' => '2022',
            'branches_id' => 3,
            'is_active' => 1,
        ]);
        Position::create([
            'description' => 'Budget Office Staff',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        UserProfile::create([
            'users_id' => 5,
            'first_name' => 'Budget Office',
            'last_name' => 'User',
            'positions_id' => 3,
        ]);

        User::create([
            'username' => 'procure',
            'email' => 'procure@admin.com',
            'account_type' => 'PROCUREMENT_OFFICE',
            'password' => 'admin',
            'ppmp_year' => '2022',
            'branches_id' => 4,
            'is_active' => 1,
        ]);
        Position::create([
            'description' => 'Procurement Unit Staff',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        UserProfile::create([
            'users_id' => 6,
            'first_name' => 'Procurement Unit',
            'last_name' => 'User 1',
            'positions_id' => 4,
        ]);

        User::create([
            'username' => 'souser',
            'email' => 'supply@admin.com',
            'account_type' => 'SUPPLY_OFFICE',
            'password' => 'admin',
            'ppmp_year' => '2022',
            'branches_id' => 5,
            'is_active' => 1,
        ]);
        Position::create([
            'description' => 'Supply Office Staff',
            'is_delete' => 0,
            'added_by' => 1,
        ]);
        UserProfile::create([
            'users_id' => 7,
            'first_name' => 'Supply',
            'last_name' => 'Office',
            'positions_id' => 5,
        ]);
    }
}
