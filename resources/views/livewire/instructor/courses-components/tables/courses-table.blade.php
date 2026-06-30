<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.Active Courses') }}</h3>
        <button wire:click="openCreateModal"
            class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
            <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
            {{ __('messages.Add Course') }}
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr class="rtl:text-right">
                    <th class="w-8 p-4 text-[10px] font-bold uppercase tracking-widest text-secondary"></th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Thumbnail') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Title') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Price') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5] rtl:text-right">
                @foreach ($courses as $course)
                    <tr>
                        <td class="text-center rtl:pr-4 ltr:pl-4">
                            <button wire:click="toggleCourseExpand({{ $course->id }})"
                                class="transition-colors text-secondary hover:text-on-primary-container">
                                <i class="fas {{ $expandedCourses && in_array($course->id, $expandedCourses) ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                            </button>
                        </td>
                        <td class="p-4">
                            @if ($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" class="object-cover w-12 h-12 neo-border-sm neo-radius" alt="Thumbnail">
                            @else
                                <div class="flex items-center justify-center w-12 h-12 neo-border-sm neo-radius bg-surface-container-low text-secondary">
                                    <i class="text-sm fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-on-surface">{{ $course->title }}</span>
                                @if($course->sections && count($course->sections) > 0)
                                    <span class="text-xs text-secondary ltr:ml-2 rtl:mr-2">({{ __('messages.sections') }}: {{ count($course->sections) }})</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4 text-sm font-bold text-on-surface">${{ number_format($course->price, 2) }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold leading-none
                                @if($course->status === 'published') bg-primary-container text-on-primary-container
                                @elseif($course->status === 'archived') bg-surface-container text-secondary
                                @else bg-surface-container-high text-on-surface @endif">
                                {{ __('messages.' . ucfirst($course->status)) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEditModal({{ $course->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Edit">
                                    <i class="text-xs fas fa-edit"></i>
                                </button>
                                <button wire:click="openDeleteModal({{ $course->id }})"
                                    class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-error hover:bg-error hover:text-white" title="Delete">
                                    <i class="text-xs fas fa-trash"></i>
                                </button>
                                @if($course->sections && count($course->sections) === 0)
                                    <button wire:click="openSectionCreateModal({{ $course->id }})"
                                        class="flex items-center justify-center w-8 h-8 transition-colors neo-border-sm neo-radius text-on-surface hover:bg-primary-container hover:text-on-primary-container" title="Add Section">
                                        <i class="text-xs fas fa-plus-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @if($expandedCourses && in_array($course->id, $expandedCourses))
                        <tr>
                            <td colspan="7" class="p-0 bg-surface-container-low">
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-xs font-bold tracking-widest uppercase text-secondary">{{ __('messages.Sections & Lessons') }}</h4>
                                        <div class="flex items-center gap-2">
                                            <button wire:click="openPlaylistImportModal({{ $course->id }})"
                                                class="px-3 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-surface-container-low text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors">
                                                <i class="fab fa-youtube ltr:mr-1 rtl:ml-1 text-[#FF0000]"></i>
                                                {{ __('messages.Import Playlist') }}
                                            </button>
                                            <button wire:click="openSectionCreateModal({{ $course->id }})"
                                                class="px-3 py-1 neo-border-sm neo-radius text-[10px] font-bold bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white transition-colors">
                                                <i class="fas fa-plus ltr:mr-1 rtl:ml-1"></i>
                                                {{ __('messages.Add Section') }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        @forelse($course->sections->sortBy('order') as $section)
                                            @include('livewire.instructor.courses-components.tables.partials.section-row', ['section' => $section, 'courseId' => $course->id])
                                        @empty
                                            <p class="text-xs italic text-secondary">{{ __('messages.No sections yet. Click Add Section or Import Playlist to get started.') }}</p>
                                        @endforelse
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
                @if(count($courses) == 0)
                    <tr><td colspan="7" class="p-8 text-sm text-center text-secondary">{{ __('messages.No courses found.') }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $courses->links() }}
    </div>
</div>
