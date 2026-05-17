@if($showLessonCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeLessonModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Create New Lesson') }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Title') }}</label>
                            <input type="text" wire:model.lazy="lessonCreateTitle"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonCreateTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type') }}</label>
                            <select wire:model.lazy="lessonCreateType"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="video">Video</option>
                                <option value="text">Text</option>
                            </select>
                            @error('lessonCreateType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Content') }}</label>
                            <div class="mt-2 bg-white" wire:ignore>
                                <div x-data x-ref="quillEditor" x-init="
                                                    quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                                    quill.on('text-change', function () {
                                                    $dispatch('input', quill.root.innerHTML);
                                                    });
                                                " wire:model.debounce.2000ms="lessonCreateContent">

                                    {!! $lessonCreateContent !!}
                                </div>
                                @error('lessonCreateContent') <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Duration (seconds)') }}</label>
                            <input type="number" wire:model.lazy="lessonCreateDuration" min="0"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonCreateDuration') <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Order') }}</label>
                            <input type="number" wire:model.lazy="lessonCreateOrder" min="0"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonCreateOrder') <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4" x-show="$wire.lessonCreateType === 'video'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Video URL') }}</label>
                            <input type="url" wire:model.lazy="lessonCreateVideoUrl"
                                placeholder="e.g., https://example.com/video.mp4"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Enter the direct video URL (MP4, WebM, etc.)</p>
                            @error('lessonCreateVideoUrl') <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="storeLesson" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Create') }}
                    </button>
                    <button wire:click="closeLessonModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif