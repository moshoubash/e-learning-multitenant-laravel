<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Provider') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Client ID') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Redirect URL') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse ($integrations as $integration)
                    <tr class="transition-colors duration-150 hover:bg-surface-container-low">
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                @if($integration->provider === 'google')
                                    <i class="fab fa-google text-on-surface"></i>
                                @elseif($integration->provider === 'paypal')
                                    <i class="fab fa-paypal text-on-surface"></i>
                                @endif
                                <span class="text-sm font-bold text-on-surface">{{ ucfirst($integration->provider) }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-mono text-sm text-on-surface">{{ $integration->client_id }}</td>
                        <td class="p-4 text-sm text-on-surface">{{ $integration->redirect_url ?: '—' }}</td>
                        <td class="p-4">
                            <button wire:click="toggleActive({{ $integration->id }})"
                                class="px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold transition-colors {{ $integration->is_active ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container text-secondary' }}">
                                {{ $integration->is_active ? __('messages.Active') : __('messages.Inactive') }}
                            </button>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEditModal({{ $integration->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                                    <i class="text-xs fas fa-edit"></i>
                                </button>
                                <button wire:click="openDeleteModal({{ $integration->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                                    <i class="text-xs fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-sm text-center text-secondary">{{ __('messages.No integrations found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $integrations->links() }}
    </div>
</div>
