<?php

namespace Database\Seeders;

use App\Models\Tenant\Lesson;
use App\Models\Tenant\Section;
use Illuminate\Database\Seeder;

class TextLessonsSeeder extends Seeder
{
    public function run(): void
    {
        $textLessons = [
            [
                'section_title' => 'PHP Basics',
                'lessons' => [
                    ['title' => 'PHP Syntax Reference', 'content' => 'PHP code is executed on the server and produces HTML output.
It can be embedded directly within HTML using <?php ?> tags.
Every PHP statement ends with a semicolon (;).
Variables start with $ followed by the variable name.
Variable names are case-sensitive and start with a letter or underscore.
Comments can be written using // for single-line or /* */ for multi-line.'],
                ],
            ],
            [
                'section_title' => 'PHP Functions & Arrays',
                'lessons' => [
                    ['title' => 'PHP Array Functions Reference', 'content' => 'PHP provides a rich set of array functions:
- array_push() — adds one or more elements to the end of an array.
- array_pop() — removes and returns the last element.
- array_merge() — merges two or more arrays.
- array_keys() — returns all the keys of an array.
- array_values() — returns all the values of an array.
- in_array() — checks if a value exists in an array.
- sort() — sorts an indexed array in ascending order.
- count() — returns the number of elements in an array.'],
                ],
            ],
            [
                'section_title' => 'Laravel Fundamentals',
                'lessons' => [
                    ['title' => 'Laravel Directory Structure', 'content' => 'A Laravel project follows a well-organized directory structure:
- app/ — contains the core application code (Models, Http/Controllers, Livewire).
- bootstrap/ — contains the app bootstrapping scripts.
- config/ — holds all configuration files.
- database/ — migrations, seeders, and factories.
- public/ — the web server document root (index.php).
- resources/ — views (Blade), CSS, JavaScript, and language files.
- routes/ — all route definitions (web.php, api.php, tenant.php).
- storage/ — compiled Blade, logs, cache, and file uploads.
- tests/ — automated tests.'],
                ],
            ],
            [
                'section_title' => 'Database & Eloquent',
                'lessons' => [
                    ['title' => 'Common Eloquent Methods', 'content' => 'Eloquent provides a fluent interface for database queries:
- all() — retrieves all records from the table.
- find($id) — finds a record by its primary key.
- where($col, $op, $val) — adds a where clause.
- first() — returns the first matching record.
- get() — executes the query and returns the results.
- create($data) — inserts a new record.
- update($data) — updates existing records.
- delete() — deletes the record.
- with($relation) — eager loads relationships.
- orderBy($col, $dir) — orders the results.'],
                ],
            ],
            [
                'section_title' => 'AWS Core Services',
                'lessons' => [
                    ['title' => 'AWS Global Infrastructure', 'content' => 'AWS operates a global infrastructure spanning the world:
- Regions — geographically distinct locations (e.g., us-east-1, eu-west-1).
- Availability Zones (AZs) — multiple data centers within each region.
- Edge Locations — content delivery endpoints for CloudFront.
- Local Zones — extensions of regions for ultra-low latency.
Key benefits: high availability, fault tolerance, and low latency.
When architecting on AWS, always design for multi-region and multi-AZ.'],
                ],
            ],
            [
                'section_title' => 'AWS Advanced',
                'lessons' => [
                    ['title' => 'AWS Well-Architected Framework', 'content' => 'The AWS Well-Architected Framework helps build secure, high-performing, resilient, and efficient infrastructure:
- Operational Excellence — run and monitor systems to deliver business value.
- Security — protect data, systems, and assets (IAM, encryption, VPC).
- Reliability — ensure workloads perform correctly through failure recovery.
- Performance Efficiency — use computing resources efficiently.
- Cost Optimization — avoid unnecessary costs (right-sizing, reserved instances).
- Sustainability — minimize environmental impact.
Review your architecture against these pillars regularly.'],
                ],
            ],
        ];

        foreach ($textLessons as $item) {
            $section = Section::where('title', $item['section_title'])->first();
            if (!$section) {
                continue;
            }

            $maxOrder = $section->lessons()->max('order') ?? 0;

            foreach ($item['lessons'] as $index => $lessonData) {
                Lesson::create([
                    'section_id' => $section->id,
                    'title' => $lessonData['title'],
                    'type' => 'text',
                    'content' => $lessonData['content'],
                    'video_url' => null,
                    'order' => $maxOrder + $index + 1,
                ]);
            }
        }
    }
}
