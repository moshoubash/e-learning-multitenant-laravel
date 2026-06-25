<div class="overflow-hidden bg-surface-container-lowest neo-border neo-radius">
    <div class="p-[24px] border-b-2 border-on-surface flex items-center justify-between">
        <h3 class="text-[18px] font-bold uppercase tracking-widest text-on-surface leading-none">{{ __('messages.All Courses') }}</h3>
        <button wire:click="openCreateModal"
            class="px-4 py-2 text-xs font-bold tracking-widest uppercase transition-colors neo-border neo-radius bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-white">
            <i class="fas fa-plus ltr:mr-2 rtl:ml-2"></i>
            {{ __('messages.Add Course') }}
        </button>
    </div>
    <div>
        <p class="px-[24px] py-3 text-xs font-bold uppercase tracking-widest text-secondary border-b border-[#E5E5E5]">
            {{ __('messages.Note: Only instructors can add sections, lessons, and quizzes to courses.') }}
        </p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full ltr:text-left rtl:text-right">
            <thead class="border-b-2 bg-surface-container-low border-on-surface">
                <tr>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Thumbnail') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Title') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Instructor') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Price') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Status') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Created At') }}</th>
                    <th class="p-4 text-[10px] font-bold uppercase tracking-widest text-secondary">{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E5E5]">
                @forelse ($courses as $course)
                    <tr class="transition-colors duration-150 hover:bg-surface-container-low">
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
                            <div class="text-sm font-bold text-on-surface">{{ $course->title }}</div>
                            <div class="text-xs text-secondary mt-0.5">{{ Str::limit($course->description, 50) }}</div>
                        </td>
                        <td class="p-4 text-sm text-on-surface">{{ $course->instructor->name ?? 'N/A' }}</td>
                        <td class="p-4 text-sm font-bold text-on-surface">${{ number_format($course->price, 2) }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 neo-border-sm neo-radius text-[10px] font-bold leading-none
                                @if($course->status === 'published') bg-primary-container text-on-primary-container
                                @elseif($course->status === 'archived') bg-surface-container text-secondary
                                @else bg-surface-container-high text-on-surface @endif">
                                {{ __('messages.' . ucfirst($course->status)) }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-secondary">
                            {{ app()->getLocale() === 'ar' ? $course->created_at->isoFormat('dddd, D MMMM YYYY') : $course->created_at->translatedFormat('Y-m-d') }}
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
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-sm text-center text-secondary">{{ __('messages.No courses found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-on-surface">
        {{ $courses->links() }}
    </div>
</div>
