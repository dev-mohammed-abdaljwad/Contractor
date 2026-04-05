@props(['variant' => 'gray', 'icon' => null])

<span class="badge badge-{{ $variant }}">
    @if($icon)
        <span class="ms" style="font-size:14px;margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}:4px">{{ $icon }}</span>
    @endif
    {{ $slot }}
</span>
