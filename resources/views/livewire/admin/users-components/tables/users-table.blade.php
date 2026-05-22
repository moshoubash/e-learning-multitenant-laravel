<div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="flex items-center justify-between">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700">{{ __('messages.Active Users') }}</h3>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="mr-2 fas fa-plus"></i>
            {{ __('messages.Add User') }}
        </button>
    </div>

    <table class="w-full text-left">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Name") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Email") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Role") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Created At") }}</th>
                <th class="p-4 text-sm font-semibold text-gray-600">{{ __("messages.Actions") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-t border-gray-200 hover:bg-gray-50">
                    <td class="p-4">{{ $user->name }}</td>
                    <td class="p-4 text-gray-600">{{ $user->email }}</td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                        @if($user->hasRole('admin')) bg-purple-100 text-purple-800
                                                                        @elseif($user->hasRole('instructor')) bg-blue-100 text-blue-800
                                                                        @else bg-green-100 text-green-800 @endif">
                            {{ __('messages.'.ucfirst($user->getRoleNames()->first() ?? 'student')) }}
                        </span>
                    </td>

                    <td class="p-4 text-gray-500">
                        {{ app()->getLocale() === 'ar' ? $user->created_at->isoFormat('dddd, D MMMM YYYY') : $user->created_at->translatedFormat('Y-m-d') }}
                    </td>



                    <td class="p-4">
                        <button wire:click="openEditModal({{ $user->id }})" class="mr-3 text-blue-600 hover:text-blue-800"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="openDeleteModal({{ $user->id }})" class="text-red-600 hover:text-red-800"
                            title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $users->links() }}
    </div>
</div>
