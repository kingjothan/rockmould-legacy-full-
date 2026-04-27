<x-layouts.admin :company="$company" page-heading="FAQ CMS">
    <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            @foreach ($faqs as $faq)
                <div class="luxury-card">
                    <h2 class="font-display text-2xl">{{ $faq->question }}</h2>
                    <form action="{{ route('admin.cms.faq.update', $faq) }}" method="POST" class="mt-5 space-y-4">
                        @csrf @method('PUT')
                        <input name="category" value="{{ $faq->category }}" class="input-shell">
                        <input name="question" value="{{ $faq->question }}" class="input-shell">
                        <textarea name="answer" rows="4" class="input-shell">{{ $faq->answer }}</textarea>
                        <button class="luxury-btn-primary">Update FAQ</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="luxury-card">
            <h2 class="font-display text-3xl">Add FAQ</h2>
            <form action="{{ route('admin.cms.faq.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <input name="category" required placeholder="Category" class="input-shell">
                <input name="question" required placeholder="Question" class="input-shell">
                <textarea name="answer" rows="4" required placeholder="Answer" class="input-shell"></textarea>
                <button class="luxury-btn-primary w-full">Create FAQ</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
