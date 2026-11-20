@props(['url', 'name'])

<button type="button" class="btn btn-sm btn-icon-sm text-danger" data-delete-url="{{ $url }}" data-delete-name="{{ $name }}" title="Hapus" aria-label="Hapus {{ $name }}">
    <i class="bi bi-trash"></i>
</button>
