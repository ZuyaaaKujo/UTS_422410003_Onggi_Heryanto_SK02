@php $detail = $session->locationDetail; @endphp

{{-- A. Informasi sesi --}}
<div class="glass card-pad mb-3">
    <div class="card-title-row"><h2><i class="bi bi-info-circle"></i> Informasi Sesi</h2></div>
    <div class="row">
        <x-form.input class="col-md-4" name="code" label="Kode Sesi" :value="$session->code" required maxlength="30" />
        <x-form.input class="col-md-8" name="name" label="Nama Sesi" :value="$session->name" required maxlength="150" placeholder="mis. Pengamatan Cincin Saturnus" />
        <x-form.select class="col-md-6" name="astronomical_object_id" label="Objek Sasaran" :options="$objects->mapWithKeys(fn ($o) => [$o->id => $o->name . ' (' . $o->code . ')'])->all()" :value="$session->astronomical_object_id" required />
        <x-form.select class="col-md-6" name="status" label="Status Sesi" :options="$statuses" :value="$session->status" :placeholder="false" required />
        <x-form.input class="col-md-4" type="date" name="observation_date" label="Tanggal Pengamatan" :value="$session->observation_date?->format('Y-m-d')" required />
        <x-form.input class="col-md-4 col-6" type="time" name="start_time" label="Waktu Mulai" :value="$session->start_time_short" required />
        <x-form.input class="col-md-4 col-6" type="time" name="end_time" label="Waktu Selesai" :value="$session->end_time_short" required hint="Boleh melewati tengah malam." />
        <x-form.textarea class="col-md-6" name="description" label="Deskripsi Pengamatan" :value="$session->description" />
        <x-form.textarea class="col-md-6" name="notes" label="Catatan Tambahan" :value="$session->notes" />
    </div>
</div>

{{-- B. Detail lokasi (One-to-One) --}}
<div class="glass card-pad mb-3">
    <div class="card-title-row">
        <h2><i class="bi bi-geo-alt"></i> Detail Lokasi Sesi</h2>
        <small class="text-muted">Satu sesi memiliki tepat satu detail lokasi</small>
    </div>
    <div class="row">
        <x-form.select class="col-md-6" name="location[observation_location_id]" label="Lokasi Pengamatan" :options="$locations->mapWithKeys(fn ($l) => [$l->id => $l->name . ($l->city ? ' — ' . $l->city : '')])->all()" :value="$detail?->observation_location_id" required />
        <x-form.select class="col-md-3 col-6" name="location[weather_condition]" label="Kondisi Cuaca" :options="array_combine($weatherOptions, $weatherOptions)" :value="$detail?->weather_condition" required />
        <x-form.select class="col-md-3 col-6" name="location[visibility_condition]" label="Visibilitas" :options="array_combine($visibilityOptions, $visibilityOptions)" :value="$detail?->visibility_condition" required />
        <x-form.input class="col-md-4 col-4" type="number" step="0.1" name="location[temperature]" label="Suhu (°C)" :value="$detail?->temperature" />
        <x-form.input class="col-md-4 col-4" type="number" name="location[humidity]" label="Kelembapan (%)" :value="$detail?->humidity" min="0" max="100" />
        <x-form.input class="col-md-4 col-4" type="number" name="location[light_pollution_level]" label="Polusi Cahaya (Bortle 1-9)" :value="$detail?->light_pollution_level" min="1" max="9" />
        <x-form.textarea name="location[environment_notes]" label="Catatan Kondisi Lingkungan" :value="$detail?->environment_notes" rows="2" />
    </div>
</div>

{{-- C. Perlengkapan (Many-to-Many) --}}
<div class="glass card-pad mb-3">
    <div class="card-title-row">
        <h2><i class="bi bi-camera-reels"></i> Perlengkapan yang Digunakan</h2>
        <small class="text-muted"><span id="eqSelectedCount">0</span> dipilih · perlengkapan dalam perawatan tidak dapat dipilih</small>
    </div>
    @error('equipment')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

    @foreach ($equipmentList->groupBy(fn ($e) => $e->category->name) as $categoryName => $items)
        <div class="eq-group-title">{{ $categoryName }}</div>
        <div class="row g-2 mb-3">
            @foreach ($items as $item)
                @php
                    $pivot = $selectedEquipment->get($item->id)?->pivot;
                    $isSelected = (bool) old("equipment.{$item->id}.selected", $pivot !== null);
                    $locked = $item->availability_status === \App\Enums\EquipmentAvailability::Maintenance && ! $isSelected;
                @endphp
                <div class="col-xl-4 col-md-6">
                    <div class="eq-pick {{ $isSelected ? 'is-selected' : '' }} {{ $locked ? 'is-locked' : '' }}">
                        <label class="eq-pick-head">
                            <input type="checkbox" class="form-check-input eq-check" name="equipment[{{ $item->id }}][selected]" value="1" @checked($isSelected) @disabled($locked)>
                            <span class="flex-grow-1">
                                <strong>{{ $item->name }}</strong>
                                <small class="d-block text-muted">{{ $item->code }} · {{ trim($item->brand . ' ' . $item->model) ?: '—' }} · stok {{ $item->quantity }}</small>
                            </span>
                            <x-badge :color="$item->availability_status->color()">{{ $item->availability_status->label() }}</x-badge>
                        </label>
                        <div class="eq-pick-body">
                            <div class="eq-qty">
                                <label class="form-label small mb-1" for="qty_{{ $item->id }}">Jumlah</label>
                                <input type="number" id="qty_{{ $item->id }}" class="form-control form-control-sm @error("equipment.{$item->id}.quantity_used") is-invalid @enderror" name="equipment[{{ $item->id }}][quantity_used]" min="1" max="{{ max($item->quantity, 1) }}" value="{{ old("equipment.{$item->id}.quantity_used", $pivot?->quantity_used ?? 1) }}">
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label small mb-1" for="note_{{ $item->id }}">Catatan penggunaan</label>
                                <input type="text" id="note_{{ $item->id }}" class="form-control form-control-sm" name="equipment[{{ $item->id }}][usage_notes]" maxlength="255" value="{{ old("equipment.{$item->id}.usage_notes", $pivot?->usage_notes) }}" placeholder="opsional">
                            </div>
                        </div>
                        @error("equipment.{$item->id}.quantity_used")<div class="text-danger small px-3 pb-2">{{ $message }}</div>@enderror
                        @error("equipment.{$item->id}")<div class="text-danger small px-3 pb-2">{{ $message }}</div>@enderror
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<div class="form-actions">
    <a href="{{ $session->exists ? route('sessions.show', $session) : route('sessions.index') }}" class="btn btn-ghost">Batal</a>
    <button type="submit" class="btn btn-astra"><i class="bi bi-save me-1"></i>{{ $session->exists ? 'Simpan Perubahan' : 'Simpan Sesi' }}</button>
</div>
