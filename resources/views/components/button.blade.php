@props(['variant' => 'primary', 'size' => 'default', 'icon' => null, 'iconSize' => 'default'])

@php
    $sizeClasses = $size === 'small' ? 'btn-sm' : '';
    $iconSizeClass = $iconSize === 'small' ? 'style="font-size:15px"' : 'style="font-size:18px"';
@endphp

<button {{ $attributes->merge(['class' => "btn btn-$variant $sizeClasses"]) }}>
    @if($icon)
        <span class="ms ms-fill" {{ $iconSizeClass }}>{{ $icon }}</span>
    @endif
    {{ $slot }}
</button>
