@if($showAssignmentCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeAssignmentModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">{{ __('messages.Create New Assignment') }}</h3>
                    <form>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Title') }}</label>
                            <input type="text" wire:model.lazy="assignmentCreateTitle"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('assignmentCreateTitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Description') }}</label>
                            <textarea wire:model.lazy="assignmentCreateDescription" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('assignmentCreateDescription') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Instructions') }}</label>
                            <textarea wire:model.lazy="assignmentCreateInstructions" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="{{ __('messages.Enter assignment instructions') }}"></textarea>
                            @error('assignmentCreateInstructions') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Due Date') }}</label>
                                <input type="datetime-local" wire:model.lazy="assignmentCreateDueDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('assignmentCreateDueDate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Max Score') }}</label>
                                <input type="number" wire:model.lazy="assignmentCreateMaxScore" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="100">
                                @error('assignmentCreateMaxScore') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Allow Late') }}</label>
                                <select wire:model.lazy="assignmentCreateAllowLate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="1">{{ __('messages.Yes') }}</option>
                                    <option value="0">{{ __('messages.No') }}</option>
                                </select>
                                @error('assignmentCreateAllowLate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Status') }}</label>
                                <select wire:model.lazy="assignmentCreateStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="draft">{{ __('messages.Draft') }}</option>
                                    <option value="published">{{ __('messages.Published') }}</option>
                                    <option value="archived">{{ __('messages.Archived') }}</option>
                                </select>
                                @error('assignmentCreateStatus') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.Order') }}</label>
                            <input type="number" wire:model.lazy="assignmentCreateOrder" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('assignmentCreateOrder') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Attachments Section -->
                        <div class="p-4 mb-4 rounded-lg bg-gray-50">
                            <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('messages.Attachments') }}</label>
                            <p class="mb-2 text-xs text-gray-500">{{ __('messages.Upload files for students to reference') }}</p>
                            <input type="file" wire:model="assignmentCreateAttachments" multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('assignmentCreateAttachments.*') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            @if(count($this->assignmentCreateAttachments ?? []) > 0)
                                <div class="mt-2 text-sm text-gray-600">
                                    {{ count($this->assignmentCreateAttachments) }} {{ __('messages.file(s) selected') }}
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="storeAssignment" type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.Create') }}
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
