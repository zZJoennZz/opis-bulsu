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
            'branch_name' => 'DEVELOPER',
            'type' => 'DEVELOPER',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Main Office',
            'type' => 'OFFICE',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Budget Office',
            'type' => 'OFFICE',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Procurement Unit',
            'type' => 'OFFICE',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Supply Office',
            'type' => 'OFFICE',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Hagonoy Campus',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Meneses Campus',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Bustos Campus',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Sarmiento Campus',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Architecture and Fine Arts',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Arts and Letters',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Business Administration',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Criminal Justice Education',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Education',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Engineering',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Home Economics',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Industrial Technology',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Information and Communications Technology',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Law',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Nursing',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Physical Education, Recreation and Sports',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Science',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'College of Social Science and Philosophy',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
        Branch::create([
            'branch_name' => 'Graduate School',
            'type' => 'CAMPUS',
            'address' => null,
            'email_address' => null,
            'contact_number' => null,
            'is_delete' => 0,
            'added_by' => 1,
            'is_update' => 0,
        ]);
    }
}
