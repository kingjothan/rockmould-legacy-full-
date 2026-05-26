<x-layouts.admin :company="$company" page-heading="Settings">
    @php
        $fontSizeScale = function (string $key) use ($company) {
            $value = $company[$key] ?? '1';
            $number = is_numeric($value) ? (float) $value : 1.0;
            $number = max(0.7, min(2.5, $number));

            return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
        };
    @endphp
    <div class="max-w-4xl">
        <div class="luxury-card-flat overflow-hidden p-0">
            <div class="border-b border-border px-6 py-4">
                <h2 class="font-display text-lg font-semibold">Company Settings</h2>
                <p class="mt-0.5 text-sm text-muted-foreground">Update the company profile, contact details, and support information.</p>
            </div>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                @if (session('success'))
                    <div class="mb-6 flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="form-section mb-5">
                    <p class="form-section-title">Company Identity</p>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Company Name</label>
                            <input name="name" value="{{ $company['name'] }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Tagline</label>
                            <input name="tagline" value="{{ $company['tagline'] }}" class="input-shell">
                        </div>
                    </div>
                </div>

                <div class="form-section mb-5">
                    <p class="form-section-title">Primary Contact</p>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Phone Number</label>
                            <input name="phone" value="{{ $company['phone'] }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Email Address</label>
                            <input type="email" name="email" value="{{ $company['email'] }}" class="input-shell">
                        </div>
                    </div>
                </div>

                <div class="form-section mb-5">
                    <p class="form-section-title">Support Contact</p>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Support Phone</label>
                            <input name="support_phone" value="{{ $company['support_phone'] }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Support Email</label>
                            <input type="email" name="support_email" value="{{ $company['support_email'] }}" class="input-shell">
                        </div>
                    </div>
                </div>

                <div class="form-section mb-6">
                    <p class="form-section-title">Office</p>
                    <div class="grid gap-4">
                        <div>
                            <label class="admin-label">Office Hours</label>
                            <input name="hours" value="{{ $company['hours'] }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Address</label>
                            <textarea name="address" rows="3" class="input-shell">{{ $company['address'] }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-6">
                    <p class="form-section-title">System Typography</p>
                    <p class="mb-4 text-sm text-muted-foreground">Control every major write-up and heading scale across the public website, client portal, and admin CMS. Use any value from 0.70 to 2.50; 1 is default, 1.50 is 50% larger.</p>
                    <div class="grid gap-5">
                        <div class="rounded-lg border border-border bg-background/40 p-4">
                            <p class="mb-3 text-sm font-medium text-foreground">Public Website</p>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="admin-label">Public Copy Size</label>
                                    <input type="number" name="public_copy_scale" value="{{ old('public_copy_scale', $fontSizeScale('public_copy_scale')) }}" min="0.7" max="2.5" step="0.01" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Public Heading Size</label>
                                    <input type="number" name="public_heading_scale" value="{{ old('public_heading_scale', $fontSizeScale('public_heading_scale')) }}" min="0.7" max="2.5" step="0.01" class="input-shell">
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-border bg-background/40 p-4">
                            <p class="mb-3 text-sm font-medium text-foreground">Client Portal</p>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="admin-label">Portal Copy Size</label>
                                    <input type="number" name="portal_copy_scale" value="{{ old('portal_copy_scale', $fontSizeScale('portal_copy_scale')) }}" min="0.7" max="2.5" step="0.01" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Portal Heading Size</label>
                                    <input type="number" name="portal_heading_scale" value="{{ old('portal_heading_scale', $fontSizeScale('portal_heading_scale')) }}" min="0.7" max="2.5" step="0.01" class="input-shell">
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-border bg-background/40 p-4">
                            <p class="mb-3 text-sm font-medium text-foreground">Admin / CMS</p>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="admin-label">Admin Copy Size</label>
                                    <input type="number" name="admin_copy_scale" value="{{ old('admin_copy_scale', $fontSizeScale('admin_copy_scale')) }}" min="0.7" max="2.5" step="0.01" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Admin Heading Size</label>
                                    <input type="number" name="admin_heading_scale" value="{{ old('admin_heading_scale', $fontSizeScale('admin_heading_scale')) }}" min="0.7" max="2.5" step="0.01" class="input-shell">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="luxury-btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
