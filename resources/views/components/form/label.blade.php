@props([
    'id' => ''
])
<!-- access to value of image as $slot -->
<label for="{{ $id }}">{{ $slot }}</label>
