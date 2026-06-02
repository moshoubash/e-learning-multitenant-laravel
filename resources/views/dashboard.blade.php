<div class="space-y-6">
    {{-- Page header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.Dashboard') }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ __('messages.Welcome back,') }} {{ auth()->user()->name }}</p>
        </div>
    </div>
</div>
