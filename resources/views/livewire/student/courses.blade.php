<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('messages.Browse Courses') }}
        </h2>
    </div>
</x-slot>

<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
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
                <div class="mb-4 overflow-hidden shadow-sm bg-green-50 sm:rounded-lg">
                    <div class="p-4 border-b border-green-200">
                        <h3 class="text-lg font-semibold text-green-700">
                            <i class="fas fa-graduation-cap @if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif"></i>{{ __('messages.My Enrolled Courses') }}
                        </h3>
                    </div>
                    <div class="divide-y divide-green-100">
                        @foreach($enrolledCourses as $course)
                            <div wire:click="selectCourse({{ $course->id }})"
                                class="p-4 hover:bg-green-100 cursor-pointer {{ $selectedCourse == $course->id ? 'bg-green-100 border-l-4 border-green-500' : '' }}">
                                <h4 class="font-medium text-gray-800">{{ $course->title }}</h4>
                                <p class="mt-1 text-sm text-gray-500">{{ $course->instructor->name ?? 'N/A' }}</p>
                                <div class="flex items-center mt-2 text-xs text-gray-400">
                                    <span class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif">
                                        <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-folder"></i>
                                        {{ __('messages.sections') }}
                                         {{ $course->sections->count() }}
                                    </span>
                                    <span>
                                        <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-book"></i>
                                        {{ __('messages.lessons') }}
                                        {{ $course->sections->sum(fn($s) => $s->lessons->count()) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Available Courses Section -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.Available Courses') }}</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($courses as $course)
                        <div wire:click="selectCourse({{ $course->id }})"
                            class="p-4 hover:bg-gray-50 cursor-pointer {{ $selectedCourse == $course->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                            <h4 class="font-medium text-gray-800">{{ $course->title }}</h4>
                            <p class="mt-1 text-sm text-gray-500">{{ $course->instructor->name ?? 'N/A' }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <span class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif">
                                    <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-folder"></i>
                                    {{ __('messages.sections') }}
                                    {{ $course->sections->count() }}
                                </span>
                                <span>
                                    <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-book"></i>
                                    {{ __('messages.lessons') }}
                                    {{ $course->sections->sum(fn($s) => $s->lessons->count()) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            <i class="mb-2 text-4xl fas fa-book-open"></i>
                            <p>No courses available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Course Content Area -->
        <div class="lg:col-span-2">
            @if($selectedCourse)
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <!-- Course Header -->
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-800">{{ $selectedCourseData->title }}</h3>
                        <p class="mt-2 text-gray-500">{{ __('messages.By') }} {{ $selectedCourseData->instructor->name ?? 'N/A' }}</p>
                        <div class="flex items-center mt-4 text-sm text-gray-500">
                            <span class="@if(app()->getLocale() === 'ar') ml-4 @else mr-4 @endif">
                                <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-dollar-sign"></i> {{ number_format($selectedCourseData->price, 2) }}
                            </span>
                            <span class="@if(app()->getLocale() === 'ar') ml-4 @else mr-4 @endif">
                                <i class="@if(app()->getLocale() === 'ar') ml-1 @else mr-1 @endif fas fa-folder"></i>
                                {{ __('messages.sections') }}
                                {{ $selectedCourseData->sections->count() }}
                            </span>
                        </div>
                        @if($selectedCourseData->description)
                            <p class="mt-4 text-gray-600">{{ $selectedCourseData->description }}</p>
                        @endif

                        <!-- Enroll Button -->
                        @if(!$this->isEnrolled($selectedCourseData->id))
                            <div class="mt-6">
                                <button wire:click="enrollInCourse({{ $selectedCourseData->id }})"
                                    class="px-6 py-3 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                    <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-graduation-cap"></i>
                                    @if($selectedCourseData->price == 0)
                                        {{ __('messages.Enroll for Free') }}
                                    @else
                                        {{ __('messages.Enroll Now') }} - ${{ number_format($selectedCourseData->price, 2) }}
                                    @endif
                                </button>
                            </div>
                        @else
                            <div class="mt-6">
                                <span
                                    class="inline-flex items-center px-4 py-2 font-medium text-green-700 bg-green-100 rounded-lg">
                                    <i class="@if(app()->getLocale() === 'ar') ml-2 @else mr-2 @endif fas fa-check-circle"></i>
                                    {{ __('messages.Enrolled') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Curriculum -->
                    <div class="p-6">
                        <h4 class="mb-4 text-lg font-semibold text-gray-700">{{ __('messages.Course Curriculum') }}</h4>

                        @forelse($selectedCourseData->sections->sortBy('order') as $section)
                            <div class="mb-4 overflow-hidden border border-gray-200 rounded-lg">
                                <!-- Section Header -->
                                <div wire:click="toggleSection({{ $section->id }})"
                                    class="flex items-center justify-between p-4 bg-gray-100 cursor-pointer hover:bg-gray-50">
                                    <div class="flex items-center">
                                        @if(app()->getLocale() === 'ar')
                                            <i class="fas fa-chevron-left @if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-gray-400 transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                        @else
                                            <i class="fas fa-chevron-right @if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-gray-400 transition-transform {{ $this->isSectionExpanded($section->id) ? 'rotate-90' : '' }}"></i>
                                        @endif

                                        <span class="font-medium text-gray-800">{{ $section->title }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ __('messages.lessons') }}
                                        {{ $section->lessons->count() }}
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
                                                                                        @elseif($lesson->type === 'quiz') fa-list-check text-purple-500 @else fa-file-text text-gray-400 @endif @if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif"></i>
                                                        <span class="text-gray-700">{{ $lesson->title }}</span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-400">
                                                        @if($lesson->duration_seconds)
                                                            {{ gmdate('i:s', $lesson->duration_seconds) }}
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        @empty
                                            <div class="p-4 text-sm text-center text-gray-500">
                                                No lessons in this section yet.
                                            </div>
                                        @endforelse

                                        <!-- Quiz if exist -->
                                        @if($section->quiz)
                                            <div class="p-4 border-t border-purple-100 bg-purple-50">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <i class="@if(app()->getLocale() === 'ar') ml-3 @else mr-3 @endif text-purple-500 fas fa-question-circle"></i>
                                                        <span class="font-medium text-purple-700">{{ $section->quiz->title }}</span>
                                                        <span class="@if(app()->getLocale() === 'ar') mr-2 @else ml-2 @endif px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">
                                                            {{ __('messages.questions') }}
                                                            {{ $section->quiz->questions->count() }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-purple-500">
                                                        {{ __('messages.Pass') }}: {{ $section->quiz->pass_percentage }}%
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500">
                                <i class="mb-2 text-4xl fas fa-folder-open"></i>
                                <p class="text-center">{{ __('messages.No sections available yet.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <i class="mb-4 text-6xl text-gray-300 fas fa-book-open"></i>
                        <h3 class="text-lg font-medium text-gray-700">{{ __('messages.Select a Course') }}</h3>
                        <p class="mt-2 text-gray-500">Choose a course from the list to view its curriculum.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
