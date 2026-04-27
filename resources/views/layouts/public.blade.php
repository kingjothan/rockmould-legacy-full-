<x-layouts.base :title="$title ?? 'Rockmould Limited'" :description="$description ?? null">
    <div class="min-h-screen">
        @include('partials.public-header', ['company' => $company])
        <main>{{ $slot }}</main>
        @include('partials.footer', ['company' => $company])
    </div>
</x-layouts.base>
