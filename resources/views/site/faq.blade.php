<x-layouts.public :company="$company" title="FAQ | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Frequently Asked Questions'"
        :subtitle="$page['hero_subtitle'] ?? 'Support and Clarity'"
        :description="$page['hero_description'] ?? 'Answers to common questions about our projects, services, portal access, and delivery model.'"
        :image="$page['hero_media_path'] ?? 'images/project-2.jpg'"
        :title-size="$page['font_sizes']['hero_title'] ?? null"
        :description-size="$page['font_sizes']['hero_description'] ?? null"
    />

    <section id="rm-faq" aria-label="Rockmould frequently asked questions">
        <style>
            #rm-faq {
                padding: clamp(48px,7vw,96px) clamp(16px,5vw,48px);
                background: hsl(var(--background));
                color: hsl(var(--foreground));
                font-family: var(--font-body);
            }
            #rm-faq *, #rm-faq *::before, #rm-faq *::after { box-sizing: border-box; }
            #rm-faq .rm-wrap { max-width: 1200px; margin: 0 auto; }

            /* Eyebrow */
            #rm-faq .rm-eyebrow {
                font-size: 11px; letter-spacing: .22em; text-transform: uppercase;
                color: hsl(var(--primary)); font-weight: 600; margin: 0 0 12px;
                display: flex; align-items: center; gap: 10px;
            }
            #rm-faq .rm-eyebrow::before {
                content: ""; display: block; width: 28px; height: 1px;
                background: hsl(var(--primary));
            }

            /* Search */
            #rm-faq .rm-search { position: relative; max-width: 520px; margin: 0 0 40px; }
            #rm-faq .rm-search input {
                width: 100%; font-family: var(--font-body); font-size: 15px;
                color: hsl(var(--foreground)); background: hsl(var(--card));
                border: 1px solid hsl(var(--border)); border-radius: var(--radius);
                padding: 13px 16px 13px 44px; outline: none;
                transition: border-color .18s, box-shadow .18s;
            }
            #rm-faq .rm-search input::placeholder { color: hsl(var(--muted-foreground)); }
            #rm-faq .rm-search input:focus {
                border-color: hsl(var(--primary));
                box-shadow: 0 0 0 3px hsl(var(--primary) / .15);
            }
            #rm-faq .rm-search svg {
                position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
                width: 18px; height: 18px; color: hsl(var(--muted-foreground));
                pointer-events: none;
            }

            /* Grid */
            #rm-faq .rm-grid { display: grid; grid-template-columns: 240px 1fr; gap: clamp(24px,4vw,64px); align-items: start; }
            #rm-faq .rm-grid > * { min-width: 0; }

            /* Rail */
            #rm-faq .rm-rail { position: sticky; top: 88px; }
            #rm-faq .rm-rail-label {
                font-size: 10px; letter-spacing: .2em; text-transform: uppercase;
                color: hsl(var(--muted-foreground)); font-weight: 600;
                padding: 0 0 12px; border-bottom: 1px solid hsl(var(--border)); margin: 0 0 6px;
            }
            #rm-faq .rm-rail ul { list-style: none; margin: 0; padding: 0; }
            #rm-faq .rm-rail button {
                display: flex; width: 100%; align-items: baseline;
                justify-content: space-between; gap: 8px;
                background: none; border: 0; cursor: pointer; text-align: left;
                font-family: var(--font-body); font-size: 13.5px;
                color: hsl(var(--muted-foreground)); padding: 8px 0 8px 16px;
                position: relative; transition: color .15s;
            }
            #rm-faq .rm-rail button::before {
                content: ""; position: absolute; left: 0; top: 50%;
                transform: translateY(-50%) scaleY(.2);
                width: 2px; height: 18px; background: hsl(var(--primary));
                opacity: 0; transition: opacity .18s, transform .18s;
            }
            #rm-faq .rm-rail button:hover { color: hsl(var(--foreground)); }
            #rm-faq .rm-rail button .rm-count { font-size: 11px; color: hsl(var(--muted-foreground)); }
            #rm-faq .rm-rail button[aria-current="true"] { color: hsl(var(--foreground)); font-weight: 600; }
            #rm-faq .rm-rail button[aria-current="true"]::before { opacity: 1; transform: translateY(-50%) scaleY(1); }

            /* Mobile pills */
            #rm-faq .rm-pills { display: none; }

            /* Category sections */
            #rm-faq .rm-section { display: none; }
            #rm-faq .rm-section.rm-active { display: block; }

            /* Category heading */
            #rm-faq .rm-cat-head { margin: 0 0 4px; }
            #rm-faq .rm-cat-head h3 {
                font-family: var(--font-display); font-weight: 600;
                font-size: clamp(20px, 2.2vw, 26px);
                color: hsl(var(--foreground)); margin: 0 0 10px; letter-spacing: .02em;
            }
            #rm-faq .rm-cat-rule { width: 40px; height: 2px; background: hsl(var(--primary)); margin-bottom: 20px; }

            /* Accordion items */
            #rm-faq .rm-item { border-bottom: 1px solid hsl(var(--border)); }
            #rm-faq .rm-item:first-of-type { border-top: 1px solid hsl(var(--border)); }
            #rm-faq .rm-q {
                display: flex; width: 100%; gap: 16px; align-items: flex-start;
                justify-content: space-between; background: none; border: 0; cursor: pointer;
                text-align: left; font-family: var(--font-body);
                font-size: clamp(14.5px, 1.5vw, 16.5px); font-weight: 500;
                color: hsl(var(--foreground)); padding: 18px 2px; line-height: 1.45;
                transition: color .15s;
            }
            #rm-faq .rm-q:hover { color: hsl(var(--primary)); }
            #rm-faq .rm-q > span:first-child { min-width: 0; flex: 1; overflow-wrap: break-word; }
            #rm-faq .rm-q .rm-ico { flex: 0 0 auto; width: 18px; height: 18px; margin-top: 2px; position: relative; }
            #rm-faq .rm-q .rm-ico::before, #rm-faq .rm-q .rm-ico::after {
                content: ""; position: absolute; background: hsl(var(--primary));
                transition: transform .25s ease, opacity .2s;
            }
            #rm-faq .rm-q .rm-ico::before { top: 8px; left: 0; width: 18px; height: 2px; }
            #rm-faq .rm-q .rm-ico::after  { top: 0; left: 8px; width: 2px; height: 18px; }
            #rm-faq .rm-q[aria-expanded="true"] .rm-ico::after { transform: scaleY(0); opacity: 0; }
            #rm-faq .rm-q[aria-expanded="true"] { color: hsl(var(--primary)); }
            #rm-faq .rm-panel { overflow: hidden; max-height: 0; transition: max-height .32s ease; }
            #rm-faq .rm-panel-inner { padding: 0 32px 22px 2px; color: hsl(var(--muted-foreground)); font-size: 15px; line-height: 1.7; }
            #rm-faq .rm-panel-inner p { margin: 0 0 10px; }
            #rm-faq .rm-panel-inner p:last-child { margin-bottom: 0; }
            #rm-faq .rm-panel-inner ol, #rm-faq .rm-panel-inner ul { margin: 6px 0 10px; padding-left: 22px; }
            #rm-faq .rm-panel-inner li { margin: 0 0 5px; }
            #rm-faq .rm-panel-inner strong { color: hsl(var(--foreground)); font-weight: 600; }

            /* Search results */
            #rm-faq .rm-result-cat {
                font-size: 10px; letter-spacing: .18em; text-transform: uppercase;
                color: hsl(var(--primary)); font-weight: 600; margin: 16px 0 -8px; padding-top: 8px;
            }
            #rm-faq .rm-result-cat:first-child { margin-top: 0; }
            #rm-faq .rm-empty { color: hsl(var(--muted-foreground)); font-size: 15px; padding: 28px 0; }
            #rm-faq #rm-search-results { display: none; }
            #rm-faq #rm-search-results.rm-active { display: block; }

            /* Focus */
            #rm-faq button:focus-visible { outline: 2px solid hsl(var(--primary)); outline-offset: 3px; border-radius: 2px; }

            @media (max-width: 760px) {
                #rm-faq .rm-grid { grid-template-columns: 1fr; gap: 0; }
                #rm-faq .rm-rail { display: none; }
                #rm-faq .rm-pills {
                    display: flex; gap: 8px; overflow-x: auto; padding: 2px 2px 16px;
                    margin: 0 0 20px; -webkit-overflow-scrolling: touch;
                    scrollbar-width: none;
                }
                #rm-faq .rm-pills::-webkit-scrollbar { display: none; }
                #rm-faq .rm-pills button {
                    flex: 0 0 auto; font-family: var(--font-body); font-size: 13px;
                    white-space: nowrap; cursor: pointer;
                    background: hsl(var(--card)); border: 1px solid hsl(var(--border));
                    color: hsl(var(--muted-foreground)); padding: 7px 14px;
                    border-radius: 999px; transition: .15s;
                }
                #rm-faq .rm-pills button[aria-current="true"] {
                    background: hsl(var(--primary)); border-color: hsl(var(--primary));
                    color: hsl(var(--primary-foreground));
                }
                #rm-faq .rm-panel-inner { padding-right: 8px; }
            }
            @media (prefers-reduced-motion: reduce) {
                #rm-faq * { transition: none !important; }
            }
        </style>

        <div class="rm-wrap">
            <p class="rm-eyebrow">Rockmould Limited</p>
            <h2 style="font-family:var(--font-display);font-size:clamp(28px,4vw,48px);font-weight:600;letter-spacing:.02em;margin:0 0 12px;color:hsl(var(--foreground));">
                Frequently asked questions
            </h2>
            <p style="font-size:clamp(14px,1.4vw,16.5px);color:hsl(var(--muted-foreground));max-width:58ch;margin:0 0 32px;line-height:1.65;">
                Everything you need to know about buying, building, and investing with Rockmould. Search a question, or browse by section.
            </p>

            <div class="rm-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input id="rm-search-input" type="search" placeholder="Search the FAQ…" aria-label="Search frequently asked questions" autocomplete="off">
            </div>

            <div class="rm-grid">
                {{-- Left rail --}}
                <nav class="rm-rail" aria-label="FAQ sections">
                    <p class="rm-rail-label">Sections</p>
                    <ul id="rm-rail-list">
                        @foreach ($faqs as $category => $items)
                            <li>
                                <button type="button" data-cat="{{ Str::slug($category) }}"
                                    aria-current="{{ $loop->first ? 'true' : 'false' }}">
                                    <span>{{ $category }}</span>
                                    <span class="rm-count">{{ count($items) }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <div>
                    {{-- Mobile pills --}}
                    <div class="rm-pills" id="rm-pills" role="tablist" aria-label="FAQ sections">
                        @foreach ($faqs as $category => $items)
                            <button type="button" data-cat="{{ Str::slug($category) }}"
                                aria-current="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Search results container --}}
                    <div id="rm-search-results"></div>

                    {{-- Category sections --}}
                    @foreach ($faqs as $category => $items)
                        <div class="rm-section {{ $loop->first ? 'rm-active' : '' }}" id="cat-{{ Str::slug($category) }}">
                            <div class="rm-cat-head">
                                <h3>{{ $category }}</h3>
                                <div class="rm-cat-rule"></div>
                            </div>
                            @foreach ($items as $faq)
                                <div class="rm-item" data-question="{{ strtolower($faq->question) }}" data-answer="{{ strtolower(strip_tags($faq->answer)) }}" data-cat-label="{{ $category }}">
                                    <button type="button" class="rm-q" aria-expanded="false">
                                        <span>{{ $faq->question }}</span>
                                        <span class="rm-ico" aria-hidden="true"></span>
                                    </button>
                                    <div class="rm-panel" role="region">
                                        <div class="rm-panel-inner">{!! nl2br(e($faq->answer)) !!}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <script>
        (function () {
            var allItems = Array.from(document.querySelectorAll('#rm-faq .rm-item'));
            var searchInput = document.getElementById('rm-search-input');
            var searchResults = document.getElementById('rm-search-results');
            var sections = document.querySelectorAll('#rm-faq .rm-section');
            var railBtns = document.querySelectorAll('#rm-rail-list button');
            var pillBtns = document.querySelectorAll('#rm-pills button');
            var activecat = railBtns[0] ? railBtns[0].dataset.cat : null;
            var searchTimer;

            function setCategory(cat) {
                activecat = cat;
                railBtns.forEach(function (b) { b.setAttribute('aria-current', b.dataset.cat === cat ? 'true' : 'false'); });
                pillBtns.forEach(function (b) { b.setAttribute('aria-current', b.dataset.cat === cat ? 'true' : 'false'); });
                sections.forEach(function (s) {
                    s.classList.toggle('rm-active', s.id === 'cat-' + cat);
                });
            }

            railBtns.forEach(function (b) {
                b.addEventListener('click', function () {
                    clearSearch();
                    setCategory(b.dataset.cat);
                });
            });
            pillBtns.forEach(function (b) {
                b.addEventListener('click', function () {
                    clearSearch();
                    setCategory(b.dataset.cat);
                });
            });

            // Accordion
            document.getElementById('rm-faq').addEventListener('click', function (e) {
                var btn = e.target.closest('.rm-q');
                if (!btn) return;
                var open = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', String(!open));
                var panel = btn.nextElementSibling;
                panel.style.maxHeight = open ? '0px' : (panel.scrollHeight + 28) + 'px';
            });

            // Search
            function clearSearch() {
                searchInput.value = '';
                searchResults.innerHTML = '';
                searchResults.classList.remove('rm-active');
                sections.forEach(function (s) {
                    s.classList.toggle('rm-active', s.id === 'cat-' + activecat);
                });
            }

            function doSearch(q) {
                if (!q) { clearSearch(); return; }
                sections.forEach(function (s) { s.classList.remove('rm-active'); });
                searchResults.innerHTML = '';
                searchResults.classList.add('rm-active');

                var hits = 0;
                allItems.forEach(function (item) {
                    var q2 = item.dataset.question || '';
                    var a2 = item.dataset.answer || '';
                    if (q2.indexOf(q) > -1 || a2.indexOf(q) > -1) {
                        if (hits === 0 || item.dataset.catLabel !== (searchResults.lastElementChild && searchResults.lastElementChild.dataset && searchResults.lastElementChild.dataset.cat)) {
                            var label = document.createElement('p');
                            label.className = 'rm-result-cat';
                            label.dataset.cat = item.dataset.catLabel;
                            label.textContent = item.dataset.catLabel;
                            searchResults.appendChild(label);
                        }
                        searchResults.appendChild(item.cloneNode(true));
                        hits++;
                    }
                });

                if (hits === 0) {
                    var e2 = document.createElement('p');
                    e2.className = 'rm-empty';
                    e2.textContent = 'No questions match "' + q + '". Try a different word, or browse the sections.';
                    searchResults.appendChild(e2);
                }

                // Re-attach accordion to cloned nodes
                searchResults.querySelectorAll('.rm-q').forEach(function (btn) {
                    btn.setAttribute('aria-expanded', 'false');
                    btn.nextElementSibling.style.maxHeight = '0px';
                });
            }

            searchInput.addEventListener('input', function (e) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () { doSearch(e.target.value.trim().toLowerCase()); }, 130);
            });
        })();
        </script>
    </section>
</x-layouts.public>
