<div class="glass card-pad mb-3">
    <div class="row">
        <x-form.input class="col-md-4" name="code" label="Kode Objek" :value="$object->code" required maxlength="20" />
        <x-form.input class="col-md-8" name="name" label="Nama Objek" :value="$object->name" required maxlength="120" placeholder="mis. Orion Nebula (M42)" />
        <x-form.select class="col-md-6" name="category" label="Kategori" :options="$categories" :value="$object->category" required />
        <x-form.input class="col-md-6" name="constellation" label="Konstelasi" :value="$object->constellation" maxlength="80" placeholder="mis. Orion" />
        <x-form.textarea name="description" label="Deskripsi" :value="$object->description" rows="3" />
        <x-form.textarea name="astronomical_info" label="Informasi Astronomi" :value="$object->astronomical_info" rows="3" hint="Jarak, ukuran, magnitudo, atau fakta ilmiah lain (rujuk NASA/ESA/ESO)." />
        <x-form.input type="url" name="image_url" label="URL Gambar (opsional)" :value="$object->image_url" hint="Gunakan gambar berlisensi publik (mis. NASA/ESA). Bila kosong atau gagal dimuat, gambar ilustrasi kategori dipakai." />
    </div>
</div>
<div class="form-actions">
    <a href="{{ $object->exists ? route('astronomical-objects.show', $object) : route('astronomical-objects.index') }}" class="btn btn-ghost">Batal</a>
    <button type="submit" class="btn btn-astra"><i class="bi bi-save me-1"></i>{{ $object->exists ? 'Simpan Perubahan' : 'Simpan Objek' }}</button>
</div>
