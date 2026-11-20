<div class="glass card-pad mb-3">
    <div class="row">
        <x-form.input class="col-md-4" name="code" label="Kode Perlengkapan" :value="$equipment->code" required maxlength="20" />
        <x-form.input class="col-md-8" name="name" label="Nama Perlengkapan" :value="$equipment->name" required maxlength="150" placeholder="mis. Dobsonian Telescope 10&quot;" />
        <x-form.select class="col-md-6" name="equipment_category_id" label="Kategori" :options="$categories->pluck('name', 'id')->all()" :value="$equipment->equipment_category_id" required />
        <x-form.input class="col-md-3 col-6" name="brand" label="Merek" :value="$equipment->brand" maxlength="80" />
        <x-form.input class="col-md-3 col-6" name="model" label="Model" :value="$equipment->model" maxlength="80" />
        <x-form.input class="col-md-3" type="number" name="quantity" label="Jumlah (stok)" :value="$equipment->quantity" min="0" required />
        <x-form.select class="col-md-4 col-6" name="condition" label="Kondisi" :options="$conditions" :value="$equipment->condition" :placeholder="false" required />
        <x-form.select class="col-md-5 col-6" name="availability_status" label="Status Ketersediaan" :options="$availabilities" :value="$equipment->availability_status" :placeholder="false" required />
        <x-form.textarea name="description" label="Deskripsi" :value="$equipment->description" />
    </div>
</div>
<div class="form-actions">
    <a href="{{ $equipment->exists ? route('equipment.show', $equipment) : route('equipment.index') }}" class="btn btn-ghost">Batal</a>
    <button type="submit" class="btn btn-astra"><i class="bi bi-save me-1"></i>{{ $equipment->exists ? 'Simpan Perubahan' : 'Simpan Perlengkapan' }}</button>
</div>
