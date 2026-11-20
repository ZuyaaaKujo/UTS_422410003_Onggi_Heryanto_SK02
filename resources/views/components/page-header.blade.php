@props(['title', 'subtitle' => null, 'icon' => 'bi-stars'])

<div class="page-header">
    <div class="d-flex align-items-start gap-3">
        <div class="page-header-icon"><i class="bi {{ $icon }}"></i></div>
        <div>
            <h1>{{ $title }}</h1>
            @if ($subtitle)<p>{{ $subtitle }}</p>@endif
        </div>
    </div>
    @isset($actions)
        <div class="page-header-actions">{{ $actions }}</div>
    @endisset
</div>
