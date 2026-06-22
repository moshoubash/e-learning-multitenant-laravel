<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
<div class="min-h-full bg-surface-container-low flex items-center justify-center p-6" style="font-family: 'Space Grotesk', sans-serif;">
    <div class="relative w-full max-w-[1123px] bg-surface-container-lowest" style="border:4px solid var(--color-on-surface);min-height:500px;height:auto;">

        {{-- Background Accent Stamp --}}
        <div class="absolute -bottom-20 ltr:-right-20 rtl:-left-20 w-[400px] h-[400px] bg-primary-container z-0 flex items-center justify-center" style="border:4px solid var(--color-on-surface);transform:rotate(15deg);">
            <div style="transform:rotate(-15deg);">
                <i class="fas fa-certificate text-[120px] text-on-surface"></i>
            </div>
        </div>

        <div class="absolute top-4 left-4 right-4 bottom-4 pointer-events-none opacity-20 z-0" style="border:2px solid var(--color-on-surface);"></div>

        {{-- Header --}}
        <div class="relative z-10 p-12 pb-0 flex justify-between items-start">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 flex items-center justify-center flex-shrink-0 bg-primary-container" style="border:2px solid var(--color-on-surface);">
                    <i class="fas fa-graduation-cap text-on-surface text-4xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold uppercase leading-none tracking-tighter text-on-surface">grid lms</h1>
                    <p class="text-xs font-bold uppercase text-secondary mt-1" style="letter-spacing:0.3em;">{{ __('messages.Neo-Brutalist Learning OS') }}</p>
                </div>
            </div>
            <div class="text-right ltr:text-right rtl:text-left">
                <p class="text-sm font-bold uppercase text-secondary" style="letter-spacing:0.2em;">{{ __('messages.Certificate ID') }}</p>
                <p class="text-xl font-bold text-on-surface">{{ $certificateId }}</p>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="relative z-10 flex flex-col items-center justify-center text-center flex-1 px-12 mt-12">
            <div class="inline-block bg-on-surface text-white px-8 py-2 mb-8" style="border:2px solid var(--color-on-surface);transform:rotate(-1deg);">
                <h2 class="text-2xl font-bold uppercase text-white" style="letter-spacing:0.2em;">{{ __('messages.Certificate of Completion') }}</h2>
            </div>

            <p class="text-lg font-medium uppercase text-secondary mb-4" style="letter-spacing:0.15em;">{{ __('messages.This is to certify that') }}</p>

            <div class="relative mb-8">
                <h3 class="text-7xl lg:text-8xl font-black uppercase tracking-tighter text-on-surface relative z-10 leading-none">{{ $user->name }}</h3>
                <div class="absolute -bottom-2 left-0 w-full h-6 bg-primary-container -z-10 opacity-80"></div>
            </div>

            <p class="text-lg font-medium uppercase text-secondary mb-4" style="letter-spacing:0.15em;">{{ __('messages.Has successfully completed the course') }}</p>

            <h4 class="text-4xl lg:text-5xl font-bold uppercase text-on-surface mb-12 max-w-3xl" style="letter-spacing:-0.02em;">{{ $course->title }}</h4>

            <div class="w-24 bg-on-surface mb-12" style="height:3px;"></div>
        </div>

        {{-- Footer --}}
        <div class="relative z-10 px-12 pb-12 flex justify-between items-end">
            <div>
                <p class="text-sm font-bold uppercase text-secondary" style="letter-spacing:0.2em;">{{ __('messages.Date of Issue') }}</p>
                <p class="text-xl font-bold uppercase text-on-surface">{{ $completedAt }}</p>
            </div>
            <div class="flex gap-16 ltr:mr-32 rtl:ml-32">
                <div class="text-center">
                    <div class="mb-4 flex items-end justify-center" style="height:36px;">
                        @if($instructor)
                            <span style="font-family:'Dancing Script',cursive;font-size:24px;color:var(--color-on-surface);">{{ $instructor->name }}</span>
                        @else
                            <span style="font-family:'Dancing Script',cursive;font-size:24px;color:var(--color-on-surface);">—</span>
                        @endif
                    </div>
                    <p class="font-bold uppercase text-xs text-on-surface" style="letter-spacing:0.1em;">{{ $instructor->name ?? '—' }}</p>
                    <div class="w-48 bg-on-surface mx-auto mb-2" style="height:1px;"></div>
                    <p class="text-[10px] uppercase text-secondary">{{ __('messages.Instructor') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Download Button --}}
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 flex gap-4">
        <a href="{{ route('tenant.student.certificate.download', $course) }}"
           class="px-6 py-3 bg-on-surface text-white font-bold uppercase flex items-center gap-2" style="border:2px solid var(--color-on-surface);box-shadow:4px 4px 0 0 var(--color-primary-container);">
            <i class="fas fa-download"></i>
            {{ __('messages.Download Certificate') }}
        </a>
    </div>
</div>
