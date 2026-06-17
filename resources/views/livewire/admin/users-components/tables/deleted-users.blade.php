@if($deletedUsers->count() > 0)
    <div class="bg-surface-container-lowest neo-border neo-radius overflow-hidden">
        <div class="p-[24px] border-b-2 border-on-surface bg-surface-container-low">
            <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Deleted Users') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full ltr:text-left rtl:text-right">
                <thead class="bg-surface-container border-b-2 border-on-surface">
                    <tr>
                        <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Name') }}</th>
                        <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Email') }}</th>
                        <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Deleted At') }}</th>
                        <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E5E5]">
                    @foreach ($deletedUsers as $user)
                        <tr class="bg-surface-container-low">
                            <td class="p-4 text-sm text-on-surface font-bold">{{ $user->name }}</td>
                            <td class="p-4 text-sm text-secondary">{{ $user->email }}</td>
                            <td class="p-4 text-sm text-secondary">
                                {{ app()->getLocale() === 'ar' ?
                                $user->deleted_at->isoFormat('dddd, D MMMM YYYY') :
                                $user->deleted_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-4">
                                <button wire:click="openRestoreModal({{ $user->id }})"
                                    class="inline-flex items-center px-3 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-surface hover:bg-on-surface hover:text-white transition-colors">
                                    <i class="fas fa-undo ltr:mr-1 rtl:ml-1"></i> {{ __('messages.Restore') }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
