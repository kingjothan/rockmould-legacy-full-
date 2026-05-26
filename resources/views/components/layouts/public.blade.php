<x-layouts.base :title="$title ?? 'Rockmould Limited'" :description="$description ?? null">
    @php
        $formatScale = function ($value) {
            $number = is_numeric($value) ? (float) $value : 1.0;
            $number = max(0.7, min(2.5, $number));

            return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
        };
        $publicCopyScale = $formatScale($company['public_copy_scale'] ?? '1');
        $publicHeadingScale = $formatScale($company['public_heading_scale'] ?? '1');
    @endphp
    <div class="public-site-shell min-h-screen" style="--public-copy-scale: {{ $publicCopyScale }}; --public-heading-scale: {{ $publicHeadingScale }};">
        @include('partials.public-header', ['company' => $company])
        <main class="public-main">{{ $slot }}</main>
        @include('partials.footer', ['company' => $company])
    </div>
</x-layouts.base>
