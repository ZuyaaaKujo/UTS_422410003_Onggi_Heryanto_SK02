@props(['name', 'label', 'value' => null, 'rows' => 3, 'hint' => null, 'errorKey' => null])

@php
    $key = $errorKey ?? str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f_' . str_replace(['[', ']', '.'], '_', $name);
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'mb-3']) }}>
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" {{ $attributes->except('class')->class(['form-control', 'is-invalid' => $errors->has($key)]) }}>{{ old($key, $value) }}</textarea>
    @if ($hint)<div class="form-text">{{ $hint }}</div>@endif
    @error($key)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
