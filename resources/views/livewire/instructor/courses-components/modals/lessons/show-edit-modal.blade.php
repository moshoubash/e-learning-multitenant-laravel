@if($showLessonEditModal && $editingLesson)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeLessonModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">{{ __('messages.Edit Lesson') }}: {{ $editingLesson->title }}
                    </h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Title') }}</label>
                            <input type="text" wire:model.lazy="lessonEditTitle"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonEditTitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Type') }}</label>
                            <select wire:model.lazy="lessonEditType"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="video" {{ $editingLesson->type === 'video' ? 'selected' : '' }}>{{ __('messages.Video') }}</option>
                                <option value="text" {{ $editingLesson->type === 'text' ? 'selected' : '' }}>{{ __('messages.Text') }}</option>
                                <option value="quiz" {{ $editingLesson->type === 'quiz' ? 'selected' : '' }}>{{ __('messages.Quiz') }}</option>
                            </select>
                            @error('lessonEditType') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Content') }}</label>
                            <div class="mt-2 bg-white" wire:ignore>
                                <div x-data x-ref="quillEditor" x-init="
                                                        quill = new Quill($refs.quillEditor, {theme: 'snow'});
                                                        quill.on('text-change', function () {
                                                        $dispatch('input', quill.root.innerHTML);
                                                        });
                                                    " wire:model.debounce.2000ms="lessonEditContent">

                                    {!! $lessonEditContent !!}
                                </div>
                                @error('lessonEditContent') <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label
                                class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Duration (seconds)') }}</label>
                            <input type="number" wire:model.lazy="lessonEditDuration" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonEditDuration') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Order') }}</label>
                            <input type="number" wire:model.lazy="lessonEditOrder" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lessonEditOrder') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4" x-show="$wire.lessonEditType === 'video'">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Video URL') }}</label>
                            <input type="url" wire:model.lazy="lessonEditVideoUrl"
                                placeholder="e.g., https://example.com/video.mp4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Enter the direct video URL (MP4, WebM, etc.)</p>
                            @error('lessonEditVideoUrl') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="updateLesson" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.Update') }}
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
