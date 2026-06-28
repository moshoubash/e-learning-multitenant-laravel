<div>
    <header class="h-16 flex justify-between items-center px-[24px] bg-surface-container-lowest border-b-2 border-on-surface sticky top-0 z-40">
        <div>
            <h2 class="text-[24px] font-bold text-on-surface leading-none">{{ __('messages.Enrollment Confirmed') }}</h2>
        <p class="text-[12px] font-medium uppercase text-secondary mt-0.5 tracking-wider">Enrollment completed successfully</p>
    </div>
    <div class="flex items-center gap-2">
        @livewire('shared.notification-bell')
    </div>
</header>

    <div class="p-[24px] max-w-[1400px] mx-auto">
        @if($isLoaded && $enrollment)
            <div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
                {{-- Success Icon --}}
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-4 neo-border neo-radius bg-primary-container">
                        <i class="text-4xl text-on-primary-container fas fa-check"></i>
                    </div>
                    <h2 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Congratulations!') }}</h2>
                    <p class="mt-2 text-sm text-secondary">{{ __('messages.You have successfully enrolled in the course.') }}</p>
                </div>

                {{-- Enrollment Details --}}
                <div class="border-t-2 border-on-surface p-[24px]">
                    <h3 class="mb-4 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Enrollment Details') }}</h3>
                    <div class="flex items-start gap-4">
                        @if($enrollment->course && $enrollment->course->thumbnail)
                            <img src="{{ $enrollment->course->thumbnail }}" alt="{{ $enrollment->course->title }}"
                                class="object-cover w-32 h-24 neo-border-sm neo-radius">
                        @else
                            <div class="flex items-center justify-center w-32 h-24 neo-border-sm neo-radius bg-surface-container">
                                <i class="text-2xl text-secondary fas fa-book"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-on-surface">{{ $enrollment->course->title ?? 'N/A' }}</h4>
                            <p class="mt-1 text-sm text-secondary">{{ __('messages.By') }} {{ $enrollment->course->instructor->name ?? 'N/A' }}</p>
                            <div class="mt-4 text-sm">
                                <span class="text-secondary">{{ __('messages.Enrollment Date') }}:</span>
                                <span class="ml-2 font-bold text-on-surface">{{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Next Steps --}}
                <div class="border-t-2 border-on-surface p-[24px] bg-surface-container-low">
                    <h4 class="mb-3 text-xs font-bold tracking-widest uppercase text-on-surface">{{ __('messages.What\'s Next?') }}</h4>
                    <ul class="space-y-2 text-sm text-secondary">
                        <li class="flex items-center">
                            <i class="w-5 mr-2 text-center fas fa-play-circle text-primary-container"></i>
                            {{ __('messages.Start watching lessons and learning at your own pace') }}
                        </li>
                        <li class="flex items-center">
                            <i class="w-5 mr-2 text-center fas fa-tasks text-primary-container"></i>
                            {{ __('messages.Track your progress as you complete lessons') }}
                        </li>
                        <li class="flex items-center">
                            <i class="w-5 mr-2 text-center fas fa-certificate text-primary-container"></i>
                            {{ __('messages.Earn a certificate upon course completion') }}
                        </li>
                    </ul>
                </div>

                {{-- Action Buttons --}}
                <div class="border-t-2 border-on-surface p-[24px]">
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('tenant.student.course', ['course' => $enrollment->course->slug ?? '#']) }}"
                            class="flex-1 px-6 py-3 text-xs font-bold tracking-widest text-center uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                            <i class="ml-2 fas fa-play"></i>
                            {{ __('messages.Start Learning') }}
                        </a>
                        <a href="{{ route('tenant.student.courses') }}"
                            class="flex-1 px-6 py-3 text-xs font-bold tracking-widest text-center uppercase transition-colors neo-border neo-radius bg-surface-container text-on-surface hover:bg-on-surface hover:text-white">
                            <i class="ml-2 fas fa-list"></i>
                            {{ __('messages.Browse More Courses') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="p-12 text-center bg-surface-container-lowest neo-border neo-radius">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-4 neo-border neo-radius bg-error/10">
                    <i class="text-4xl text-error fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="text-sm font-bold tracking-widest uppercase text-on-surface">{{ __('messages.Enrollment Not Found') }}</h2>
                <p class="mt-2 text-sm text-secondary">{{ __('messages.We could not find the enrollment you are looking for.') }}</p>
                <a href="{{ route('tenant.student.courses') }}"
                    class="inline-flex items-center px-6 py-3 mt-4 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
                    <i class="ml-2 fas fa-arrow-left"></i>
                    {{ __('messages.Back to Courses') }}
                </a>
            </div>
        @endif
    </div>
</div>
