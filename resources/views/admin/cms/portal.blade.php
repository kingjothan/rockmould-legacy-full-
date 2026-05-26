<x-layouts.admin :company="$company" page-heading="Portal CMS">
    @php
        $loginMediaPath = data_get($portal, 'login.media_path');
        $loginMediaUrl = $loginMediaPath ? \App\Support\AssetPath::url($loginMediaPath) : null;
        $loginMediaExtension = strtolower(pathinfo((string) parse_url((string) $loginMediaPath, PHP_URL_PATH), PATHINFO_EXTENSION));
        $loginMediaIsVideo = in_array($loginMediaExtension, ['mp4', 'webm', 'ogg', 'mov'], true);
        $loginFeaturesText = collect(data_get($portal, 'login.features', []))->implode(PHP_EOL);
        $supportFaqsText = collect(data_get($portal, 'support.faqs', []))
            ->map(fn ($faq) => ($faq['question'] ?? '').'|'.($faq['answer'] ?? ''))
            ->implode(PHP_EOL);
    @endphp

    <form action="{{ route('admin.cms.portal.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid gap-8 xl:grid-cols-2">
            <div class="luxury-card space-y-6">
                <div>
                    <p class="section-label">Portal Login</p>
                    <h2 class="font-display text-3xl">Portal Entry Experience</h2>
                    <p class="mt-2 text-sm text-muted-foreground">Control the login-screen media, marketing panel, KYC prompts, and access messaging.</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1.02fr_0.98fr]">
                    <div class="space-y-4">
                        <div>
                            <label class="admin-label">Marketing Title</label>
                            <input name="login_marketing_title" value="{{ old('login_marketing_title', data_get($portal, 'login.marketing_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Highlighted Words</label>
                            <input name="login_marketing_highlight" value="{{ old('login_marketing_highlight', data_get($portal, 'login.marketing_highlight')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Feature List</label>
                            <textarea name="login_features_text" rows="5" class="input-shell">{{ old('login_features_text', $loginFeaturesText) }}</textarea>
                            <p class="mt-2 text-xs text-muted-foreground">Use one feature per line.</p>
                        </div>
                        <div>
                            <label class="admin-label">Welcome Title</label>
                            <input name="login_welcome_title" value="{{ old('login_welcome_title', data_get($portal, 'login.welcome_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Welcome Description</label>
                            <textarea name="login_welcome_description" rows="3" class="input-shell">{{ old('login_welcome_description', data_get($portal, 'login.welcome_description')) }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Access Button</label>
                                <input name="login_access_button" value="{{ old('login_access_button', data_get($portal, 'login.access_button')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Request Access Prompt</label>
                                <input name="login_request_access_text" value="{{ old('login_request_access_text', data_get($portal, 'login.request_access_text')) }}" class="input-shell">
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Request Access Link</label>
                                <input name="login_request_access_link" value="{{ old('login_request_access_link', data_get($portal, 'login.request_access_link')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Support Prompt</label>
                                <input name="login_support_text" value="{{ old('login_support_text', data_get($portal, 'login.support_text')) }}" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Support Link Label</label>
                            <input name="login_support_link" value="{{ old('login_support_link', data_get($portal, 'login.support_link')) }}" class="input-shell">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="overflow-hidden rounded-lg border border-border bg-card">
                            <div class="aspect-[16/10] bg-muted">
                                @if ($loginMediaUrl)
                                    @if ($loginMediaIsVideo)
                                        <video controls autoplay muted playsinline preload="auto" class="h-full w-full object-cover" data-video-lightbox data-video-src="{{ $loginMediaUrl }}" data-video-title="Portal login media" tabindex="0" role="button" aria-label="Play portal login media video">
                                            <source src="{{ $loginMediaUrl }}">
                                        </video>
                                    @else
                                        <img src="{{ $loginMediaUrl }}" alt="Portal login media" class="h-full w-full object-cover">
                                    @endif
                                @else
                                    <div class="flex h-full items-center justify-center text-sm text-muted-foreground">No portal login media selected</div>
                                @endif
                            </div>
                            <div class="border-t border-border px-4 py-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Login Media</p>
                                <p class="mt-1 text-sm text-muted-foreground">{{ $loginMediaPath ?: 'Not set yet' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Login Media Link or Path</label>
                            <input name="login_media_path" value="{{ old('login_media_path', $loginMediaPath) }}" class="input-shell" placeholder="https://cdn.example.com/portal-login.mp4">
                            <p class="mt-2 text-xs text-muted-foreground">Paste a CDN/direct image or video URL, or use a local path.</p>
                        </div>
                        <div>
                            <label class="admin-label">Upload Login Media</label>
                            <input type="file" name="login_media_upload" accept="image/*,video/*" class="input-shell">
                            <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                <input type="checkbox" name="remove_login_media" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                Remove current login media if no new link or upload is provided
                            </label>
                        </div>
                        <div>
                            <label class="admin-label">Pending Title</label>
                            <input name="login_pending_title" value="{{ old('login_pending_title', data_get($portal, 'login.pending_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Pending Description</label>
                            <textarea name="login_pending_description" rows="2" class="input-shell">{{ old('login_pending_description', data_get($portal, 'login.pending_description')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Pending Helper Copy</label>
                            <textarea name="login_pending_helper" rows="3" class="input-shell">{{ old('login_pending_helper', data_get($portal, 'login.pending_helper')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Unrecognized Title</label>
                            <input name="login_unrecognized_title" value="{{ old('login_unrecognized_title', data_get($portal, 'login.unrecognized_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Unrecognized Description</label>
                            <textarea name="login_unrecognized_description" rows="3" class="input-shell">{{ old('login_unrecognized_description', data_get($portal, 'login.unrecognized_description')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Unrecognized Button</label>
                            <input name="login_unrecognized_button" value="{{ old('login_unrecognized_button', data_get($portal, 'login.unrecognized_button')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">KYC Title</label>
                            <input name="login_kyc_title" value="{{ old('login_kyc_title', data_get($portal, 'login.kyc_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">KYC Description</label>
                            <textarea name="login_kyc_description" rows="3" class="input-shell">{{ old('login_kyc_description', data_get($portal, 'login.kyc_description')) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="luxury-card space-y-6">
                <div>
                    <p class="section-label">Dashboard</p>
                    <h2 class="font-display text-3xl">Portal Dashboard Messaging</h2>
                    <p class="mt-2 text-sm text-muted-foreground">Manage the welcome message, metric labels, section headings, and empty states used on the client dashboard.</p>
                </div>

                <div class="grid gap-4">
                    <div>
                        <label class="admin-label">Welcome Title</label>
                        <input name="dashboard_welcome_title" value="{{ old('dashboard_welcome_title', data_get($portal, 'dashboard.welcome_title')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Description</label>
                        <textarea name="dashboard_description" rows="3" class="input-shell">{{ old('dashboard_description', data_get($portal, 'dashboard.description')) }}</textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Active Projects Label</label>
                            <input name="dashboard_active_projects_label" value="{{ old('dashboard_active_projects_label', data_get($portal, 'dashboard.active_projects_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Amount Paid Label</label>
                            <input name="dashboard_amount_paid_label" value="{{ old('dashboard_amount_paid_label', data_get($portal, 'dashboard.amount_paid_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Outstanding Label</label>
                            <input name="dashboard_outstanding_label" value="{{ old('dashboard_outstanding_label', data_get($portal, 'dashboard.outstanding_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Documents Label</label>
                            <input name="dashboard_documents_label" value="{{ old('dashboard_documents_label', data_get($portal, 'dashboard.documents_label')) }}" class="input-shell">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Projects Section Title</label>
                            <input name="dashboard_projects_title" value="{{ old('dashboard_projects_title', data_get($portal, 'dashboard.projects_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Projects Link Label</label>
                            <input name="dashboard_projects_link" value="{{ old('dashboard_projects_link', data_get($portal, 'dashboard.projects_link')) }}" class="input-shell">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Projects Empty State</label>
                        <textarea name="dashboard_projects_empty" rows="2" class="input-shell">{{ old('dashboard_projects_empty', data_get($portal, 'dashboard.projects_empty')) }}</textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Updates Section Title</label>
                            <input name="dashboard_updates_title" value="{{ old('dashboard_updates_title', data_get($portal, 'dashboard.updates_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Documents Section Title</label>
                            <input name="dashboard_documents_title" value="{{ old('dashboard_documents_title', data_get($portal, 'dashboard.documents_title')) }}" class="input-shell">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Updates Empty State</label>
                            <textarea name="dashboard_updates_empty" rows="2" class="input-shell">{{ old('dashboard_updates_empty', data_get($portal, 'dashboard.updates_empty')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Documents Empty State</label>
                            <textarea name="dashboard_documents_empty" rows="2" class="input-shell">{{ old('dashboard_documents_empty', data_get($portal, 'dashboard.documents_empty')) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="luxury-card space-y-6">
                <div>
                    <p class="section-label">Projects</p>
                    <h2 class="font-display text-3xl">Projects Page Copy</h2>
                </div>
                <div class="grid gap-4">
                    <div>
                        <label class="admin-label">Kicker</label>
                        <input name="projects_kicker" value="{{ old('projects_kicker', data_get($portal, 'projects.kicker')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Title</label>
                        <input name="projects_title" value="{{ old('projects_title', data_get($portal, 'projects.title')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Description</label>
                        <textarea name="projects_description" rows="3" class="input-shell">{{ old('projects_description', data_get($portal, 'projects.description')) }}</textarea>
                    </div>
                    <div>
                        <label class="admin-label">Filter Button</label>
                        <input name="projects_filter_button" value="{{ old('projects_filter_button', data_get($portal, 'projects.filter_button')) }}" class="input-shell">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Assigned Label</label>
                            <input name="projects_assigned_label" value="{{ old('projects_assigned_label', data_get($portal, 'projects.assigned_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Assigned Description</label>
                            <textarea name="projects_assigned_description" rows="2" class="input-shell">{{ old('projects_assigned_description', data_get($portal, 'projects.assigned_description')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Paid Label</label>
                            <input name="projects_paid_label" value="{{ old('projects_paid_label', data_get($portal, 'projects.paid_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Paid Description</label>
                            <textarea name="projects_paid_description" rows="2" class="input-shell">{{ old('projects_paid_description', data_get($portal, 'projects.paid_description')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Outstanding Label</label>
                            <input name="projects_outstanding_label" value="{{ old('projects_outstanding_label', data_get($portal, 'projects.outstanding_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Outstanding Description</label>
                            <textarea name="projects_outstanding_description" rows="2" class="input-shell">{{ old('projects_outstanding_description', data_get($portal, 'projects.outstanding_description')) }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Empty State Kicker</label>
                        <input name="projects_empty_kicker" value="{{ old('projects_empty_kicker', data_get($portal, 'projects.empty_kicker')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Empty State Title</label>
                        <input name="projects_empty_title" value="{{ old('projects_empty_title', data_get($portal, 'projects.empty_title')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Empty State Description</label>
                        <textarea name="projects_empty_description" rows="3" class="input-shell">{{ old('projects_empty_description', data_get($portal, 'projects.empty_description')) }}</textarea>
                    </div>
                    <div>
                        <label class="admin-label">Card Button Label</label>
                        <input name="projects_card_button" value="{{ old('projects_card_button', data_get($portal, 'projects.card_button')) }}" class="input-shell">
                    </div>
                </div>
            </div>

            <div class="luxury-card space-y-6">
                <div>
                    <p class="section-label">Documents, Media, and Balance</p>
                    <h2 class="font-display text-3xl">Library and Finance Labels</h2>
                </div>
                <div class="space-y-6">
                    <div class="form-section">
                        <p class="form-section-title">Documents</p>
                        <div>
                            <label class="admin-label">Title</label>
                            <input name="documents_title" value="{{ old('documents_title', data_get($portal, 'documents.title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Description</label>
                            <textarea name="documents_description" rows="2" class="input-shell">{{ old('documents_description', data_get($portal, 'documents.description')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Empty State Text</label>
                            <textarea name="documents_empty_text" rows="2" class="input-shell">{{ old('documents_empty_text', data_get($portal, 'documents.empty_text')) }}</textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <p class="form-section-title">Media</p>
                        <div>
                            <label class="admin-label">Kicker</label>
                            <input name="media_kicker" value="{{ old('media_kicker', data_get($portal, 'media.kicker')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Title</label>
                            <input name="media_title" value="{{ old('media_title', data_get($portal, 'media.title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Description</label>
                            <textarea name="media_description" rows="3" class="input-shell">{{ old('media_description', data_get($portal, 'media.description')) }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="admin-label">Assets Label</label>
                                <input name="media_assets_label" value="{{ old('media_assets_label', data_get($portal, 'media.assets_label')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Photos Label</label>
                                <input name="media_photos_label" value="{{ old('media_photos_label', data_get($portal, 'media.photos_label')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Videos Label</label>
                                <input name="media_videos_label" value="{{ old('media_videos_label', data_get($portal, 'media.videos_label')) }}" class="input-shell">
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Empty State Title</label>
                                <input name="media_empty_title" value="{{ old('media_empty_title', data_get($portal, 'media.empty_title')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Empty State Description</label>
                                <textarea name="media_empty_description" rows="2" class="input-shell">{{ old('media_empty_description', data_get($portal, 'media.empty_description')) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <p class="form-section-title">Balance Sheet</p>
                        <div>
                            <label class="admin-label">Kicker</label>
                            <input name="balance_kicker" value="{{ old('balance_kicker', data_get($portal, 'balance.kicker')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Title</label>
                            <input name="balance_title" value="{{ old('balance_title', data_get($portal, 'balance.title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Description</label>
                            <textarea name="balance_description" rows="3" class="input-shell">{{ old('balance_description', data_get($portal, 'balance.description')) }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="admin-label">Contract Label</label>
                                <input name="balance_contract_label" value="{{ old('balance_contract_label', data_get($portal, 'balance.contract_label')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Paid Label</label>
                                <input name="balance_paid_label" value="{{ old('balance_paid_label', data_get($portal, 'balance.paid_label')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Outstanding Label</label>
                                <input name="balance_outstanding_label" value="{{ old('balance_outstanding_label', data_get($portal, 'balance.outstanding_label')) }}" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">History Title</label>
                            <input name="balance_history_title" value="{{ old('balance_history_title', data_get($portal, 'balance.history_title')) }}" class="input-shell">
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Empty State Title</label>
                                <input name="balance_empty_title" value="{{ old('balance_empty_title', data_get($portal, 'balance.empty_title')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Empty State Description</label>
                                <textarea name="balance_empty_description" rows="2" class="input-shell">{{ old('balance_empty_description', data_get($portal, 'balance.empty_description')) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid gap-8 xl:grid-cols-2">
            <div class="luxury-card space-y-6">
                <div>
                    <p class="section-label">Updates and Profile</p>
                    <h2 class="font-display text-3xl">Timeline and Account Copy</h2>
                </div>
                <div class="space-y-6">
                    <div class="form-section">
                        <p class="form-section-title">Updates</p>
                        <div>
                            <label class="admin-label">Title</label>
                            <input name="updates_title" value="{{ old('updates_title', data_get($portal, 'updates.title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Description</label>
                            <textarea name="updates_description" rows="2" class="input-shell">{{ old('updates_description', data_get($portal, 'updates.description')) }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Empty State Title</label>
                                <input name="updates_empty_title" value="{{ old('updates_empty_title', data_get($portal, 'updates.empty_title')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Empty State Description</label>
                                <textarea name="updates_empty_description" rows="2" class="input-shell">{{ old('updates_empty_description', data_get($portal, 'updates.empty_description')) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <p class="form-section-title">Profile</p>
                        <div>
                            <label class="admin-label">Title</label>
                            <input name="profile_title" value="{{ old('profile_title', data_get($portal, 'profile.title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Support Title</label>
                            <input name="profile_support_title" value="{{ old('profile_support_title', data_get($portal, 'profile.support_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Support Empty State</label>
                            <textarea name="profile_support_empty" rows="2" class="input-shell">{{ old('profile_support_empty', data_get($portal, 'profile.support_empty')) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Update Form Title</label>
                            <input name="profile_update_title" value="{{ old('profile_update_title', data_get($portal, 'profile.update_title')) }}" class="input-shell">
                        </div>
                    </div>
                </div>
            </div>

            <div class="luxury-card space-y-6">
                <div>
                    <p class="section-label">Support</p>
                    <h2 class="font-display text-3xl">Support Page Messaging</h2>
                    <p class="mt-2 text-sm text-muted-foreground">Update the manager area, support tiles, FAQ list, and ticket-section copy used throughout the portal support experience.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="admin-label">Page Title</label>
                        <input name="support_title" value="{{ old('support_title', data_get($portal, 'support.title')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Page Description</label>
                        <textarea name="support_description" rows="3" class="input-shell">{{ old('support_description', data_get($portal, 'support.description')) }}</textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Manager Section Title</label>
                            <input name="support_manager_title" value="{{ old('support_manager_title', data_get($portal, 'support.manager_title')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Manager Role Label</label>
                            <input name="support_manager_role" value="{{ old('support_manager_role', data_get($portal, 'support.manager_role')) }}" class="input-shell">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Phone Tile Label</label>
                            <input name="support_phone_label" value="{{ old('support_phone_label', data_get($portal, 'support.phone_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Phone Tile Note</label>
                            <input name="support_phone_note" value="{{ old('support_phone_note', data_get($portal, 'support.phone_note')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Email Tile Label</label>
                            <input name="support_email_label" value="{{ old('support_email_label', data_get($portal, 'support.email_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Email Tile Note</label>
                            <input name="support_email_note" value="{{ old('support_email_note', data_get($portal, 'support.email_note')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Hours Tile Label</label>
                            <input name="support_hours_label" value="{{ old('support_hours_label', data_get($portal, 'support.hours_label')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Hours Value</label>
                            <input name="support_hours_value" value="{{ old('support_hours_value', data_get($portal, 'support.hours_value')) }}" class="input-shell">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Hours Note</label>
                        <input name="support_hours_note" value="{{ old('support_hours_note', data_get($portal, 'support.hours_note')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Message Section Title</label>
                        <input name="support_message_title" value="{{ old('support_message_title', data_get($portal, 'support.message_title')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Message Button Label</label>
                        <input name="support_message_button" value="{{ old('support_message_button', data_get($portal, 'support.message_button')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">FAQ Section Title</label>
                        <input name="support_faq_title" value="{{ old('support_faq_title', data_get($portal, 'support.faq_title')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Portal FAQs</label>
                        <textarea name="support_faqs_text" rows="7" class="input-shell">{{ old('support_faqs_text', $supportFaqsText) }}</textarea>
                        <p class="mt-2 text-xs text-muted-foreground">Use <code>Question|Answer</code> per line.</p>
                    </div>
                    <div>
                        <label class="admin-label">Tickets Section Title</label>
                        <input name="support_tickets_title" value="{{ old('support_tickets_title', data_get($portal, 'support.tickets_title')) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Tickets Empty State</label>
                        <textarea name="support_tickets_empty" rows="2" class="input-shell">{{ old('support_tickets_empty', data_get($portal, 'support.tickets_empty')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky bottom-4 z-20 flex justify-end">
            <button class="luxury-btn-primary">Save Portal Settings</button>
        </div>
    </form>
</x-layouts.admin>
