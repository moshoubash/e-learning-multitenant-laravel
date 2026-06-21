@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#16a34a]']) }}>
        {{ $status }}
    </div>
@endif
