<div class="glass card-pad mb-3">
    <div class="row">
        <x-form.input class="col-md-6" name="name" label="Nama Kategori" :value="$category->name" required maxlength="100" />
        <x-form.textarea name="description" label="Deskripsi" :value="$category->description" />
    </div>
</div>
<div class="form-actions">
    <a href="{{ route('equipment-categories.index') }}" class="btn btn-ghost">Batal</a>
    <button type="submit" class="btn btn-astra"><i class="bi bi-save me-1"></i>{{ $category->exists ? 'Simpan Perubahan' : 'Simpan Kategori' }}</button>
</div>
