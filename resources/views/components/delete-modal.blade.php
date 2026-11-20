{{-- Modal konfirmasi hapus: dipakai semua tombol dengan atribut data-delete-url. --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="" id="deleteForm" class="modal-content glass">
            @csrf
            @method('DELETE')
            <div class="modal-body text-center p-4">
                <div class="modal-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <h5 class="mt-3" id="deleteModalLabel">Hapus data ini?</h5>
                <p class="text-muted mb-0">Anda akan menghapus <strong id="deleteModalName">data</strong>. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4 pt-0">
                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>
