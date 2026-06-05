<div class="p-6 mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="p-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.All Quizzes') }}</h3>
    </div>

    <table class="w-full mx-0 mt-4 text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Title") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Course / Section") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Questions") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Pass %") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Actions") }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($quizzes as $quiz)
                <tr class="border-t border-gray-200 hover:bg-gray-50">
                    <td class="p-4 font-medium text-gray-900">{{ $quiz->title }}</td>
                    <td class="p-4">
                        <div class="text-gray-700">{{ $quiz->section?->course?->title ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $quiz->section?->title ?? 'N/A' }}</div>
                    </td>
                    <td class="p-4 text-gray-700">{{ $quiz->questions->count() }}</td>
                    <td class="p-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $quiz->pass_percentage }}%
                        </span>
                    </td>
                    <td class="p-4">
                        <button wire:click="openEditQuizModal({{ $quiz->id }})"
                            class="@rim('mr-3') text-blue-600 hover:text-blue-800" title="Edit Quiz">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="openAttemptsModal({{ $quiz->id }})"
                            class="@rim('mr-3') text-green-600 hover:text-green-800" title="View Attempts">
                            <i class="fas fa-list"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        No quizzes found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $quizzes->links() }}
    </div>
</div>
