@if($deletedUsers->count() > 0)
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-4 bg-gray-100 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.Deleted Users') }}</h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Name") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Email") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Deleted At") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Actions") }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deletedUsers as $user)
                    <tr class="text-gray-500 border-t border-gray-200 hover:bg-gray-50 bg-gray-50">
                        <td class="p-4">{{ $user->name }}</td>
                        <td class="p-4">{{ $user->email }}</td>
                        <td class="p-4">
                             {{ app()->getLocale() === 'ar' ?
                            $user->deleted_at->isoFormat('dddd, D MMMM YYYY') :
                            $user->deleted_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="p-4">
                            <button wire:click="openRestoreModal({{ $user->id }})" class="text-green-600 hover:text-green-800"
                                title="Restore">
                                <i class="@rim('mr-1') fas fa-undo"></i> {{ __('messages.Restore') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
