<div class="p-6 mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="flex items-center justify-between">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.All Courses') }}</h3>
            <p class="text-sm text-gray-500">
                {{ __('messages.Note: Only instructors can add sections, lessons, and quizzes to courses.') }}
            </p>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="@rim('mr-2') fas fa-plus"></i>
            {{ __('messages.Add Course') }}
        </button>
    </div>

    <table class="w-full mx-0 mt-4 text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Title") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Instructor") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Price") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Status") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Created At") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Actions") }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($courses as $course)
                <tr class="border-t border-gray-200 hover:bg-gray-50">
                    <td class="p-4">
                        <div class="font-medium text-gray-900">{{ $course->title }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($course->description, 50) }}</div>
                    </td>
                    <td class="p-4">
                        <span class="text-gray-700">{{ $course->instructor->name ?? 'N/A' }}</span>
                    </td>
                    <td class="p-4 text-gray-700">${{ number_format($course->price, 2) }}</td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($course->status === 'published') bg-green-100 text-green-800
                                            @elseif($course->status === 'draft') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                            {{ __('messages.' . ucfirst($course->status)) }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-500">
                        {{ app()->getLocale() === 'ar' ? $course->created_at->isoFormat('dddd, D MMMM YYYY') : $course->created_at->translatedFormat('Y-m-d') }}
                    </td>

                    <td class="p-4">
                        <button wire:click="openEditModal({{ $course->id }})" class="@rim('mr-3') text-blue-600 hover:text-blue-800"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="openDeleteModal({{ $course->id }})" class="text-red-600 hover:text-red-800"
                            title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        No courses found. Click "Add Course" to create one.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $courses->links() }}
    </div>
</div>
