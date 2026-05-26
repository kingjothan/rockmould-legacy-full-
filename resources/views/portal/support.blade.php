<x-layouts.portal :company="$company" :page-heading="$content['title'] ?? 'Support'">
    @php
        $primaryAssignment = $assignments->first();
        $primaryManager = $primaryAssignment
            ? [
                'name' => $primaryAssignment->account_manager_name ?: 'Rockmould Client Relations',
                'email' => $primaryAssignment->account_manager_email ?: $company['email'],
                'phone' => $primaryAssignment->account_manager_phone ?: $company['phone'],
            ]
            : [
                'name' => 'Rockmould Client Relations',
                'email' => $company['email'],
                'phone' => $company['phone'],
            ];
        $initials = collect(explode(' ', $primaryManager['name']))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
        $faqs = $content['faqs'] ?? [];
    @endphp

    <div class="mx-auto max-w-3xl space-y-8">
        <div>
            <h1 class="font-display text-2xl font-semibold">{{ $content['title'] ?? 'Support' }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">{{ $content['description'] ?? 'Get help with your portal, projects, or account' }}</p>
        </div>

        <div class="luxury-card">
            <h2 class="mb-4 font-display text-lg font-semibold">{{ $content['manager_title'] ?? 'Your Account Manager' }}</h2>
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-lg font-bold text-primary">{{ $initials ?: 'RM' }}</div>
                <div>
                    <p class="font-medium">{{ $primaryManager['name'] }}</p>
                    <p class="text-xs text-muted-foreground">{{ $content['manager_role'] ?? 'Senior Client Relations Manager' }}</p>
                    <div class="mt-2 flex flex-wrap gap-4">
                        <a href="tel:{{ preg_replace('/\s+/', '', (string) $primaryManager['phone']) }}" class="flex items-center gap-1 text-xs text-primary hover:underline"><x-portal-icon name="phone" class="h-3 w-3" /> {{ $primaryManager['phone'] }}</a>
                        <a href="mailto:{{ $primaryManager['email'] }}" class="flex items-center gap-1 text-xs text-primary hover:underline"><x-portal-icon name="mail" class="h-3 w-3" /> {{ $primaryManager['email'] }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="luxury-card text-center">
                <x-portal-icon name="phone" class="mx-auto mb-2 h-6 w-6 text-primary" />
                <p class="text-xs uppercase tracking-wider text-muted-foreground">{{ $content['phone_label'] ?? 'Call Us' }}</p>
                <p class="mt-1 text-sm font-medium">{{ $company['phone'] }}</p>
                <p class="text-xs text-muted-foreground">{{ $content['phone_note'] ?? 'Mon-Fri, 9am-5pm' }}</p>
            </div>
            <div class="luxury-card text-center">
                <x-portal-icon name="mail" class="mx-auto mb-2 h-6 w-6 text-primary" />
                <p class="text-xs uppercase tracking-wider text-muted-foreground">{{ $content['email_label'] ?? 'Email' }}</p>
                <p class="mt-1 text-sm font-medium">{{ $company['support_email'] ?? $company['email'] }}</p>
                <p class="text-xs text-muted-foreground">{{ $content['email_note'] ?? '24hr response time' }}</p>
            </div>
            <div class="luxury-card text-center">
                <x-portal-icon name="clock" class="mx-auto mb-2 h-6 w-6 text-primary" />
                <p class="text-xs uppercase tracking-wider text-muted-foreground">{{ $content['hours_label'] ?? 'Office Hours' }}</p>
                <p class="mt-1 text-sm font-medium">{{ $content['hours_value'] ?? '9:00 AM - 5:00 PM' }}</p>
                <p class="text-xs text-muted-foreground">{{ $content['hours_note'] ?? 'Monday to Friday' }}</p>
            </div>
        </div>

        <div class="luxury-card">
            <h2 class="mb-4 font-display text-lg font-semibold">{{ $content['message_title'] ?? 'Send a Message' }}</h2>
            <form action="{{ route('portal.support.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Subject</label>
                    <select name="subject" class="input-shell">
                        @foreach (['General Inquiry', 'Payment Question', 'Project Update Request', 'Document Request', 'Technical Issue'] as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Project</label>
                    <select name="project_assignment_id" class="input-shell">
                        <option value="">General Portal Support</option>
                        @foreach ($assignments as $assignment)
                            <option value="{{ $assignment->id }}">{{ $assignment->project?->title ?? 'Project' }} &middot; {{ $assignment->unit?->label ?? $assignment->unit_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Message</label>
                    <textarea name="message" rows="4" required placeholder="Describe your question or issue..." class="input-shell resize-none">{{ old('message') }}</textarea>
                </div>
                <button class="luxury-btn-primary gap-2 text-xs">
                    <x-portal-icon name="send" class="h-3.5 w-3.5" />
                    {{ $content['message_button'] ?? 'Send Message' }}
                </button>
            </form>
        </div>

        <div class="luxury-card">
            <h2 class="mb-4 font-display text-lg font-semibold">{{ $content['faq_title'] ?? 'Frequently Asked Questions' }}</h2>
            <div class="space-y-3">
                @foreach ($faqs as $faq)
                    <details class="faq-item">
                        <summary>
                            <span class="flex items-center gap-2">
                                <x-portal-icon name="help-circle" class="h-4 w-4 shrink-0 text-primary" />
                                {{ $faq['question'] }}
                            </span>
                            <svg class="faq-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div class="faq-body">{{ $faq['answer'] }}</div>
                    </details>
                @endforeach
            </div>
        </div>

        <div class="luxury-card">
            <h2 class="mb-4 font-display text-lg font-semibold">{{ $content['tickets_title'] ?? 'Previous Tickets' }}</h2>
            <div class="space-y-4">
                @forelse ($tickets as $ticket)
                    <div class="rounded-sm border border-border bg-muted/20 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-medium">{{ $ticket->subject }}</p>
                            <x-status-badge :value="$ticket->status" />
                        </div>
                        <p class="mt-3 text-sm text-muted-foreground">{{ $ticket->message }}</p>
                    </div>
                @empty
                    <p class="text-sm text-muted-foreground">{{ $content['tickets_empty'] ?? 'No support tickets yet.' }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.portal>
