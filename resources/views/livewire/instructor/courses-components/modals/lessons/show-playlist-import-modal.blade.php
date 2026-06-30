@if($showPlaylistImportModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closePlaylistImportModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">
                        <i class="fab fa-youtube ltr:mr-1 rtl:ml-1 text-[#FF0000]"></i>
                        {{ __('messages.Import YouTube Playlist') }}
                    </h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Playlist URL') }}</label>
                            <input type="url" wire:model.lazy="playlistUrl"
                                placeholder="https://www.youtube.com/playlist?list=..."
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('playlistUrl') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            <p class="mt-1 text-xs text-secondary">{{ __('messages.Paste a YouTube playlist URL to import all videos as lessons') }}</p>
                        </div>

                        @if(empty($playlistVideos))
                            <div class="mb-4">
                                <button wire:click="fetchPlaylist" type="button"
                                    class="w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-primary-container text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors">
                                    <i class="fas fa-cloud-download-alt ltr:mr-2 rtl:ml-2"></i>
                                    {{ __('messages.Fetch Playlist') }}
                                </button>
                            </div>
                        @endif

                        @if(!empty($playlistVideos))
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-on-surface">{{ __('messages.Preview') }} ({{ count($playlistVideos) }} {{ __('messages.videos') }})</span>
                                    <button wire:click="fetchPlaylist" type="button" class="text-xs text-secondary hover:text-on-surface transition-colors">
                                        <i class="fas fa-sync ltr:mr-1 rtl:ml-1"></i> {{ __('messages.Refresh') }}
                                    </button>
                                </div>
                                <div class="max-h-48 overflow-y-auto neo-border-sm neo-radius divide-y divide-on-surface/10 bg-surface-container-low">
                                    @foreach($playlistVideos as $video)
                                        <div class="flex items-center px-3 py-2">
                                            <i class="fab fa-youtube text-[#FF0000] text-xs ltr:mr-2 rtl:ml-2 shrink-0"></i>
                                            <span class="text-xs text-on-surface truncate">{{ $video['title'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Section Title') }}</label>
                                <input type="text" wire:model.lazy="playlistImportSectionTitle"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary"
                                    placeholder="{{ __('messages.Enter section title for the imported lessons') }}">
                                @error('playlistImportSectionTitle') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    @if(!empty($playlistVideos))
                        <button wire:click="importPlaylist" type="button"
                            class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-primary-container text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                            <i class="fas fa-upload ltr:mr-2 rtl:ml-2"></i>
                            {{ __('messages.Import :count Lessons', ['count' => count($playlistVideos)]) }}
                        </button>
                    @endif
                    <button wire:click="closePlaylistImportModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
