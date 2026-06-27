<div class="landing-content">
    {{-- Sticky Top Nav --}}
    <header class="sticky top-0 z-50 flex items-center justify-between w-full px-8 py-4 border-b-2 bg-surface border-on-background" style="background-color: #F4F4F4;">
        <div class="text-2xl font-bold tracking-tighter uppercase text-on-surface" style="font-family: 'Space Grotesk', sans-serif;">GRID LMS</div>
        <nav class="items-center hidden gap-6 lg:flex">
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="#features" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.Features') }}</a>
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="#how-it-works" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.How it Works') }}</a>
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="#contact" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.Contact') }}</a>
            <a class="px-2 py-1 text-sm font-medium tracking-wider uppercase transition-colors text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container" href="#updates" style="font-family: 'Space Grotesk', sans-serif;">{{ __('messages.Updates') }}</a>
        </nav>
        <div class="flex items-center gap-3">
            <a href="{{ url('lang/' . (app()->getLocale() === 'en' ? 'ar' : 'en')) }}"
               class="px-6 py-2 text-sm font-bold tracking-wider uppercase transition-colors neo-border-sm"
               style="font-family: 'Space Grotesk', sans-serif; border: 2px solid #0A0A0A; border-radius: 4px; color: #0A0A0A; background-color: #FFFFFF;">
                {{ app()->getLocale() === 'en' ? 'العربية' : 'EN' }}
            </a>
            {{-- <a href="{{ route('login') }}">
                <button class="px-6 py-2 text-sm font-bold tracking-wider uppercase transition-all btn-primary" style="font-family: 'Space Grotesk', sans-serif; background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 4px;">{{ __('messages.Get Started') }}</button>
            </a> --}}
        </div>
    </header>

    <main>
        {{-- Hero Section --}}
        <section class="flex flex-col items-center px-8 py-20 text-center" style="background-color: #F4F4F4;">
            <h1 class="max-w-4xl mb-6 text-5xl font-bold uppercase md:text-6xl" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">
                {{ __('messages.Manage Courses, Quizzes & Students — All in One Place') }}
            </h1>
            <p class="max-w-2xl mb-10 text-lg" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                {{ __('messages.A complete learning management system with role-based portals for admins, instructors, and students. Create structured curricula, assess with quizzes, track progress, and award certificates — all with a clean, fast interface.') }}
            </p>
            <div class="flex flex-col gap-4 mb-16 sm:flex-row">
                <a href="{{ route('login') }}">
                    <button class="px-8 py-4 text-xl font-bold tracking-wider uppercase transition-all" style="font-family: 'Space Grotesk', sans-serif; background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 4px; color: #0A0A0A;">{{ __('messages.Get Started') }}</button>
                </a>
                <a href="#contact">
                    <button class="px-8 py-4 text-xl font-bold tracking-wider uppercase transition-all" style="font-family: 'Space Grotesk', sans-serif; background-color: #FFFFFF; border: 2px solid #0A0A0A; border-radius: 4px; color: #0A0A0A;">{{ __('messages.Contact Us') }}</button>
                </a>
            </div>
            <div class="w-full max-w-5xl overflow-hidden" style="border: 2px solid #0A0A0A; border-radius: 4px; background-color: #FFFFFF;">
                <div style="background-color: #1a1c1c; padding: 12px; display: flex; gap: 8px;">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #ba1a1a;"></div>
                    <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #FFD600;"></div>
                    <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #c6c6c7;"></div>
                </div>
                <div class="relative overflow-hidden aspect-video" style="background-color: #f3f3f3;">
                    @if(app()->getLocale() == 'ar')
                        <img src="{{ asset('images/student-dashboard-ar.png') }}" alt="{{ __('messages.Student Dashboard Preview') }}" class="object-fill w-full h-full">
                    @else
                        <img src="{{ asset('images/student-dashboard.png') }}" alt="{{ __('messages.Student Dashboard Preview') }}" class="object-fill w-full h-full">
                    @endif
                </div>
            </div>
        </section>

        {{-- Features Grid --}}
        <section id="features" class="px-8 py-24" style="background-color: #ffffff; border-top: 2px solid #0A0A0A; border-bottom: 2px solid #0A0A0A;">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-3xl font-bold uppercase md:text-4xl" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Engineered for Impact') }}</h2>
                <div style="width: 96px; height: 8px; background-color: #FFD600; border: 2px solid #0A0A0A; margin: 0 auto;"></div>
            </div>
            <div class="grid max-w-6xl grid-cols-1 gap-6 mx-auto md:grid-cols-3">
                <div class="flex flex-col items-start transition-colors card" style="cursor: default;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='#FFFFFF'">
                    <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background-color: #FFD600; border: 2px solid #0A0A0A; margin-bottom: 24px;">
                        <span class="material-symbols-outlined" style="color: #0A0A0A; font-variation-settings: 'FILL' 1;">auto_stories</span>
                    </div>
                    <h3 class="mb-4 text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Course Builder') }}</h3>
                    <p class="text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                        {{ __('messages.Organize your curriculum into sections and lessons. Upload videos, set thumbnails, embed resources, and structure the perfect learning path.') }}
                    </p>
                </div>
                <div class="flex flex-col items-start transition-colors card" style="cursor: default;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='#FFFFFF'">
                    <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background-color: #FFD600; border: 2px solid #0A0A0A; margin-bottom: 24px;">
                        <span class="material-symbols-outlined" style="color: #0A0A0A; font-variation-settings: 'FILL' 1;">quiz</span>
                    </div>
                    <h3 class="mb-4 text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Quizzes') }}</h3>
                    <p class="text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                        {{ __('messages.Create assessments with multiple choice and true/false questions. Set pass percentages, track attempts, and auto-grade results. Keep students accountable with structured evaluations.') }}
                    </p>
                </div>
                <div class="flex flex-col items-start transition-colors card" style="cursor: default;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='#FFFFFF'">
                    <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background-color: #FFD600; border: 2px solid #0A0A0A; margin-bottom: 24px;">
                        <span class="material-symbols-outlined" style="color: #0A0A0A; font-variation-settings: 'FILL' 1;">groups</span>
                    </div>
                    <h3 class="mb-4 text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Multi-Role Dashboards') }}</h3>
                    <p class="text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                        {{ __('messages.Dedicated dashboards for admins, instructors, and students. Manage users, assign roles, control permissions, and give every stakeholder the tools they need — nothing more, nothing less.') }}
                    </p>
                </div>
            </div>
            <p class="mt-12 text-base text-center uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632; letter-spacing: 0.05em;">
                {{ __('messages.and more features waiting for you') }}
            </p>
        </section>

        {{-- How It Works --}}
        <section id="how-it-works" class="px-8 py-24" style="background-color: #f3f3f3; border-top: 2px solid #0A0A0A; border-bottom: 2px solid #0A0A0A;">
            <div class="max-w-3xl mx-auto">
                <h2 class="mb-16 text-4xl font-bold text-center uppercase md:text-5xl" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.How it Works') }}</h2>
                <div class="relative space-y-16">
                    <div class="step-line"></div>

                    <div class="relative flex flex-col items-start gap-8 md:flex-row" style="z-index: 10;">
                        <div class="step-square shrink-0">1</div>
                        <div class="flex-grow card">
                            <h4 class="mb-2 text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Configure Your Platform') }}</h4>
                            <p class="text-lg" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                {{ __('messages.Set up your institution — define roles and permissions, customize the design to match your brand, configure SMTP for transactional emails, and integrate OAuth providers like Google for seamless sign-in.') }}
                            </p>
                        </div>
                    </div>

                    <div class="relative flex flex-col items-start gap-8 md:flex-row" style="z-index: 10;">
                        <div class="step-square shrink-0">2</div>
                        <div class="flex-grow card">
                            <h4 class="mb-2 text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Create Courses & Assessments') }}</h4>
                            <p class="text-lg" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                {{ __('messages.Instructors build courses with structured sections, video lessons, and learning materials. Attach quizzes with automated grading, set enrollment pricing, and publish when ready.') }}
                            </p>
                        </div>
                    </div>

                    <div class="relative flex flex-col items-start gap-8 md:flex-row" style="z-index: 10;">
                        <div class="step-square shrink-0">3</div>
                        <div class="flex-grow card">
                            <h4 class="mb-2 text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Enroll, Learn & Certify') }}</h4>
                            <p class="text-lg" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                {{ __('messages.Students browse and enroll in courses, track lesson progress, take quizzes. Upon completion, they earn downloadable certificates to showcase their achievement.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Final CTA --}}
        <section style="background-color: #FFD600; border-top: 2px solid #0A0A0A; padding: 80px 32px; display: flex; flex-direction: column; align-items: center; text-align: center;">
            <h2 class="mb-8 text-4xl font-bold uppercase md:text-5xl" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Ready to Transform Learning?') }}</h2>
            <p class="max-w-xl mb-12 text-lg" style="font-family: 'Space Grotesk', sans-serif; color: #544600;">
                {{ __('messages.From course creation to certification — everything you need to run a modern learning institution in one platform.') }}
            </p>
            <a href="{{ route('login') }}">
                <button class="px-12 py-6 text-xl font-bold tracking-widest uppercase transition-all" style="font-family: 'Space Grotesk', sans-serif; background-color: #FFFFFF; border: 2px solid #0A0A0A; border-radius: 4px; color: #0A0A0A;" onmouseover="this.style.backgroundColor='#0A0A0A'; this.style.color='#FFFFFF';" onmouseout="this.style.backgroundColor='#FFFFFF'; this.style.color='#0A0A0A';">{{ __('messages.Get Started Now') }}</button>
            </a>
        </section>

        {{-- Updates / Changelog --}}
        <section id="updates" style="background-color: #FFFFFF; border-top: 2px solid #0A0A0A; padding: 80px 32px;">
            <div class="max-w-3xl mx-auto">
                <h2 class="mb-16 text-4xl font-bold text-center uppercase md:text-5xl" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Updates') }}</h2>
                <div class="relative space-y-12">
                    {{-- v1.0 --}}
                    <div class="relative flex gap-6 pl-8 border-l-4 border-on-surface">
                        <span class="absolute left-0 flex items-center justify-center w-8 h-8 text-sm font-bold" style="background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 50%; transform: translateX(-50%); top: 0;">1</span>
                        <div class="flex-1 card">
                            <div class="flex items-baseline gap-3 mb-3">
                                <h3 class="text-xl font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">v1.0</h3>
                                <span class="text-xs font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">{{ __('messages.Initial Release') }}</span>
                            </div>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                    <span style="color: #0A0A0A;">
                                        {{ app()->getLocale() == 'ar' ? '←' : '→' }}
                                    </span>
                                    <span>{{ __('messages.Configure Your Platform — roles, permissions, custom design, SMTP, and OAuth integration.') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                    <span style="color: #0A0A0A;">
                                        {{ app()->getLocale() == 'ar' ? '←' : '→' }}
                                    </span>
                                    <span>{{ __('messages.Create Courses & Assessments — structured sections, video lessons, quizzes with auto-grading, and enrollment pricing.') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-base" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632;">
                                    <span style="color: #0A0A0A;">
                                        {{ app()->getLocale() == 'ar' ? '←' : '→' }}
                                    </span>
                                    <span>{{ __('messages.Enroll, Learn & Certify — course enrollment, lesson tracking, quiz attempts, and downloadable certificates.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Contact Form --}}
    <section id="contact" style="background-color: #FFFFFF; border-top: 2px solid #0A0A0A; padding: 80px 32px;">
        <div class="max-w-lg mx-auto">
            <h2 class="mb-12 text-4xl font-bold text-center uppercase md:text-5xl" style="font-family: 'Space Grotesk', sans-serif; letter-spacing: 0.08em; color: #0A0A0A;">{{ __('messages.Get In Touch') }}</h2>
            <form class="grid grid-cols-1 gap-6" wire:submit.prevent="submit">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">{{ __('messages.Name') }}</label>
                    <input wire:model="name" class="p-4 text-base" style="font-family: 'Space Grotesk', sans-serif; border: 2px solid #0A0A0A; border-radius: 0; background-color: #FFFFFF; color: #0A0A0A; outline: none;" placeholder="{{ __('messages.Your Name') }}" type="text" onfocus="this.style.backgroundColor='#f9f9f9'" onblur="this.style.backgroundColor='#FFFFFF'">
                    @error('name') <span class="text-xs font-bold text-error" style="font-family: 'Space Grotesk', sans-serif;">{{ $message }}</span> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">{{ __('messages.Email') }}</label>
                    <input wire:model="email" class="p-4 text-base" style="font-family: 'Space Grotesk', sans-serif; border: 2px solid #0A0A0A; border-radius: 0; background-color: #FFFFFF; color: #0A0A0A; outline: none;" placeholder="{{ __('your@email.com') }}" type="email" onfocus="this.style.backgroundColor='#f9f9f9'" onblur="this.style.backgroundColor='#FFFFFF'">
                    @error('email') <span class="text-xs font-bold text-error" style="font-family: 'Space Grotesk', sans-serif;">{{ $message }}</span> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold uppercase" style="font-family: 'Space Grotesk', sans-serif; color: #0A0A0A;">{{ __('messages.Message') }}</label>
                    <textarea wire:model="message" class="p-4 text-base" style="font-family: 'Space Grotesk', sans-serif; border: 2px solid #0A0A0A; border-radius: 0; background-color: #FFFFFF; color: #0A0A0A; outline: none;" placeholder="{{ __('messages.How can we help?') }}" rows="4" onfocus="this.style.backgroundColor='#f9f9f9'" onblur="this.style.backgroundColor='#FFFFFF'"></textarea>
                    @error('message') <span class="text-xs font-bold text-error" style="font-family: 'Space Grotesk', sans-serif;">{{ $message }}</span> @enderror
                </div>
                <button class="w-full py-6 text-xl font-bold tracking-widest uppercase transition-all btn" type="submit" style="font-family: 'Space Grotesk', sans-serif; background-color: #FFD600; border: 2px solid #0A0A0A; border-radius: 4px; color: #0A0A0A;" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('messages.Send Message') }}</span>
                    <span wire:loading>{{ __('messages.Sending...') }}</span>
                </button>
            </form>
        </div>
    </section>

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
                    <a class="text-base transition-colors" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632; text-decoration: none;" href="{{ route('login') }}" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ __('messages.Get Started') }}</a>
                    <a class="text-base transition-colors" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632; text-decoration: none;" href="#features" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ __('messages.Features') }}</a>
                    <a class="text-base transition-colors" style="font-family: 'Space Grotesk', sans-serif; color: #4d4632; text-decoration: none;" href="#contact" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ __('messages.Contact') }}</a>
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
