@props([ // access to all passed property in input component
    'type' => 'text', 'name', 'value' => '', 'label' => false
])

@if($label)
    <label for="">{{ $label }}</label>
@endif

<input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ old($name, $value) }}"
    {{ $attributes->class([ // $attributes inject all attributes passed in input component else in props
        'form-control', // Always apply
        'is-invalid' => $errors->has($name) // Apply 'is-invalid' class if there are errors for 'name'
    ]) }}
>

@error($name)
    <!-- The $message variable automatically contains the error message for the name field. -->
    <div class="invalid-feedback">
    {{ $message }}
    </div>
@enderror
