<x-layouts.admin :company="$company" page-heading="Notifications">
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($notifications as $notification)
            <div class="stat-card">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10">
                    <x-portal-icon name="bell" class="h-5 w-5 text-primary" />
                </div>
                <p class="font-display text-3xl font-bold text-primary">{{ $notification['count'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">{{ $notification['title'] }}</p>
            </div>
        @endforeach
    </div>
</x-layouts.admin>
