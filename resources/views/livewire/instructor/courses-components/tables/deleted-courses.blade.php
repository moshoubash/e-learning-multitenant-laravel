@if($deletedCourses->count() > 0)
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-4 bg-gray-100 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.Deleted Courses') }}</h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Title") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Deleted At") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Actions") }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deletedCourses as $course)
                    <tr class="text-gray-500 border-t border-gray-200 hover:bg-gray-50 bg-gray-50">
                        <td class="p-4">{{ $course->title }}</td>
                        <td class="p-4">
                            {{ app()->getLocale() === 'ar' ?
                            $course->deleted_at->isoFormat('dddd, D MMMM YYYY') :
                            $course->deleted_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="p-4">
                            <button wire:click="openRestoreModal({{ $course->id }})" class="text-green-600 hover:text-green-800"
                                title="Restore">
                                <i class="fas fa-undo"></i> {{ __('messages.Restore') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
