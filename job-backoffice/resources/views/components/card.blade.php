<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden']) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>