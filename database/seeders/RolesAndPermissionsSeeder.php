<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Course management
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',

            // Section management
            'view sections',
            'create sections',
            'edit sections',
            'delete sections',

            // Lesson management
            'view lessons',
            'create lessons',
            'edit lessons',
            'delete lessons',

            // Quiz management
            'view quizzes',
            'create quizzes',
            'edit quizzes',
            'delete quizzes',

            // Enrollment management
            'view enrollments',
            'create enrollments',
            'edit enrollments',
            'delete enrollments',

            // Quiz attempts & progress
            'take quizzes',
            'view own progress',
            'view all progress',
        ];

        $guard = tenant() ? 'tenant' : 'web';

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guard]);
        }

        // Create roles and assign permissions

        // Admin role — full tenant management
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $adminRole->syncPermissions(Permission::all());

        // Instructor role — can manage their own courses, sections, lessons, quizzes
        $instructorRole = Role::firstOrCreate(['name' => 'instructor', 'guard_name' => $guard]);
        $instructorRole->syncPermissions([
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            'view sections',
            'create sections',
            'edit sections',
            'delete sections',
            'view lessons',
            'create lessons',
            'edit lessons',
            'delete lessons',
            'view quizzes',
            'create quizzes',
            'edit quizzes',
            'delete quizzes',
            'view enrollments',
            'view own progress',
        ]);

        // Student role — can view courses, take quizzes, and view own progress
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => $guard]);
        $studentRole->syncPermissions([
            'view courses',
            'view sections',
            'view lessons',
            'view quizzes',
            'take quizzes',
            'view own progress',
        ]);

        // Reset cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
