@props(['label', 'value', 'subtext' => null, 'variant' => 'default'])

<div class="stat-card">
    <div class="stat-lbl">{{ $label }}</div>
    <div class="stat-val @if($variant === 'green') green @elseif($variant === 'amber') amber @elseif($variant === 'blue') blue @endif">
        {{ $value }}
    </div>
    @if($subtext)
        <div class="stat-sub">{{ $subtext }}</div>
    @endif
</div>
