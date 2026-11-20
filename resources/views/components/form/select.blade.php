@props(['name', 'label', 'options' => [], 'value' => null, 'required' => false, 'placeholder' => '— Pilih —', 'errorKey' => null])

@php
    $key = $errorKey ?? str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f_' . str_replace(['[', ']', '.'], '_', $name);
    $current = (string) old($key, $value instanceof \BackedEnum ? $value->value : $value);
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'mb-3']) }}>
    <label for="{{ $id }}" class="form-label">{{ $label }}@if ($required)<span class="text-danger"> *</span>@endif</label>
    <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->except('class')->class(['form-select', 'is-invalid' => $errors->has($key)]) }} @required($required)>
        @if ($placeholder !== false)<option value="">{{ $placeholder }}</option>@endif
        @foreach ($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected((string) $optValue === $current)>{{ $optLabel }}</option>
        @endforeach
    </select>
    @error($key)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
