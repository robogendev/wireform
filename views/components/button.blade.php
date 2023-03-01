@props([
    'style' => 'primary',
    'label' => null,
    'slot' => null,
])

@php
    $slot = $slot ?: $label;
@endphp

@if($style === 'primary')
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest']) }}>
        {{ $slot }}
    </button>
@elseif($style === 'secondary')
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm']) }}>
        {{ $slot }}
    </button>
@endif
