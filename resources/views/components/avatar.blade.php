@props(['initial', 'variant' => 'green', 'size' => 'default'])

@php
    $sizeClasses = [
        'default' => 'av',
        'small' => 'av',
        'large' => 'av',
    ];
    $sizeStyle = $size === 'small' ? 'style="width:30px;height:30px;font-size:12px"' : ($size === 'large' ? 'style="width:52px;height:52px;font-size:20px"' : '');
@endphp

<div class="av av-{{ $variant }}" {{ $sizeStyle }}>
    {{ $initial }}
</div>
