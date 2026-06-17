@if($showLessonCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-on-surface/60 transition-opacity" wire:click="closeLessonModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden ltr:text-left rtl:text-right align-bottom transition-all transform bg-surface-container-lowest neo-border neo-radius sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface mb-4">{{ __('messages.Create New Lesson') }}</h3>
                    <form enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Title') }}</label>
                            <input type="text" wire:model.lazy="lessonCreateTitle"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('lessonCreateTitle') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Type') }}</label>
                            <select wire:model.lazy="lessonCreateType"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0">
                                <option value="video">{{ __('messages.Video') }}</option>
                                <option value="text">{{ __('messages.Text') }}</option>
                            </select>
                            @error('lessonCreateType') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Content') }}</label>
                            <div class="mt-2" wire:ignore>
                                <div x-data x-ref="quillEditor" x-init="quill = new Quill($refs.quillEditor, {theme: 'snow'}); quill.on('text-change', function () { $dispatch('input', quill.root.innerHTML); });" wire:model.debounce.2000ms="lessonCreateContent">
                                    {!! $lessonCreateContent !!}
                                </div>
                                @error('lessonCreateContent') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Order') }} <span>({{ __('messages.min order:') }} {{ $maxOrderInLessons + 1 ?? 0 }})</span></label>
                            <input type="number" wire:model.lazy="lessonCreateOrder" min="0"
                                class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-0 placeholder:text-secondary">
                            @error('lessonCreateOrder') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                        @if($lessonCreateType === 'video')
                            <div class="mb-4">
                                <label class="block mb-1 text-xs font-bold uppercase tracking-widest text-on-surface">{{ __('messages.Video') }}</label>
                                <input type="file" wire:model.lazy="courseVideo" accept="video/*"
                                    class="w-full px-3 py-2 neo-border-sm neo-radius bg-surface-container-low text-on-surface text-sm file:neo-border-sm file:neo-radius file:bg-surface-container file:text-on-surface file:text-xs file:font-bold file:uppercase file:tracking-widest file:px-3 file:py-1 file:ltr:mr-3 file:rtl:ml-3 file:cursor-pointer focus:outline-none focus:ring-0">
                                @error('lessonCreateVideo') <span class="text-xs text-error mt-1 block font-bold">{{ $message }}</span> @enderror
                                <div wire:loading wire:target="courseVideo" class="mt-2 text-xs font-bold text-on-surface">
                                    <span>{{ __('messages.Uploading video...') }}</span>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="px-4 py-3 bg-surface-container-low sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="storeLesson" type="button" wire:loading.attr="disabled" wire:target="courseVideo"
                        class="inline-flex justify-center w-full px-4 py-2 neo-border neo-radius bg-primary-container text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-on-surface hover:text-white transition-colors sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ __('messages.Create') }}
                    </button>
                    <button wire:click="closeLessonModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 neo-border-sm neo-radius text-xs font-bold text-on-surface bg-surface-container hover:bg-on-surface hover:text-white transition-colors sm:mt-0 sm:ltr:ml-3 sm:rtl:mr-3 sm:w-auto">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif