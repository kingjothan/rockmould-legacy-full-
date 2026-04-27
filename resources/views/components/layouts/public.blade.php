<x-layouts.base :title="$title ?? 'Rockmould Limited'" :description="$description ?? null">
    @php
        $publicCopyScale = in_array((string) ($company['public_copy_scale'] ?? '1'), ['1', '1.08', '1.16', '1.24'], true)
            ? (string) $company['public_copy_scale']
            : '1';
        $publicHeadingScale = in_array((string) ($company['public_heading_scale'] ?? '1'), ['1', '1.08', '1.16', '1.24'], true)
            ? (string) $company['public_heading_scale']
            : '1';
    @endphp
    <div class="public-site-shell min-h-screen" style="--public-copy-scale: {{ $publicCopyScale }}; --public-heading-scale: {{ $publicHeadingScale }};">
        @include('partials.public-header', ['company' => $company])
        <main class="public-main">{{ $slot }}</main>
        @include('partials.footer', ['company' => $company])
    </div>
</x-layouts.base>
