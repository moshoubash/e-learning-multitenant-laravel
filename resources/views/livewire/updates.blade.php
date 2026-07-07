<div class="min-h-screen bg-[#F4F4F4]">
    {{-- Sticky Top Nav --}}
    <header class="sticky top-0 z-50 flex items-center justify-between w-full px-8 py-4 border-b-2 bg-surface border-on-background" style="background-color: #F4F4F4;">
        <h1 class="text-5xl italic font-bold tracking-tighter uppercase text-on-surface">
            <a href="/">
                GRID
                <sup class="px-2 -tracking-[0.02em] not-italic" style="background-color: var(--color-primary-container, #FFD600); border: 2px solid var(--color-on-surface, #0A0A0A); font-size: 10px; vertical-align: super;">LMS</sup>
            </a>
        </h1>
        <nav class="items-center hidden gap-6 lg:flex">
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="{{ route('landing.home') }}#features" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.Features') }}</a>
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="{{ route('landing.home') }}#how-it-works" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.How it Works') }}</a>
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="{{ route('landing.home') }}#contact" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.Contact') }}</a>
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="{{ route('updates') }}" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.Updates') }}</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ url('lang/' . (app()->getLocale() === 'en' ? 'ar' : 'en')) }}"
               class="px-6 py-2 text-sm font-bold tracking-wider uppercase transition-colors neo-border-sm"
               style="font-family: 'Space Grotesk', sans-serif; border: 2px solid #0A0A0A; border-radius: 4px; color: #0A0A0A; background-color: #FFFFFF;">
                {{ app()->getLocale() === 'en' ? 'العربية' : 'EN' }}
            </a>
        </div>
    </header>

    <main class="px-8 py-20">
        <div class="max-w-4xl mx-auto">
            <h1 class="mb-4 text-5xl font-bold text-center uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">{{ __('messages.Updates') }}</h1>
            <p class="mb-16 text-lg text-center" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                {{ __('messages.Track every major update and improvement to the platform.') }}
            </p>

            <div class="space-y-12">
                {{-- v1.6.0 --}}
                <div class="relative pl-8 border-l-4 border-on-surface">
                    <span class="absolute left-[-3px] flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">6</span>
                    <div class="card">
                        <div class="flex items-baseline gap-3 mb-4">
                            <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">v1.6</h3>
                            <span class="text-xs font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">Latest</span>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">AI Quiz Generation</strong> — Generate quiz questions using AI via OpenRouter (free DeepSeek model included).</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- v1.5.0 --}}
                <div class="relative pl-8 border-l-4 border-on-surface">
                    <span class="absolute left-[-3px] flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">5</span>
                    <div class="card">
                        <div class="flex items-baseline gap-3 mb-4">
                            <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">v1.5</h3>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Admin Broadcast Notifications</strong> — Send notifications to all users, students, instructors, or specific users with a searchable multi-select modal.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Dynamic Auth Colors</strong> — Customizable color variables for auth pages (borders, shadows) via Design Config.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- v1.4.0 --}}
                <div class="relative pl-8 border-l-4 border-on-surface">
                    <span class="absolute left-[-3px] flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">4</span>
                    <div class="card">
                        <div class="flex items-baseline gap-3 mb-4">
                            <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">v1.4</h3>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Departments Management</strong> — Organize users and courses by department. Students only see courses relevant to their department.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Sitemap Generator</strong> — Automatic sitemap generation for all tenant landing pages, indexed by search engines daily.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Google Search Console</strong> — Verification meta tag added to all layouts for SEO monitoring.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- v1.3.0 --}}
                <div class="relative pl-8 border-l-4 border-on-surface">
                    <span class="absolute left-[-3px] flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">3</span>
                    <div class="card">
                        <div class="flex items-baseline gap-3 mb-4">
                            <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">v1.3</h3>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Points & Leaderboard</strong> — Students earn points for completing lessons, passing quizzes (bonus for 90%+), and finishing courses. Leaderboard shows rankings.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Admin Leaderboard Monitor</strong> — View student rankings, points, and completion stats from the admin panel.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Logo Upload</strong> — Upload and remove logos via Design Config, displayed dynamically across the platform.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- v1.2.0 --}}
                <div class="relative pl-8 border-l-4 border-on-surface">
                    <span class="absolute left-[-3px] flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">2</span>
                    <div class="card">
                        <div class="flex items-baseline gap-3 mb-4">
                            <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">v1.2</h3>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Student Enrollment History</strong> — Dedicated page showing all past enrollments with filtering by status.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Excel User Import</strong> — Bulk import users from Excel files with validation and tenant user limits.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">YouTube Playlist Import</strong> — Import entire YouTube playlists as course lessons with preview.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">SMTP Configuration</strong> — Dynamic email settings configuration from admin panel.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Redis Caching</strong> — Performance optimization with Redis for KPI data, reducing server load.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- v1.1.0 --}}
                <div class="relative pl-8 border-l-4 border-on-surface">
                    <span class="absolute left-[-3px] flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">1</span>
                    <div class="card">
                        <div class="flex items-baseline gap-3 mb-4">
                            <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">v1.1</h3>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Course Video Uploads</strong> — Upload videos directly to lessons with S3 storage and CloudFront delivery.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Course Thumbnails</strong> — Upload custom thumbnails for courses via the edit modal.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Notification System</strong> — Real-time polling for notifications with a dedicated notification page and bell icon.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Assignments & Submissions</strong> — Create assignments with due dates, file attachments, and late submission rules. Grade submissions with feedback.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Role & Permission Management</strong> — Create, edit, delete roles and permissions from admin panel. Allow deletion of core roles.</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span><strong class="text-on-surface">Contact Form Rate Limiting</strong> — Rate-limited contact form with email notifications and database storage.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- v1.0 --}}
                <div class="relative pl-8 border-l-4 border-on-surface">
                    <span class="absolute left-[-3px] flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">0</span>
                    <div class="card">
                        <div class="flex items-baseline gap-3 mb-4">
                            <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">v1.0</h3>
                            <span class="text-xs font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">{{ __('messages.Initial Release') }}</span>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span>{{ __('messages.Configure Your Platform — roles, permissions, custom design, SMTP, and OAuth integration.') }}</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span>{{ __('messages.Create Courses & Assessments — structured sections, video lessons, quizzes with auto-grading, and enrollment pricing.') }}</span>
                            </li>
                            <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                <span style="color: #0A0A0A;">{{ app()->getLocale() == 'ar' ? '←' : '→' }}</span>
                                <span>{{ __('messages.Enroll, Learn & Certify — course enrollment, lesson tracking, quiz attempts, and downloadable certificates.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer style="background-color: #e2e2e2; border-top: 2px solid #0A0A0A; padding: 48px 32px;">
        <div class="grid max-w-6xl grid-cols-1 gap-8 mx-auto md:grid-cols-3">
            <div class="flex flex-col gap-4">
                <div class="text-2xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">GRID LMS</div>
                <p class="max-w-xs text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                    {{ __('messages.A complete learning management system for modern institutions. Manage courses, quizzes, students, and certifications — all from one platform.') }}
                </p>
            </div>
            <div class="flex flex-col gap-4">
                <h5 class="text-sm font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">{{ __('messages.Quick Links') }}</h5>
                <nav class="flex flex-col gap-2">
                    <a class="text-base transition-colors" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632; text-decoration: none;" href="{{ route('landing.home') }}" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ __('messages.Home') }}</a>
                    <a class="text-base transition-colors" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632; text-decoration: none;" href="{{ route('landing.home') }}#features" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ __('messages.Features') }}</a>
                    <a class="text-base transition-colors" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632; text-decoration: none;" href="{{ route('landing.home') }}#contact" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ __('messages.Contact') }}</a>
                </nav>
            </div>
            <div class="flex flex-col gap-4 ltr:md:items-end rtl:md:items-start">
                <h5 class="text-sm font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">{{ __('messages.Contact') }}</h5>
                <a class="text-xl font-bold underline transition-colors underline-offset-8 decoration-4" href="mailto:mohammad@gridlms.online" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;" onmouseover="this.style.color='#705d00'" onmouseout="this.style.color='#0A0A0A';">mohammad@gridlms.online</a>
                <div class="pt-8 mt-auto">
                    <p class="text-xs font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">{{ __('© :year GRID LMS', ['year' => date('Y')]) }}</p>
                </div>
            </div>
        </div>
    </footer>
</div>
