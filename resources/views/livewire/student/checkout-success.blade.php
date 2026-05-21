<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('messages.Enrollment Confirmed') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if($isLoaded && $enrollment)
            <!-- Success Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 text-center">
                    <!-- Success Icon -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full">
                            <i class="fas fa-check text-green-600 text-4xl"></i>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ __('messages.Congratulations!') }}
                    </h2>
                    <p class="text-gray-600 mb-6">
                        {{ __('messages.You have successfully enrolled in the course.') }}
                    </p>
                </div>
            </div>

            <!-- Enrollment Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.Enrollment Details') }}</h3>

                    <div class="flex items-start space-x-4">
                        @if($enrollment->course && $enrollment->course->thumbnail)
                            <img src="{{ Storage::url($enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->title }}"
                                class="w-32 h-24 object-cover rounded-lg">
                        @else
                            <div class="w-32 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-gray-400 text-2xl"></i>
                            </div>
                        @endif

                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 text-lg">
                                {{ $enrollment->course->title ?? 'N/A' }}
                            </h4>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('messages.By') }} {{ $enrollment->course->instructor->name ?? 'N/A' }}
                            </p>

                            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">{{ __('messages.Enrollment Date') }}:</span>
                                    <span class="ml-2 text-gray-800">
                                        {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">{{ __('messages.Status') }}:</span>
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                        {{ ucfirst($enrollment->status ?? 'active') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="p-6 bg-gray-50">
                    <h4 class="font-semibold text-gray-800 mb-3">{{ __('messages.What\'s Next?') }}</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-play-circle text-green-500 mr-2"></i>
                            {{ __('messages.Start watching lessons and learning at your own pace') }}
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-tasks text-green-500 mr-2"></i>
                            {{ __('messages.Track your progress as you complete lessons') }}
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-certificate text-green-500 mr-2"></i>
                            {{ __('messages.Earn a certificate upon course completion') }}
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('tenant.student.course', ['course' => $enrollment->course->slug ?? '#']) }}"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-play mr-2"></i>
                            {{ __('messages.Start Learning') }}
                        </a>
                        <a href="{{ route('tenant.student.courses') }}"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 text-center px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            {{ __('messages.Browse More Courses') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Error State -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-600 text-4xl"></i>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ __('messages.Enrollment Not Found') }}
                    </h2>
                    <p class="text-gray-600 mb-6">
                        {{ __('messages.We could not find the enrollment you are looking for.') }}
                    </p>

                    <a href="{{ route('tenant.student.courses') }}"
                        class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('messages.Back to Courses') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
