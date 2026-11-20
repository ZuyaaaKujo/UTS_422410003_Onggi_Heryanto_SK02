@props(['title' => 'Belum ada data', 'text' => null, 'icon' => 'bi-moon-stars'])

<div class="empty-state">
    <i class="bi {{ $icon }}"></i>
    <h5>{{ $title }}</h5>
    @if ($text)<p>{{ $text }}</p>@endif
    {{ $slot }}
</div>
