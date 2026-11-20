@props(['label', 'value', 'icon', 'color' => 'cyan', 'href' => null])

@if ($href)
<a href="{{ $href }}" {{ $attributes->class(['stat-card', "stat-{$color}"]) }}>
@else
<div {{ $attributes->class(['stat-card', "stat-{$color}"]) }}>
@endif
    <div class="stat-icon"><i class="bi {{ $icon }}"></i></div>
    <div>
        <div class="stat-value" data-count="{{ $value }}">{{ $value }}</div>
        <div class="stat-label">{{ $label }}</div>
    </div>
@if ($href)
</a>
@else
</div>
@endif
