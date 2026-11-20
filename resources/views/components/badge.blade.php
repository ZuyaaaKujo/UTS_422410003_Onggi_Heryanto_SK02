@props(['color' => 'secondary', 'icon' => null])

<span {{ $attributes->class(['badge-astra', "badge-{$color}"]) }}>@if ($icon)<i class="bi {{ $icon }}"></i>@endif{{ $slot }}</span>
