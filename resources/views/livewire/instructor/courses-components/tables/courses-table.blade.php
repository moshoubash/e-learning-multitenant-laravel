<div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="flex items-center justify-between">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.Active Courses') }}</h3>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="mr-2 fas fa-plus"></i>
            {{ __('messages.Add Course') }}
        </button>
    </div>

    <table class="w-full text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="w-8 p-4 text-sm font-semibold text-gray-600"></th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Title") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Price") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Status") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Actions") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr class="border-t border-gray-200">
                    <td class="p-2 text-center">
                        <button wire:click="toggleCourseExpand({{ $course->id }})"
                            class="text-gray-500 hover:text-gray-700">
                            <i
                                class="fas {{ $course->sections && count($course->sections) > 0 ? ($expandedCourses && in_array($course->id, $expandedCourses) ? 'fa-chevron-down' : 'fa-chevron-right') : 'fa-minus' }}"></i>
                        </button>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $course->title }}</span>
                            @if($course->sections && count($course->sections) > 0)
                                <span class="ml-2 text-xs text-gray-500">({{ count($course->sections) }} sections)</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">${{ number_format($course->price, 2) }}</td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($course->status === 'published') bg-green-100 text-green-800
                                                        @elseif($course->status === 'archived') bg-gray-100 text-gray-800
                                                        @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($course->status) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center">
                            <button wire:click="openEditModal({{ $course->id }})"
                                class="mr-3 text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="openDeleteModal({{ $course->id }})" class="text-red-600 hover:text-red-800"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            @if($course->sections && count($course->sections) === 0)
                                <button wire:click="openSectionCreateModal({{ $course->id }})"
                                    class="ml-3 text-green-600 hover:text-green-800" title="Add Section">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                {{-- Curriculum (Expandable Sections & Lessons) --}}
                @if($expandedCourses && in_array($course->id, $expandedCourses) && $course->sections && count($course->sections) > 0)
                    <tr>
                        <td colspan="6" class="p-0 bg-gray-50">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-600">{{ __('messages.Sections & Lessons') }}</h4>
                                    <button wire:click="openSectionCreateModal({{ $course->id }})"
                                        class="px-3 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                        <i class="mr-1 fas fa-plus"></i>
                                        {{ __('messages.Add Section') }}
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    @foreach($course->sections->sortBy('order') as $section)
                                        @include('livewire.instructor.courses-components.tables.partials.section-row', ['section' => $section, 'courseId' => $course->id])
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif

            @endforeach
            @if(count($courses) == 0)
                <td colspan="6" class="p-4 text-center text-gray-500">{{ __('messages.No courses found.') }}</td>
            @endif
        </tbody>
    </table>
    <div class="p-4">
        {{ $courses->links() }}
    </div>
</div>
