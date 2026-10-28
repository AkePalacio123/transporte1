@props([
    'color' => 'secondary'
])

<span class="badge bg-{{ $color }}">

    {{ $slot }}

</span>
