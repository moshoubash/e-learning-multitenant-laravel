<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Courses') }}
        </h2>
    </div>
</x-slot>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Course List Sidebar -->
        <div class="lg:col-span-1">
            <!-- Enrolled Courses Section -->
            @php
                $enrolledCourses = \App\Models\Tenant\Enrollment::where('user_id', auth()->id())
                    ->with('course.instructor', 'course.sections.lessons')
                    ->get()
                    ->filter(fn($e) => $e->course && $e->course->status === 'published')
                    ->pluck('course');
            @endphp

            @if($enrolledCourses->count() > 0)
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4 border-b border-green-200">
                        <h3 class="text-lg font-semibold text-green-700">
                            <i class="fas fa-graduation-cap mr-2"></i>{{ __('My Enrolled Courses') }}
                        </h3>
                    </div>
                    <div class="divide-y divide-green-100">
                        @foreach($enrolledCourses as $course)
                            <div wire:click="selectCourse({{ $course->id }})"
                                class="p-4 hover:bg-green-100 cursor-pointer {{ $selectedCourse == $course->id ? 'bg-green-100 border-l-4 border-green-500' : '' }}">
                                <h4 class="font-medium text-gray-800">{{ $course->title }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $course->instructor->name ?? 'N/A' }}</p>
                                <div class="flex items-center mt-2 text-xs text-gray-400">
                                    <span class="mr-3">
                                        <i class="fas fa-folder mr-1"></i> {{ $course->sections->count() }} sections
                                    </span>
                                    <span>
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} lessons
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Available Courses Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">{{ __('Available Courses') }}</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($courses as $course)
                        <div wire:click="selectCourse({{ $course->id }})"
                            class="p-4 hover:bg-gray-50 cursor-pointer {{ $selectedCourse == $course->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                            <h4 class="font-medium text-gray-800">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-500 mt-1">{{ $course->instructor->name ?? 'N/A' }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <span class="mr-3">
                                    <i class="fas fa-folder mr-1"></i> {{ $course->sections->count() }} sections
                                </span>
                                <span>
                                    <i class="fas fa-book mr-1"></i>
                                    {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} lessons
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-book-open text-4xl mb-2"></i>
                            <p>No courses available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Course Content Area -->
        <div class="lg:col-span-2">
            @if($selectedCourse)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Course Header -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $selectedCourseData->title }}</h3>
                        <p class="text-gray-500 mt-2">By {{ $selectedCourseData->instructor->name ?? 'N/A' }}</p>
                        <div class="mt-4 flex items-center text-sm text-gray-500">
                            <span class="mr-4">
                                <i class="fas fa-dollar-sign mr-1"></i> {{ number_format($selectedCourseData->price, 2) }}
                            </span>
                            <span class="mr-4">
                                <i class="fas fa-folder mr-1"></i> {{ $selectedCourseData->sections->count() }} sections
                            </span>
                        </div>
                        @if($selectedCourseData->description)
                            <p class="mt-4 text-gray-600">{{ $selectedCourseData->description }}</p>
                        @endif

                        <!-- Enroll Button -->
                        @if(!$this->isEnrolled($selectedCourseData->id))
                            <div class="mt-6">
                                <button wire:click="enrollInCourse({{ $selectedCourseData->id }})"
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-graduation-cap mr-2"></i>
                                    @if($selectedCourseData->price == 0)
                                        {{ __('Enroll for Free') }}
                                    @else
                                        {{ __('Enroll Now') }} - ${{ number_format($selectedCourseData->price, 2) }}
                                    @endif
                                </button>
                            </div>
                        @else
                            <div class="mt-6">
                                <span
                                    class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg font-medium">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ __('Enrolled') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Curriculum -->
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4">{{ __('Course Curriculum') }}</h4>

                        @forelse($selectedCourseData->sections->sortBy('order') as $section)
                            <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Section Header -->
                                <div wire:click="toggleSection({{ $section->id }})"
                                    class="flex items-center justify-between p-4 bg-gray-100 hover:bg-gray-50 cursor-pointer">
                                    <div class="flex items-center">
                                        <i
                                            class="fas fa-chevron-right mr-3 text-gray-400 transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                        <span class="font-medium text-gray-800">{{ $section->title }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ $section->lessons->count() }} lessons
                                    </span>
                                </div>

                                <!-- Lessons List -->
                                @if($this->isSectionExpanded($section->id))
                                    <div class="divide-y divide-gray-100">
                                        @forelse($section->lessons->sortBy('order') as $lesson)
                                            <div class="p-4 hover:bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <i
                                                            class="fas @if($lesson->type === 'video') fa-play-circle text-blue-500 
                                                            @elseif($lesson->type === 'text') fa-file
                                                                                        @elseif($lesson->type === 'quiz') fa-list-check text-purple-500 @else fa-file-text text-gray-400 @endif mr-3"></i>
                                                        <span class="text-gray-700">{{ $lesson->title }}</span>
                                                        @if($lesson->type === 'quiz' && $lesson->quiz)
                                                            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">
                                                                Quiz ({{ $lesson->quiz->questions->count() }} questions)
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-400">
                                                        @if($lesson->duration_seconds)
                                                            {{ gmdate('i:s', $lesson->duration_seconds) }}
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($lesson->type === 'quiz' && $lesson->quiz)
                                                    <div class="mt-2 ml-8 text-sm text-gray-500">
                                                        <span>Pass: {{ $lesson->quiz->pass_percentage }}%</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="p-4 text-center text-gray-500 text-sm">
                                                No lessons in this section yet.
                                            </div>
                                        @endforelse

                                        <!-- Quiz if exist -->
                                        @if($section->quiz)
                                            <div class="p-4 bg-purple-50 border-t border-purple-100">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-question-circle text-purple-500 mr-3"></i>
                                                        <span class="text-purple-700 font-medium">{{ $section->quiz->title }}</span>
                                                        <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">
                                                            {{ $section->quiz->questions->count() }} questions
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-purple-500">
                                                        Pass: {{ $section->quiz->pass_percentage }}%
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-folder-open text-4xl mb-2"></i>
                                <p>No sections available yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-700">{{ __('Select a Course') }}</h3>
                        <p class="text-gray-500 mt-2">Choose a course from the list to view its curriculum.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>