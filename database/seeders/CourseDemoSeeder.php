<?php

namespace Database\Seeders;

use App\Models\Tenant\Assignment;
use App\Models\Tenant\Course;
use App\Models\Tenant\Enrollment;
use App\Models\Tenant\Lesson;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizOption;
use App\Models\Tenant\QuizQuestion;
use App\Models\Tenant\Section;
use App\Models\Tenant\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseDemoSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            ['name' => 'Instructor User', 'password' => Hash::make('password')]
        );
        $instructor->assignRole('instructor');

        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            ['name' => 'Student User', 'password' => Hash::make('password')]
        );
        $student->assignRole('student');

        $coursesData = [
            'php' => [
                'title' => 'PHP Programming',
                'description' => 'Learn PHP from scratch — variables, functions, arrays, and more.',
                'sections' => [
                    [
                        'title' => 'PHP Basics',
                        'lessons' => [
                            ['title' => 'Introduction to PHP', 'content' => 'Welcome to PHP. In this lesson we cover what PHP is and how it works on the server.'],
                            ['title' => 'Variables and Data Types', 'content' => 'Learn about strings, integers, floats, booleans, and how to declare variables in PHP.'],
                            ['title' => 'Control Structures', 'content' => 'Master if-else, switch, loops, and conditional logic in PHP.'],
                        ],
                        'quiz' => [
                            'title' => 'PHP Basics Quiz',
                            'questions' => [
                                [
                                    'question' => 'PHP is a client-side scripting language.',
                                    'type' => 'true_false',
                                    'options' => [
                                        ['text' => 'True', 'correct' => false],
                                        ['text' => 'False', 'correct' => true],
                                    ],
                                ],
                                [
                                    'question' => 'Which function is used to output text in PHP?',
                                    'type' => 'single',
                                    'options' => [
                                        ['text' => 'print()', 'correct' => true],
                                        ['text' => 'write()', 'correct' => false],
                                        ['text' => 'display()', 'correct' => false],
                                        ['text' => 'output()', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which of the following are valid PHP variable names?',
                                    'type' => 'multiple',
                                    'options' => [
                                        ['text' => '$myVar', 'correct' => true],
                                        ['text' => '$_var', 'correct' => true],
                                        ['text' => '$123var', 'correct' => false],
                                        ['text' => '$var-name', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                        'assignment' => [
                            'title' => 'PHP Basics Assignment',
                            'description' => 'Write a PHP script that declares variables of each data type and outputs them using echo.',
                        ],
                    ],
                    [
                        'title' => 'PHP Functions & Arrays',
                        'lessons' => [
                            ['title' => 'Functions', 'content' => 'Learn how to define and call functions, pass arguments, and return values in PHP.'],
                            ['title' => 'Arrays', 'content' => 'Explore indexed arrays, associative arrays, and multidimensional arrays in PHP.'],
                            ['title' => 'String Manipulation', 'content' => 'Work with string functions like strlen, strpos, substr, and explode.'],
                        ],
                        'quiz' => [
                            'title' => 'Functions & Arrays Quiz',
                            'questions' => [
                                [
                                    'question' => 'A function in PHP must always return a value.',
                                    'type' => 'true_false',
                                    'options' => [
                                        ['text' => 'True', 'correct' => false],
                                        ['text' => 'False', 'correct' => true],
                                    ],
                                ],
                                [
                                    'question' => 'Which function counts the number of elements in an array?',
                                    'type' => 'single',
                                    'options' => [
                                        ['text' => 'count()', 'correct' => true],
                                        ['text' => 'sizeof()', 'correct' => false],
                                        ['text' => 'length()', 'correct' => false],
                                        ['text' => 'array_count()', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which of the following are valid PHP array functions?',
                                    'type' => 'multiple',
                                    'options' => [
                                        ['text' => 'array_push()', 'correct' => true],
                                        ['text' => 'array_merge()', 'correct' => true],
                                        ['text' => 'array_split()', 'correct' => false],
                                        ['text' => 'array_combine()', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                        'assignment' => [
                            'title' => 'Functions & Arrays Assignment',
                            'description' => 'Create a PHP function that takes an array of numbers and returns the sum and average.',
                        ],
                    ],
                ],
            ],
            'laravel' => [
                'title' => 'Laravel Framework',
                'description' => 'Build modern web applications with the Laravel PHP framework.',
                'sections' => [
                    [
                        'title' => 'Laravel Fundamentals',
                        'lessons' => [
                            ['title' => 'What is Laravel?', 'content' => 'Introduction to Laravel, its philosophy, and the MVC architecture pattern.'],
                            ['title' => 'Routing', 'content' => 'Learn how to define routes, route parameters, and named routes in Laravel.'],
                            ['title' => 'Blade Templates', 'content' => 'Master Blade templating, layouts, components, and directives.'],
                        ],
                        'quiz' => [
                            'title' => 'Laravel Fundamentals Quiz',
                            'questions' => [
                                [
                                    'question' => 'Laravel follows the MVC architecture pattern.',
                                    'type' => 'true_false',
                                    'options' => [
                                        ['text' => 'True', 'correct' => true],
                                        ['text' => 'False', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which command creates a new Laravel application?',
                                    'type' => 'single',
                                    'options' => [
                                        ['text' => 'composer create-project laravel/laravel', 'correct' => true],
                                        ['text' => 'npm init laravel', 'correct' => false],
                                        ['text' => 'php artisan new app', 'correct' => false],
                                        ['text' => 'laravel new app', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which of the following are valid Blade directives?',
                                    'type' => 'multiple',
                                    'options' => [
                                        ['text' => '@if', 'correct' => true],
                                        ['text' => '@foreach', 'correct' => true],
                                        ['text' => '@while', 'correct' => false],
                                        ['text' => '@switch', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                        'assignment' => [
                            'title' => 'Laravel Fundamentals Assignment',
                            'description' => 'Create a Laravel project with three routes and corresponding Blade views.',
                        ],
                    ],
                    [
                        'title' => 'Database & Eloquent',
                        'lessons' => [
                            ['title' => 'Migrations', 'content' => 'Learn how to create and run migrations to manage database schema in Laravel.'],
                            ['title' => 'Eloquent ORM', 'content' => 'Introduction to Eloquent — models, queries, accessors, and mutators.'],
                            ['title' => 'Relationships', 'content' => 'Master Eloquent relationships: one-to-one, one-to-many, and many-to-many.'],
                        ],
                        'quiz' => [
                            'title' => 'Database & Eloquent Quiz',
                            'questions' => [
                                [
                                    'question' => 'Migrations in Laravel use SQL directly without any abstraction layer.',
                                    'type' => 'true_false',
                                    'options' => [
                                        ['text' => 'True', 'correct' => false],
                                        ['text' => 'False', 'correct' => true],
                                    ],
                                ],
                                [
                                    'question' => 'Which method is used to define a one-to-many relationship in Eloquent?',
                                    'type' => 'single',
                                    'options' => [
                                        ['text' => 'hasMany()', 'correct' => true],
                                        ['text' => 'belongsTo()', 'correct' => false],
                                        ['text' => 'hasOne()', 'correct' => false],
                                        ['text' => 'belongsToMany()', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which of the following are valid Eloquent methods for querying?',
                                    'type' => 'multiple',
                                    'options' => [
                                        ['text' => 'where()', 'correct' => true],
                                        ['text' => 'find()', 'correct' => true],
                                        ['text' => 'select()', 'correct' => false],
                                        ['text' => 'join()', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                        'assignment' => [
                            'title' => 'Database & Eloquent Assignment',
                            'description' => 'Create a migration for a posts table and define Eloquent relationships between User and Post models.',
                        ],
                    ],
                ],
            ],
            'aws' => [
                'title' => 'Amazon Web Services',
                'description' => 'Master cloud computing with AWS — EC2, S3, Lambda, and more.',
                'sections' => [
                    [
                        'title' => 'AWS Core Services',
                        'lessons' => [
                            ['title' => 'Introduction to AWS', 'content' => 'Overview of AWS cloud computing, global infrastructure, and core services.'],
                            ['title' => 'EC2 Instances', 'content' => 'Learn to launch, configure, and manage virtual servers using EC2.'],
                            ['title' => 'S3 Storage', 'content' => 'Understand object storage with S3 — buckets, objects, permissions, and versioning.'],
                        ],
                        'quiz' => [
                            'title' => 'AWS Core Services Quiz',
                            'questions' => [
                                [
                                    'question' => 'S3 stands for Simple Storage Service.',
                                    'type' => 'true_false',
                                    'options' => [
                                        ['text' => 'True', 'correct' => true],
                                        ['text' => 'False', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which AWS service provides virtual servers in the cloud?',
                                    'type' => 'single',
                                    'options' => [
                                        ['text' => 'EC2', 'correct' => true],
                                        ['text' => 'Lambda', 'correct' => false],
                                        ['text' => 'RDS', 'correct' => false],
                                        ['text' => 'S3', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which of the following are S3 storage classes?',
                                    'type' => 'multiple',
                                    'options' => [
                                        ['text' => 'S3 Standard', 'correct' => true],
                                        ['text' => 'S3 Glacier', 'correct' => true],
                                        ['text' => 'S3 Express', 'correct' => false],
                                        ['text' => 'S3 Lite', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                        'assignment' => [
                            'title' => 'AWS Core Services Assignment',
                            'description' => 'Write a step-by-step guide to launch an EC2 instance and host a static website on S3.',
                        ],
                    ],
                    [
                        'title' => 'AWS Advanced',
                        'lessons' => [
                            ['title' => 'Lambda Functions', 'content' => 'Learn serverless computing with AWS Lambda — triggers, runtime, and deployment.'],
                            ['title' => 'RDS Databases', 'content' => 'Set up and manage relational databases using Amazon RDS.'],
                            ['title' => 'CloudFront CDN', 'content' => 'Deliver content globally with CloudFront — distributions, origins, and caching.'],
                        ],
                        'quiz' => [
                            'title' => 'AWS Advanced Quiz',
                            'questions' => [
                                [
                                    'question' => 'AWS Lambda supports only Python and Node.js runtimes.',
                                    'type' => 'true_false',
                                    'options' => [
                                        ['text' => 'True', 'correct' => false],
                                        ['text' => 'False', 'correct' => true],
                                    ],
                                ],
                                [
                                    'question' => 'Which AWS service is used for content delivery and CDN?',
                                    'type' => 'single',
                                    'options' => [
                                        ['text' => 'CloudFront', 'correct' => true],
                                        ['text' => 'Route 53', 'correct' => false],
                                        ['text' => 'API Gateway', 'correct' => false],
                                        ['text' => 'ELB', 'correct' => false],
                                    ],
                                ],
                                [
                                    'question' => 'Which of the following are features of Amazon RDS?',
                                    'type' => 'multiple',
                                    'options' => [
                                        ['text' => 'Automated backups', 'correct' => true],
                                        ['text' => 'Multi-AZ deployment', 'correct' => true],
                                        ['text' => 'Built-in load balancing', 'correct' => false],
                                        ['text' => 'Auto-scaling storage', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                        'assignment' => [
                            'title' => 'AWS Advanced Assignment',
                            'description' => 'Design a serverless architecture using Lambda, API Gateway, and DynamoDB for a simple REST API.',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($coursesData as $slug => $courseData) {
            $course = Course::create([
                'instructor_id' => $instructor->id,
                'title' => $courseData['title'],
                'slug' => $slug,
                'description' => $courseData['description'],
                'status' => 'published',
                'price' => 0,
            ]);

            foreach ($courseData['sections'] as $sectionIndex => $sectionData) {
                $section = Section::create([
                    'course_id' => $course->id,
                    'title' => $sectionData['title'],
                    'order' => $sectionIndex + 1,
                ]);

                foreach ($sectionData['lessons'] as $lessonIndex => $lessonData) {
                    Lesson::create([
                        'section_id' => $section->id,
                        'title' => $lessonData['title'],
                        'type' => 'video',
                        'content' => $lessonData['content'],
                        'video_url' => null,
                        'order' => $lessonIndex + 1,
                    ]);
                }

                $quiz = Quiz::create([
                    'section_id' => $section->id,
                    'title' => $sectionData['quiz']['title'],
                    'pass_percentage' => 70,
                    'can_reattempt' => true,
                    'max_attempts' => 3,
                ]);

                foreach ($sectionData['quiz']['questions'] as $qIndex => $questionData) {
                    $question = QuizQuestion::create([
                        'quiz_id' => $quiz->id,
                        'question' => $questionData['question'],
                        'type' => $questionData['type'],
                        'order' => $qIndex + 1,
                    ]);

                    foreach ($questionData['options'] as $optionData) {
                        QuizOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optionData['text'],
                            'is_correct' => $optionData['correct'],
                        ]);
                    }
                }

                Assignment::create([
                    'course_id' => $course->id,
                    'section_id' => $section->id,
                    'title' => $sectionData['assignment']['title'],
                    'description' => $sectionData['assignment']['description'],
                    'status' => 'published',
                    'created_by' => $instructor->id,
                    'order' => 1,
                ]);
            }
        }

        $phpCourse = Course::where('slug', 'php')->first();
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $phpCourse->id,
            'status' => 'active',
        ]);
    }
}
