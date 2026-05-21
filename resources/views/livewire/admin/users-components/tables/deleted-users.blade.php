@if($deletedUsers->count() > 0)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 border-b border-gray-200 bg-gray-100">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.Deleted Users') }}</h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Name") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Email") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Deleted At") }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-600">{{ __("Actions") }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deletedUsers as $user)
                    <tr class="border-t border-gray-200 hover:bg-gray-50 bg-gray-50 text-gray-500">
                        <td class="p-4">{{ $user->name }}</td>
                        <td class="p-4">{{ $user->email }}</td>
                        <td class="p-4">{{ $user->deleted_at->format('Y-m-d H:i') }}</td>
                        <td class="p-4">
                            <button wire:click="openRestoreModal({{ $user->id }})" class="text-green-600 hover:text-green-800"
                                title="Restore">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
