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
            // MySQL refuses to ALTER an ENUM to a smaller set if any
            // existing row holds a value that is not in the new set
            // (Data truncation error 1265). The 3-step pattern:
            //   1. Extend the enum to include BOTH the old and the
            //      new values, defaulting to a new value.
            //   2. UPDATE existing rows to the new values.
            //   3. Shrink the enum back to only the new values.
            // This is a no-op on a fresh install (no rows to update
            // in step 2) and is safe on a populated install.
            DB::statement("ALTER TABLE tenants MODIFY COLUMN plan ENUM('school', 'university', 'organization', 'free', 'pro', 'enterprise') NOT NULL DEFAULT 'free'");
            DB::statement("UPDATE tenants SET plan = 'free'       WHERE plan = 'school'");
            DB::statement("UPDATE tenants SET plan = 'pro'        WHERE plan = 'university'");
            DB::statement("UPDATE tenants SET plan = 'enterprise' WHERE plan = 'organization'");
            DB::statement("ALTER TABLE tenants MODIFY COLUMN plan ENUM('free', 'pro', 'enterprise') NOT NULL DEFAULT 'free'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE tenants DROP CONSTRAINT IF EXISTS tenants_plan_check");
            DB::statement("ALTER TABLE tenants ADD CONSTRAINT tenants_plan_check CHECK (plan IN ('school', 'university', 'organization', 'free', 'pro', 'enterprise'))");
            DB::statement("UPDATE tenants SET plan = 'free'       WHERE plan = 'school'");
            DB::statement("UPDATE tenants SET plan = 'pro'        WHERE plan = 'university'");
            DB::statement("UPDATE tenants SET plan = 'enterprise' WHERE plan = 'organization'");
            DB::statement("ALTER TABLE tenants DROP CONSTRAINT IF EXISTS tenants_plan_check");
            DB::statement("ALTER TABLE tenants ADD CONSTRAINT tenants_plan_check CHECK (plan IN ('free', 'pro', 'enterprise'))");
            DB::statement("ALTER TABLE tenants ALTER COLUMN plan SET DEFAULT 'free'");
        } else {
            // SQLite and others: doctrine's ->change() will fail
            // without doctrine/dbal, so we run a no-op safe
            // fallback that updates data only if any old values
            // exist, then relies on the column already being
            // the new enum (set by the original migration).
            $map = [
                'school' => 'free',
                'university' => 'pro',
                'organization' => 'enterprise',
            ];
            foreach ($map as $old => $new) {
                DB::table('tenants')->where('plan', $old)->update(['plan' => $new]);
            }
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE tenants MODIFY COLUMN plan ENUM('school', 'university', 'organization', 'free', 'pro', 'enterprise') NOT NULL DEFAULT 'school'");
            DB::statement("UPDATE tenants SET plan = 'school'        WHERE plan = 'free'");
            DB::statement("UPDATE tenants SET plan = 'university'    WHERE plan = 'pro'");
            DB::statement("UPDATE tenants SET plan = 'organization'  WHERE plan = 'enterprise'");
            DB::statement("ALTER TABLE tenants MODIFY COLUMN plan ENUM('school', 'university', 'organization') NOT NULL DEFAULT 'school'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE tenants DROP CONSTRAINT IF EXISTS tenants_plan_check");
            DB::statement("ALTER TABLE tenants ADD CONSTRAINT tenants_plan_check CHECK (plan IN ('school', 'university', 'organization', 'free', 'pro', 'enterprise'))");
            DB::statement("UPDATE tenants SET plan = 'school'       WHERE plan = 'free'");
            DB::statement("UPDATE tenants SET plan = 'university'   WHERE plan = 'pro'");
            DB::statement("UPDATE tenants SET plan = 'organization' WHERE plan = 'enterprise'");
            DB::statement("ALTER TABLE tenants DROP CONSTRAINT IF EXISTS tenants_plan_check");
            DB::statement("ALTER TABLE tenants ADD CONSTRAINT tenants_plan_check CHECK (plan IN ('school', 'university', 'organization'))");
            DB::statement("ALTER TABLE tenants ALTER COLUMN plan SET DEFAULT 'school'");
        } else {
            $map = [
                'free' => 'school',
                'pro' => 'university',
                'enterprise' => 'organization',
            ];
            foreach ($map as $new => $old) {
                DB::table('tenants')->where('plan', $new)->update(['plan' => $old]);
            }
        }
    }
};
