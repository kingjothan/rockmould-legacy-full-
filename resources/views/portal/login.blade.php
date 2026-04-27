<x-layouts.base title="Client Portal | Rockmould Limited">
    @php
        $loginMediaPath = $content['media_path'] ?? null;
        $loginMediaUrl = $loginMediaPath ? \App\Support\AssetPath::url($loginMediaPath) : null;
        $loginMediaExtension = strtolower(pathinfo((string) parse_url((string) $loginMediaPath, PHP_URL_PATH), PATHINFO_EXTENSION));
        $loginMediaIsVideo = in_array($loginMediaExtension, ['mp4', 'webm', 'ogg', 'mov'], true);
        $marketingTitle = $content['marketing_title'] ?? 'Your Projects, At Your Fingertips';
        $marketingHighlight = $content['marketing_highlight'] ?? 'At Your Fingertips';
        $marketingFeatures = collect($content['features'] ?? [
            'Track project progress in real-time',
            'Access documents and statements',
            'View payment history and balances',
            'Secure and private portal access',
        ])->filter()->values();
        $marketingIcons = ['bar-chart-3', 'file-text', 'clock', 'shield'];
    @endphp
    <div class="min-h-screen bg-background lg:flex">
        <div class="relative hidden overflow-hidden border-r border-border bg-card lg:flex lg:w-1/2 lg:items-center lg:justify-center lg:p-12">
            @if ($loginMediaUrl)
                @if ($loginMediaIsVideo)
                    <video autoplay muted loop playsinline class="hero-media">
                        <source src="{{ $loginMediaUrl }}">
                    </video>
                @else
                    <img src="{{ $loginMediaUrl }}" alt="Portal access background" class="hero-media">
                @endif
                <div class="hero-soft-overlay"></div>
                <div class="hero-overlay"></div>
            @endif

            <div class="relative z-10 max-w-md">
                <a href="{{ route('home') }}" class="mb-12 flex items-center gap-3">
                    <x-brand-mark class="h-10 w-auto" />
                </a>

                <h2 class="mb-6 font-display text-3xl font-semibold">
                    @if ($marketingHighlight && str_contains($marketingTitle, $marketingHighlight))
                        {{ str($marketingTitle)->replace($marketingHighlight, '')->trim() }}
                        <span class="text-gold-gradient">{{ $marketingHighlight }}</span>
                    @else
                        {{ $marketingTitle }}
                    @endif
                </h2>

                <div class="space-y-4">
                    @foreach ($marketingFeatures as $index => $feature)
                        <div class="flex items-center gap-3 text-sm text-muted-foreground">
                            <x-portal-icon :name="$marketingIcons[$index] ?? 'check-circle'" class="h-4 w-4 text-primary" />
                            <span>{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-1 items-center justify-center p-6">
            <div class="w-full max-w-md">
                @if ($state === 'pending')
                    <div class="text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                            <x-portal-icon name="clock" class="h-7 w-7 text-primary" />
                        </div>
                        <h2 class="mb-2 font-display text-2xl font-semibold">{{ $content['pending_title'] ?? 'Request Submitted' }}</h2>
                        <p class="mb-2 text-muted-foreground">{{ $content['pending_description'] ?? 'Your KYC request is under review.' }}</p>
                        <p class="mb-8 text-sm text-muted-foreground">{{ $content['pending_helper'] ?? 'Our team will verify your details and set up your account. This usually takes 1-2 business days.' }}</p>
                        <a href="{{ route('home') }}" class="luxury-btn-outline">Return to Website</a>
                    </div>
                @elseif ($state === 'unrecognized')
                    <div class="text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-destructive/10">
                            <x-portal-icon name="phone" class="h-7 w-7 text-destructive" />
                        </div>
                        <h2 class="mb-2 font-display text-2xl font-semibold">{{ $content['unrecognized_title'] ?? 'Phone Not Recognized' }}</h2>
                        <p class="mb-8 text-muted-foreground">{{ $content['unrecognized_description'] ?? 'This number isn’t registered. Submit a KYC request to get started.' }}</p>
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('portal.login', ['mode' => 'kyc']) }}" class="luxury-btn-primary w-full justify-center">
                                {{ $content['unrecognized_button'] ?? 'Submit KYC Request' }}
                                <x-portal-icon name="arrow-right" class="h-4 w-4" />
                            </a>
                            <a href="{{ route('portal.login') }}" class="text-sm text-muted-foreground transition hover:text-primary">Try another number</a>
                        </div>
                    </div>
                @elseif ($state === 'kyc')
                    <div>
                        <h2 class="mb-2 font-display text-2xl font-semibold">{{ $content['kyc_title'] ?? 'KYC Request' }}</h2>
                        <p class="mb-6 text-sm text-muted-foreground">{{ $content['kyc_description'] ?? 'Fill in your details. Our team will review and set up your account.' }}</p>
                        @include('portal.partials.kyc-form')
                    </div>
                @else
                    <div>
                        <h1 class="mb-2 font-display text-3xl font-semibold">{{ $content['welcome_title'] ?? 'Welcome Back' }}</h1>
                        <p class="mb-8 text-muted-foreground">{{ $content['welcome_description'] ?? 'Enter your registered phone number to access your portal.' }}</p>

                        <form action="{{ route('portal.authenticate') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="mb-2 block text-xs uppercase tracking-wider text-muted-foreground">Phone Number</label>
                                <div class="portal-search-shell">
                                    <x-portal-icon name="phone" class="h-4 w-4" />
                                    <input name="phone" value="{{ old('phone') }}" placeholder="+234 xxx xxx xxxx" class="input-shell py-3">
                                </div>
                            </div>
                            <button type="submit" class="luxury-btn-primary w-full justify-center">
                                {{ $content['access_button'] ?? 'Access Portal' }}
                                <x-portal-icon name="arrow-right" class="h-4 w-4" />
                            </button>
                        </form>

                        <p class="mt-6 text-center text-xs text-muted-foreground">
                            {{ $content['request_access_text'] ?? 'Don’t have an account?' }}
                            <a href="{{ route('portal.login', ['mode' => 'kyc']) }}" class="text-primary hover:underline">{{ $content['request_access_link'] ?? 'Request Access' }}</a>
                        </p>
                        <p class="mt-4 text-center text-xs text-muted-foreground">
                            {{ $content['support_text'] ?? 'Need help?' }}
                            <a href="{{ route('contact') }}" class="text-primary hover:underline">{{ $content['support_link'] ?? 'Contact Support' }}</a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.base>
