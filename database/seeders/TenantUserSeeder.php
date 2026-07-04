<?php

namespace Database\Seeders;

use App\Models\Tenant\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $userModel = tenant() ? \App\Models\Tenant\User::class : \App\Models\User::class;

        $softwareDept = Department::where('slug', 'software-development')->first();
        $cloudDept = Department::where('slug', 'cloud-engineering')->first();
        $businessDept = Department::where('slug', 'business-management')->first();

        // Admin User — no department (sees all)
        $admin = $userModel::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Adminpassword123@'),
                'department_id' => null,
            ]
        );
        $admin->assignRole('admin');

        // Instructor — Software Development
        $instructor = $userModel::firstOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'Instructor User',
                'password' => Hash::make('Instructorpassword123@'),
                'department_id' => $softwareDept?->id,
            ]
        );
        $instructor->assignRole('instructor');

        // Student — Software Development
        $student = $userModel::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('Studentpassword123@'),
                'department_id' => $softwareDept?->id,
            ]
        );
        $student->assignRole('student');

        // Cloud Instructor
        $cloudInstructor = $userModel::firstOrCreate(
            ['email' => 'cloud.instructor@example.com'],
            [
                'name' => 'Cloud Instructor',
                'password' => Hash::make('password'),
                'department_id' => $cloudDept?->id,
            ]
        );
        $cloudInstructor->assignRole('instructor');

        // Cloud Student
        $cloudStudent = $userModel::firstOrCreate(
            ['email' => 'cloud.student@example.com'],
            [
                'name' => 'Cloud Student',
                'password' => Hash::make('password'),
                'department_id' => $cloudDept?->id,
            ]
        );
        $cloudStudent->assignRole('student');

        // Business Instructor
        $businessInstructor = $userModel::firstOrCreate(
            ['email' => 'business.instructor@example.com'],
            [
                'name' => 'Business Instructor',
                'password' => Hash::make('password'),
                'department_id' => $businessDept?->id,
            ]
        );
        $businessInstructor->assignRole('instructor');

        // Business Student
        $businessStudent = $userModel::firstOrCreate(
            ['email' => 'business.student@example.com'],
            [
                'name' => 'Business Student',
                'password' => Hash::make('password'),
                'department_id' => $businessDept?->id,
            ]
        );
        $businessStudent->assignRole('student');
    }
}
