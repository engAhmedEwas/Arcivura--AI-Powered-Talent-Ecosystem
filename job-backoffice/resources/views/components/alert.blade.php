@props(['type' => 'success'])

@php
    $classes = $type === 'success' 
        ? 'bg-green-50 border-green-500 text-green-700' 
        : 'bg-red-50 border-red-500 text-red-700';
    $icon = $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
@endphp

<div {{ $attributes->merge(['class' => "p-4 border-l-4 rounded-r-xl shadow-sm mb-6 $classes"]) }}>
    <div class="flex items-center">
        <i class="fas {{ $icon }} mr-2"></i>
        <p class="text-sm font-medium">{{ $slot }}</p>
    </div>
</div>