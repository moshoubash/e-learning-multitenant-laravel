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
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Title") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Slug") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Instructor") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Price") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Status") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Created At") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Actions") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr class="border-t border-gray-200 hover:bg-gray-50">
                    <td class="p-4">{{ $course->title }}</td>
                    <td class="p-4 text-gray-600">{{ $course->slug }}</td>
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
                    <td class="p-4 text-gray-500">{{ $course->created_at->format('Y-m-d') }}</td>
                    <td class="p-4">
                        <button wire:click="openEditModal({{ $course->id }})" class="text-blue-600 hover:text-blue-800 mr-3"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="openDeleteModal({{ $course->id }})" class="text-red-600 hover:text-red-800"
                            title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $courses->links() }}
    </div>
</div>