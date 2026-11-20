@php
    $toasts = [];
    if (session('success')) { $toasts[] = ['success', 'bi-check-circle-fill', session('success')]; }
    if (session('error')) { $toasts[] = ['danger', 'bi-exclamation-triangle-fill', session('error')]; }
    if ($errors->any() && ! session('error')) { $toasts[] = ['danger', 'bi-exclamation-triangle-fill', 'Periksa kembali isian formulir: terdapat ' . $errors->count() . ' kesalahan.']; }
@endphp

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:2000">
    @foreach ($toasts as [$type, $icon, $message])
        <div class="toast astra-toast toast-{{ $type }} align-items-center" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body"><i class="bi {{ $icon }} me-2"></i>{{ $message }}</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    @endforeach
</div>
