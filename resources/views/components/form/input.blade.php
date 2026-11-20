@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null, 'errorKey' => null])

@php
    $key = $errorKey ?? str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f_' . str_replace(['[', ']', '.'], '_', $name);
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'mb-3']) }}>
    <label for="{{ $id }}" class="form-label">{{ $label }}@if ($required)<span class="text-danger"> *</span>@endif</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ old($key, $value) }}"
        {{ $attributes->except('class')->class(['form-control', 'is-invalid' => $errors->has($key)]) }} @required($required)>
    @if ($hint)<div class="form-text">{{ $hint }}</div>@endif
    @error($key)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
