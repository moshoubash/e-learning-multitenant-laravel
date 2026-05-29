<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'course_id')) {
                $table->foreignId('course_id')->nullable()->constrained()->cascadeOnDelete()->after('section_id');
            }
            if (!Schema::hasColumn('assignments', 'instructions')) {
                $table->longText('instructions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('assignments', 'due_date')) {
                $table->dateTime('due_date')->nullable()->after('instructions');
            }
            if (!Schema::hasColumn('assignments', 'max_score')) {
                $table->unsignedInteger('max_score')->default(100)->after('due_date');
            }
            if (!Schema::hasColumn('assignments', 'allow_late')) {
                $table->boolean('allow_late')->default(false)->after('max_score');
            }
            if (!Schema::hasColumn('assignments', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('allow_late');
            }
            if (!Schema::hasColumn('assignments', 'status')) {
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('created_by');
            }
        });

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->longText('content')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->unsignedInteger('attempt_number')->default(1);
            $table->enum('status', ['draft', 'submitted', 'graded', 'returned'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('assignment_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });

        Schema::create('assignment_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_submission_id')->constrained('assignment_submissions')->cascadeOnDelete();
            $table->unsignedInteger('score')->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->enum('status', ['pending', 'graded', 'returned'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_grades');
        Schema::dropIfExists('assignment_attachments');
        Schema::dropIfExists('assignment_submissions');

        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('assignments', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
            if (Schema::hasColumn('assignments', 'allow_late')) {
                $table->dropColumn('allow_late');
            }
            if (Schema::hasColumn('assignments', 'max_score')) {
                $table->dropColumn('max_score');
            }
            if (Schema::hasColumn('assignments', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('assignments', 'instructions')) {
                $table->dropColumn('instructions');
            }
            if (Schema::hasColumn('assignments', 'course_id')) {
                $table->dropConstrainedForeignId('course_id');
            }
        });
    }
};
