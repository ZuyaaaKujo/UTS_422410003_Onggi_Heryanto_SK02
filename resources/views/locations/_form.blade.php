<div class="glass card-pad mb-3">
    <div class="row">
        <x-form.input class="col-md-4" name="code" label="Kode Lokasi" :value="$location->code" required maxlength="20" />
        <x-form.input class="col-md-8" name="name" label="Nama Lokasi" :value="$location->name" required maxlength="150" placeholder="mis. Observatorium Bosscha" />
        <x-form.input class="col-md-4" name="country" label="Negara" :value="$location->country" required maxlength="80" />
        <x-form.input class="col-md-4" name="province" label="Provinsi" :value="$location->province" maxlength="100" />
        <x-form.input class="col-md-4" name="city" label="Kota" :value="$location->city" maxlength="100" />
        <x-form.input class="col-md-4" type="number" step="0.0000001" name="latitude" label="Latitude" :value="$location->latitude" required hint="-90 s.d. 90" />
        <x-form.input class="col-md-4" type="number" step="0.0000001" name="longitude" label="Longitude" :value="$location->longitude" required hint="-180 s.d. 180" />
        <x-form.input class="col-md-4" type="number" name="elevation" label="Elevasi (m dpl)" :value="$location->elevation" required />
        <x-form.select class="col-md-6" name="sky_condition" label="Kondisi Langit" :options="array_combine($skyConditions, $skyConditions)" :value="$location->sky_condition" />
        <x-form.input class="col-md-6" type="number" name="light_pollution_level" label="Tingkat Polusi Cahaya (Bortle 1–9)" :value="$location->light_pollution_level" min="1" max="9" required hint="1 = sangat gelap, 9 = langit kota." />
        <x-form.textarea name="description" label="Deskripsi Lokasi" :value="$location->description" />
    </div>
</div>
<div class="form-actions">
    <a href="{{ $location->exists ? route('locations.show', $location) : route('locations.index') }}" class="btn btn-ghost">Batal</a>
    <button type="submit" class="btn btn-astra"><i class="bi bi-save me-1"></i>{{ $location->exists ? 'Simpan Perubahan' : 'Simpan Lokasi' }}</button>
</div>
