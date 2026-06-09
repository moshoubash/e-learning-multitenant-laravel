<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE tenants MODIFY COLUMN plan ENUM('free', 'pro', 'enterprise') NOT NULL DEFAULT 'free'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE tenants DROP CONSTRAINT IF EXISTS tenants_plan_check");
            DB::statement("ALTER TABLE tenants ADD CONSTRAINT tenants_plan_check CHECK (plan IN ('free', 'pro', 'enterprise'))");
            DB::statement("ALTER TABLE tenants ALTER COLUMN plan SET DEFAULT 'free'");
        } else {
            Schema::table('tenants', function ($table) {
                $table->enum('plan', ['free', 'pro', 'enterprise'])->default('free')->change();
            });
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE tenants MODIFY COLUMN plan ENUM('school', 'university', 'organization') NOT NULL DEFAULT 'school'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE tenants DROP CONSTRAINT IF EXISTS tenants_plan_check");
            DB::statement("ALTER TABLE tenants ADD CONSTRAINT tenants_plan_check CHECK (plan IN ('school', 'university', 'organization'))");
            DB::statement("ALTER TABLE tenants ALTER COLUMN plan SET DEFAULT 'school'");
        } else {
            Schema::table('tenants', function ($table) {
                $table->enum('plan', ['school', 'university', 'organization'])->default('school')->change();
            });
        }
    }
};
