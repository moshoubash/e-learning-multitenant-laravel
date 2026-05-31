<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.My Enrolled Courses') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('messages.Continue where you left off') }}</p>
        </div>
    </div>
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif text-green-500 fas fa-graduation-cap"></i>
                    {{ __('messages.My Enrolled Courses') }}
                </h3>
                <a href="{{ route('tenant.student.courses') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    {{ __('messages.Browse More') }} <i class="@if(app()->getLocale() === 'ar') mr-1 @else ml-1 @endif fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        @forelse($enrollments as $enrollment)
            @php
                $progress = $this->getCourseProgress($enrollment);
            @endphp
            <div class="p-6 border-b border-gray-100 hover:bg-gray-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800">{{ $enrollment->course->title }}</h4>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $enrollment->course->instructor->name ?? 'N/A' }}
                        </p>

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gray-500">
                                    {{ $progress['completed_lessons'] }} / {{ $progress['total_lessons'] }} {{ __('messages.lessons completed') }}
                                </span>
                                <span class="text-xs font-medium text-gray-700">
                                    {{ $progress['progress_percent'] }}%
                                </span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 transition-all duration-300 bg-green-500 rounded-full"
                                    style="width: {{ $progress['progress_percent'] }}%"></div>
                            </div>
                        </div>

                        <!-- Current Checkpoint -->
                        @if($progress['current_lesson'])
                            <div class="flex items-center mt-3 text-sm">
                                <span class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif text-gray-500">{{ __('messages.Current') }}:</span>
                                <span class="inline-flex items-center px-2 py-1 text-blue-700 bg-blue-100 rounded">
                                    <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif text-blue-500 fas fa-play-circle"></i>
                                    {{ $progress['current_lesson']->title }}
                                </span>
                            </div>
                        @elseif($progress['progress_percent'] == 100)
                            <div class="flex items-center mt-3 text-sm">
                                <span class="inline-flex items-center px-2 py-1 text-green-700 bg-green-100 rounded">
                                    <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-check-circle"></i>
                                    {{ __('messages.Course Completed!') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="ml-4">
                        <a href="{{ route('tenant.student.course', $enrollment->course->slug) }}"
                            class="inline-flex items-center px-3 py-2 text-sm text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-play"></i>
                            {{ __('messages.Continue') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <i class="mb-4 text-6xl text-gray-300 fas fa-graduation-cap"></i>

                <h3 class="text-lg font-medium text-center text-gray-700">{{ __('messages.No Enrolled Courses') }}</h3>
                <p class="mt-2 text-center text-gray-500">{{ __('messages.Start learning by enrolling in a course.') }}</p>

                <a href="{{ route('tenant.student.courses') }}"
                    class="inline-flex items-center px-4 py-2 mt-4 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="mr-2 fas fa-search"></i>
                    {{ __('messages.Browse Courses') }}
                </a>
            </div>
        @endforelse
    </div>
</div>
