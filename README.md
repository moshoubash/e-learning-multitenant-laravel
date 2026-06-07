# E-Learning Platform

A multi-tenant e-learning platform built with **Laravel 12**, **Livewire**, and **Tailwind CSS**. This platform enables educational institutions or organizations to run their own branded learning management system (LMS) with support for courses, quizzes, assignments, video lessons, and secure payment processing.

## Features

### Multi-Tenancy
- Each tenant (organization) operates as an isolated instance with its own database and subdomain
- Central application for tenant registration and management
- Built on [`stancl/tenancy`](https://tenancyforlaravel.com/)

### Role-Based Access Control
- **Admin** — Full system oversight, manage tenants, courses, quizzes, and users
- **Instructor** — Create and manage courses, sections, lessons, quizzes, and assignments
- **Student** — Enroll in courses, view lessons, submit assignments, take quizzes, and track progress

### Learning Management
- **Courses** — Structured with sections and lessons
- **Video Lessons** — Video hosting via AWS S3 with metadata extraction (getID3, FFmpeg), played using Plyr
- **Quizzes** — Multiple-choice quizzes with configurable re-attempts per-tenant
- **Assignments** — File-based submissions with instructor grading
- **Progress Tracking** — Track lesson completion and enrollment status

### Payments
- **Stripe** integration for course enrollment payments
- Checkout, payment processing, and confirmation flow

### Internationalization
- Bilingual support (English / Arabic)

### Developer Experience
- **Laravel Volt** — Livewire single-file components
- **Laravel Breeze** — Authentication scaffolding
- **Pest PHP** — Testing framework
- **Vite** — Frontend asset bundling
- **Tailwind CSS** — Utility-first styling

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | Laravel 12, PHP 8.2+ |
| **Frontend** | Livewire 4, Volt, Tailwind CSS, Vite |
| **Database** | MySQL / SQLite (per-tenant) |
| **File Storage** | AWS S3 (Flysystem) |
| **Content Delivery Network** | AWS CloudFront |
| **Payments** | Stripe |
| **Video Processing** | FFmpeg, getID3 |
| **Multi-Tenancy** | stancl/tenancy |
| **Roles & Permissions** | spatie/laravel-permission |


## Project Structure

```
app/
├── Http/Controllers/     — Payment & tenant management controllers
├── Livewire/             — Livewire components (Dashboard, Profile)
├── Models/               — Central models (User, Tenant)
├── Models/Tenant/        — Tenant-scoped models (Course, Lesson, Quiz, etc.)
├── Services/
│   ├── Admin/            — Admin business logic
│   ├── Instructor/       — Instructor business logic
│   └── Student/          — Student business logic (courses, payments, quizzes)
├── Actions/              — Reusable action classes
└── Providers/            — Service providers
database/
└── migrations/
    ├── central/          — Central database migrations
    └── tenant/           — Per-tenant database migrations
```
