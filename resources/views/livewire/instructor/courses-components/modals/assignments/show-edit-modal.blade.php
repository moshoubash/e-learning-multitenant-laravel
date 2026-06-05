@if($showAssignmentEditModal && $editingAssignment)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeAssignmentModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">{{ __('messages.Edit Assignment') }}: {{ $editingAssignment->title }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Title') }}</label>
                            <input type="text" wire:model.lazy="assignmentEditTitle"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('assignmentEditTitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Description') }}</label>
                            <textarea wire:model.lazy="assignmentEditDescription" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('assignmentEditDescription') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Instructions') }}</label>
                            <textarea wire:model.lazy="assignmentEditInstructions" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="{{ __('messages.Enter assignment instructions') }}"></textarea>
                            @error('assignmentEditInstructions') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Due Date') }}</label>
                                <input type="datetime-local" wire:model.lazy="assignmentEditDueDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('assignmentEditDueDate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Max Score') }}</label>
                                <input type="number" wire:model.lazy="assignmentEditMaxScore" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('assignmentEditMaxScore') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Allow Late') }}</label>
                                <select wire:model.lazy="assignmentEditAllowLate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1">{{ __('messages.Yes') }}</option>
                                    <option value="0">{{ __('messages.No') }}</option>
                                </select>
                                @error('assignmentEditAllowLate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Status') }}</label>
                                <select wire:model.lazy="assignmentEditStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="draft">{{ __('messages.Draft') }}</option>
                                    <option value="published">{{ __('messages.Published') }}</option>
                                    <option value="archived">{{ __('messages.Archived') }}</option>
                                </select>
                                @error('assignmentEditStatus') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Order') }}</label>
                            <input type="number" wire:model.lazy="assignmentEditOrder" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('assignmentEditOrder') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Attachments Section -->
                        <div class="p-4 mb-4 rounded-lg bg-gray-50">
                            <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('messages.Attachments') }}</label>
                            <p class="mb-2 text-xs text-gray-500">{{ __('messages.Upload additional files for students to reference') }}</p>
                            <input type="file" wire:model="assignmentEditAttachments" multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('assignmentEditAttachments.*') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

                            @if($editingAssignment && $editingAssignment->attachments->count() > 0)
                                <div class="mt-4">
                                    <h4 class="mb-2 text-sm font-medium text-gray-700">{{ __('messages.Current Attachments') }}</h4>
                                    <ul class="space-y-2">
                                        @foreach($editingAssignment->attachments as $attachment)
                                            <li class="flex items-center justify-between p-2 bg-white border rounded">
                                                <div class="flex items-center">
                                                    <i class="@rim('mr-2') text-gray-400 fas fa-file"></i>
                                                    <span class="text-sm text-gray-700">{{ $attachment->file_name }}</span>
                                                    <span class="@rim('ml-2') text-xs text-gray-400">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                                                </div>
                                                <button type="button" wire:click="removeAttachment({{ $attachment->id }})"
                                                    class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Submissions Section (Read-only for review) -->
                        @if($editingAssignment && $editingAssignment->submissions->count() > 0)
                            <div class="p-4 mb-4 rounded-lg bg-blue-50">
                                <h4 class="mb-2 text-sm font-medium text-gray-700">
                                    {{ __('messages.Submissions') }} ({{ $editingAssignment->submissions->count() }})
                                </h4>
                                <ul class="space-y-2 overflow-y-auto max-h-48">
                                    @foreach($editingAssignment->submissions as $submission)
                                        <li class="p-2 bg-white border rounded">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-700">{{ $submission->student->name ?? 'Unknown' }}</span>
                                                    <span class="ml-2 text-xs text-gray-400">
                                                        {{ $submission->submitted_at ? $submission->submitted_at->format('Y-m-d H:i') : 'N/A' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-1 text-xs rounded {{ $submission->status === 'graded' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                        {{ ucfirst($submission->status) }}
                                                    </span>
                                                    @if($submission->score !== null)
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ $submission->score }}/{{ $editingAssignment->max_score ?? 100 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="updateAssignment" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.Update') }}
                    </button>
                    <button wire:click="closeAssignmentModal" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
