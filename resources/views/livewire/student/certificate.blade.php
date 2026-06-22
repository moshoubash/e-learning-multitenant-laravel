<div class="flex items-center justify-center min-h-full p-6 bg-surface-container-low" style="font-family: 'Space Grotesk', sans-serif;">
    <div class="relative w-full max-w-[1123px] bg-surface-container-lowest" style="border:4px solid var(--color-on-surface);min-height:500px;height:auto;">

        {{-- Background Accent Stamp --}}
        <div class="absolute -bottom-20 ltr:-right-20 rtl:-left-20 w-[400px] h-[400px] bg-primary-container z-0 flex items-center justify-center" style="border:4px solid var(--color-on-surface);transform:rotate(15deg);">
            <div style="transform:rotate(-15deg);">
                <i class="fas fa-certificate text-[120px] text-on-surface"></i>
            </div>
        </div>

        <div class="absolute z-0 pointer-events-none top-4 left-4 right-4 bottom-4 opacity-20" style="border:2px solid var(--color-on-surface);"></div>

        {{-- Header --}}
        <div class="relative z-10 flex items-start justify-between p-12 pb-0">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 bg-primary-container" style="border:2px solid var(--color-on-surface);">
                    <i class="text-4xl fas fa-graduation-cap text-on-surface"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold leading-none tracking-tighter uppercase text-on-surface">grid lms</h1>
                    <p class="mt-1 text-xs font-bold uppercase text-secondary" style="letter-spacing:0.3em;">{{ tenant('name') }}</p>
                </div>
            </div>
            <div class="text-right ltr:text-right rtl:text-left">
                <p class="text-sm font-bold uppercase text-secondary" style="letter-spacing:0.2em;">{{ __('messages.Certificate ID') }}</p>
                <p class="text-xl font-bold text-on-surface">{{ $certificateId }}</p>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="relative z-10 flex flex-col items-center justify-center flex-1 px-12 mt-12 text-center">
            <div class="inline-block px-8 py-2 mb-8 text-white bg-on-surface" style="border:2px solid var(--color-on-surface);transform:rotate(-1deg);">
                <h2 class="text-2xl font-bold text-white uppercase" style="letter-spacing:0.2em;">{{ __('messages.Certificate of Completion') }}</h2>
            </div>

            <p class="mb-4 text-lg font-medium uppercase text-secondary" style="letter-spacing:0.15em;">{{ __('messages.This is to certify that') }}</p>

            <div class="relative mb-8">
                <h3 class="relative z-10 font-black leading-none tracking-tighter uppercase text-7xl lg:text-8xl text-on-surface">{{ $user->name }}</h3>
                <div class="absolute left-0 w-full h-6 -bottom-2 bg-primary-container -z-10 opacity-80"></div>
            </div>

            <p class="mb-4 text-lg font-medium uppercase text-secondary" style="letter-spacing:0.15em;">{{ __('messages.Has successfully completed the course') }}</p>

            <h4 class="max-w-3xl mb-12 text-4xl font-bold uppercase lg:text-5xl text-on-surface" style="letter-spacing:-0.02em;">{{ $course->title }}</h4>

            <div class="w-24 mb-12 bg-on-surface" style="height:3px;"></div>
        </div>

        {{-- Footer --}}
        <div class="relative z-10 flex items-end justify-between px-12 pb-12">
            <div>
                <p class="text-sm font-bold uppercase text-secondary" style="letter-spacing:0.2em;">{{ __('messages.Date of Issue') }}</p>
                <p class="text-xl font-bold uppercase text-on-surface">{{ $completedAt }}</p>
            </div>
            <div class="flex gap-16 ltr:mr-32 rtl:ml-32">
                <div class="text-center">
                    <div class="flex items-end justify-center mb-4" style="height:36px;">
                        @if($instructor)
                            <span style="font-family:'Dancing Script',cursive;font-size:24px;color:var(--color-on-surface);">{{ $instructor->name }}</span>
                        @else
                            <span style="font-family:'Dancing Script',cursive;font-size:24px;color:var(--color-on-surface);">—</span>
                        @endif
                    </div>
                    <p class="text-xs font-bold uppercase text-on-surface" style="letter-spacing:0.1em;">{{ $instructor->name ?? '—' }}</p>
                    <div class="w-48 mx-auto mb-2 bg-on-surface" style="height:1px;"></div>
                    <p class="text-[10px] uppercase text-secondary">{{ __('messages.Instructor') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Download Button --}}
    <div class="fixed flex gap-4 -translate-x-1/2 bottom-8 left-1/2">
        <a href="{{ route('tenant.student.certificate.download', $course) }}"
           class="flex items-center gap-2 px-6 py-3 font-bold text-white uppercase bg-on-surface" style="border:2px solid var(--color-on-surface);box-shadow:4px 4px 0 0 var(--color-primary-container);">
            <i class="fas fa-download"></i>
            {{ __('messages.Download Certificate') }}
        </a>
    </div>
</div>
