<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="flex items-center justify-between">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('Active Courses') }}</h3>
        </div>
        <button wire:click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>
            {{ __('Add Course') }}
        </button>
    </div>

    <table class="w-full text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-4 text-sm font-semibold text-gray-600 w-8"></th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Title") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Instructor") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Price") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Status") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Actions") }}</th>
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
                    <td class="p-4">{{ $course->instructor->name ?? 'N/A' }}</td>
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
                                class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="openDeleteModal({{ $course->id }})" class="text-red-600 hover:text-red-800"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            @if($course->sections && count($course->sections) === 0)
                                <button wire:click="openSectionCreateModal({{ $course->id }})"
                                    class="text-green-600 hover:text-green-800 ml-3" title="Add Section">
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
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-sm font-semibold text-gray-600">{{ __('Sections & Lessons') }}</h4>
                                    <button wire:click="openSectionCreateModal({{ $course->id }})"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-plus mr-1"></i>
                                        {{ __('Add Section') }}
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    @foreach($course->sections->sortBy('order') as $section)
                                        @include('livewire.admin.courses-components.tables.partials.section-row', ['section' => $section, 'courseId' => $course->id])
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $courses->links() }}
    </div>
</div>