@if($showLessonCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeLessonModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">{{ __('messages.Create New Lesson') }}</h3>
                    <form enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Title') }}</label>
                            <input type="text" wire:model.lazy="lessonCreateTitle"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonCreateTitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Type') }}</label>
                            <select wire:model.lazy="lessonCreateType"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="video">{{ __('messages.Video') }}</option>
                                <option value="text">{{ __('messages.Text') }}</option>
                            </select>
                            @error('lessonCreateType') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Content') }}</label>
                            <div class="mt-2 bg-white" wire:ignore>
                                <div x-data x-ref="quillEditor" x-init="
                                                                            quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                                                            quill.on('text-change', function () {
                                                                            $dispatch('input', quill.root.innerHTML);
                                                                            });
                                                                        " wire:model.debounce.2000ms="lessonCreateContent">

                                    {!! $lessonCreateContent !!}
                                </div>
                                @error('lessonCreateContent') <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Order') }} <span>
                                ({{ __('messages.min order:') }} {{ $maxOrderInLessons + 1 ?? 0 }}) </span></label>
                            <input type="number" wire:model.lazy="lessonCreateOrder" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonCreateOrder') <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($lessonCreateType === 'video')
                            <div class="mb-4">
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Video') }}</label>
                                <input type="file" wire:model.lazy="courseVideo" accept="video/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('lessonCreateVideo') <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                                <div wire:loading wire:target="courseVideo" class="mt-2 text-sm text-blue-600">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <svg class="w-5 h-5 text-blue-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>{{ __('messages.Uploading video...') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="storeLesson" type="button" wire:loading.attr="disabled" wire:target="courseVideo"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ __('messages.Create') }}
                    </button>
                    <button wire:click="closeLessonModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
