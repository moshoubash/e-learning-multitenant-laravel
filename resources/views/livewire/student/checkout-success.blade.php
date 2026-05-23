<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('messages.Enrollment Confirmed') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl px-4 py-6 mx-auto sm:px-6 lg:px-8">
        @if($isLoaded && $enrollment)
            <!-- Success Message -->
            <div class="overflow-hidden bg-white ">
                <div class="px-8 py-2 text-center">
                    <!-- Success Icon -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full">
                            <i class="text-4xl text-green-600 fas fa-check"></i>
                        </div>
                    </div>

                    <h2 class="mb-2 text-2xl font-bold text-gray-800">
                        {{ __('messages.Congratulations!') }}
                    </h2>
                    <p class="mb-6 text-gray-600">
                        {{ __('messages.You have successfully enrolled in the course.') }}
                    </p>
                </div>
            </div>

            <!-- Enrollment Details -->
            <div class="overflow-hidden bg-white">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">{{ __('messages.Enrollment Details') }}</h3>

                    <div class="flex items-start @if(app()->getLocale() === 'ar') gap-4 @else space-x-4 @endif">
                        @if($enrollment->course && $enrollment->course->thumbnail)
                            <img src="{{ Storage::url($enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}"
                                class="object-cover w-32 h-24 rounded-lg">
                        @else
                            <div class="flex items-center justify-center w-32 h-24 bg-gray-200 rounded-lg">
                                <i class="text-2xl text-gray-400 fas fa-book"></i>
                            </div>
                        @endif

                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-800">
                                {{ $enrollment->course->title ?? 'N/A' }}
                            </h4>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('messages.By') }} {{ $enrollment->course->instructor->name ?? 'N/A' }}
                            </p>

                            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                                <div>
                                    <span class="text-gray-500">{{ __('messages.Enrollment Date') }}:</span>
                                    <span class="ml-2 text-gray-800">
                                        {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="p-6 bg-gray-50">
                    <h4 class="mb-3 font-semibold text-gray-800">{{ __('messages.What\'s Next?') }}</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif text-green-500 fas fa-play-circle"></i>
                            {{ __('messages.Start watching lessons and learning at your own pace') }}
                        </li>
                        <li class="flex items-center">
                            <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif text-green-500 fas fa-tasks"></i>
                            {{ __('messages.Track your progress as you complete lessons') }}
                        </li>
                        <li class="flex items-center">
                            <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif text-green-500 fas fa-certificate"></i>
                            {{ __('messages.Earn a certificate upon course completion') }}
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 border-t border-gray-200">
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('tenant.student.course', ['course' => $enrollment->course->slug ?? '#']) }}"
                            class="flex-1 px-6 py-3 font-medium text-center text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="mr-2 fas fa-play"></i>
                            {{ __('messages.Start Learning') }}
                        </a>
                        <a href="{{ route('tenant.student.courses') }}"
                            class="flex-1 px-6 py-3 font-medium text-center text-gray-800 transition-colors bg-gray-200 rounded-lg hover:bg-gray-300">
                            <i class="mr-2 fas fa-list"></i>
                            {{ __('messages.Browse More Courses') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Error State -->
            <div class="overflow-hidden bg-white">
                <div class="p-12 text-center">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full">
                            <i class="text-4xl text-red-600 fas fa-exclamation-triangle"></i>
                        </div>
                    </div>

                    <h2 class="mb-2 text-2xl font-bold text-gray-800">
                        {{ __('messages.Enrollment Not Found') }}
                    </h2>
                    <p class="mb-6 text-gray-600">
                        {{ __('messages.We could not find the enrollment you are looking for.') }}
                    </p>

                    <a href="{{ route('tenant.student.courses') }}"
                        class="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="mr-2 fas fa-arrow-left"></i>
                        {{ __('messages.Back to Courses') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
