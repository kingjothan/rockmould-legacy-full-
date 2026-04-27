@props([
    'alt' => 'Rockmould Limited logo',
])

<span {{ $attributes->class(['brand-mark inline-flex shrink-0 items-center']) }} role="img" aria-label="{{ $alt }}">
    <img
        src="{{ asset('images/rockmould_logo_white.png') }}"
        alt=""
        aria-hidden="true"
        class="brand-mark-image"
        data-brand-theme="dark"
    >
    <img
        src="{{ asset('images/rockmould_logo.png') }}"
        alt=""
        aria-hidden="true"
        class="brand-mark-image"
        data-brand-theme="light"
    >
</span>
