<?php

namespace Database\Seeders;

use App\Models\Tenant\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::firstOrCreate(
            ['slug' => 'software-development'],
            ['name' => 'Software Development', 'description' => 'Courses covering programming languages, web development frameworks, and software engineering best practices.']
        );

        Department::firstOrCreate(
            ['slug' => 'cloud-engineering'],
            ['name' => 'Cloud Engineering', 'description' => 'Cloud computing, AWS, Azure, DevOps, and infrastructure management courses.']
        );

        Department::firstOrCreate(
            ['slug' => 'business-management'],
            ['name' => 'Business & Management', 'description' => 'Business strategy, project management, leadership, and organizational skills.']
        );
    }
}
