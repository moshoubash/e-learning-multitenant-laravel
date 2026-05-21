@if($showEditQuizModal && $editingQuiz)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeModals"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.Edit Quiz') }}: {{ $editingQuiz->title }}</h3>

                    <!-- Quiz Details Form -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">{{ __('messages.Quiz Details') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.Title') }}</label>
                                <input type="text" wire:model.lazy="editTitle"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('editTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.Section') }}</label>
                                <select wire:model.lazy="editSectionId"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->course?->title }} - {{ $section->title }}</option>
                                    @endforeach
                                </select>
                                @error('editSectionId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.Pass Percentage') }}</label>
                                <input type="number" wire:model.lazy="editPassPercentage" min="1" max="100"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('editPassPercentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button wire:click="updateQuiz"
                            class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            {{ __('messages.Update Quiz') }}
                        </button>
                    </div>

                    <!-- Questions Section -->
                    <div class="mb-4 flex justify-between items-center">
                        <h4 class="font-semibold text-gray-700">{{ __('messages.Questions') }} ({{ $editingQuiz->questions->count() }})</h4>
                        <button wire:click="openQuestionCreateModal({{ $editingQuiz->id }})"
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> {{ __('messages.Add Question') }}
                        </button>
                    </div>

                    @forelse($editingQuiz->questions->sortBy('order') as $question)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4 bg-white">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">#{{ $question->order }}</span>
                                    <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ ucfirst($question->type) }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="openQuestionEditModal({{ $question->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="openOptionCreateModal({{ $question->id }})" class="text-green-600 hover:text-green-800 text-sm" title="Add Option">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button wire:click="openQuestionDeleteModal({{ $question->id }})" class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="font-medium text-gray-800 mb-3">{{ $question->question }}</p>

                            <!-- Options -->
                            <div class="ml-4 space-y-2">
                                @forelse($question->options as $option)
                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                        <div class="flex items-center">
                                            @if($option->is_correct)
                                                <span class="w-5 h-5 mr-2 flex items-center justify-center bg-green-500 text-white rounded-full text-xs">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            @else
                                                <span class="w-5 h-5 mr-2 flex items-center justify-center border border-gray-300 rounded-full text-xs"></span>
                                            @endif
                                            <span class="{{ $option->is_correct ? 'text-green-700 font-medium' : 'text-gray-700' }}">
                                                {{ $option->option_text }}
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button wire:click="openOptionEditModal({{ $option->id }})" class="text-blue-600 hover:text-blue-800 text-xs">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="openOptionDeleteModal({{ $option->id }})" class="text-red-600 hover:text-red-800 text-xs">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm italic">No options yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                            No questions yet. Click "Add Question" to create one.
                        </div>
                    @endforelse
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="closeModals" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
