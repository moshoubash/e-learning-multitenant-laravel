<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                {{ __('My Enrolled Courses') }}
            </h3>
            <a href="{{ route('tenant.student.courses') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                {{ __('Browse More') }} <i class="fas fa-arrow-right ml-1"></i>
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
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $enrollment->course->instructor->name ?? 'N/A' }}
                    </p>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-500">
                                {{ $progress['completed_lessons'] }} / {{ $progress['total_lessons'] }} lessons completed
                            </span>
                            <span class="text-xs font-medium text-gray-700">
                                {{ $progress['progress_percent'] }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                style="width: {{ $progress['progress_percent'] }}%"></div>
                        </div>
                    </div>

                    <!-- Current Checkpoint -->
                    @if($progress['current_lesson'])
                        <div class="mt-3 flex items-center text-sm">
                            <span class="text-gray-500 mr-2">Current:</span>
                            <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                <i class="fas fa-play-circle mr-1 text-blue-500"></i>
                                {{ $progress['current_lesson']->title }}
                            </span>
                        </div>
                    @elseif($progress['progress_percent'] == 100)
                        <div class="mt-3 flex items-center text-sm">
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded">
                                <i class="fas fa-check-circle mr-1"></i>
                                Course Completed!
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="ml-4">
                    <a href="{{ route('tenant.student.course', $enrollment->course->slug) }}"
                        class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                        <i class="fas fa-play mr-2"></i>
                        {{ __('Continue') }}
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="p-12 text-center">
            <i class="fas fa-graduation-cap text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700">{{ __('No Enrolled Courses') }}</h3>
            <p class="text-gray-500 mt-2">{{ __('Start learning by enrolling in a course.') }}</p>
            <a href="{{ route('tenant.student.courses') }}"
                class="inline-flex items-center mt-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>
                {{ __('Browse Courses') }}
            </a>
        </div>
    @endforelse
</div>