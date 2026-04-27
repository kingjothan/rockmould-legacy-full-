<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users queried by role and status throughout admin and portal.
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index('status', 'idx_users_status');
        });

        // Projects are frequently filtered and sorted.
        Schema::table('projects', function (Blueprint $table) {
            $table->index('is_published', 'idx_projects_published');
            $table->index('is_featured', 'idx_projects_featured');
            $table->index('is_portfolio', 'idx_projects_portfolio');
            $table->index('status', 'idx_projects_status');
            $table->index('category', 'idx_projects_category');
            $table->index(['is_published', 'is_featured'], 'idx_projects_pub_feat');
        });

        // Project assignments are joined heavily in portal and admin client views.
        Schema::table('project_assignments', function (Blueprint $table) {
            $table->index('user_id', 'idx_pa_user');
            $table->index('project_id', 'idx_pa_project');
            $table->index('status', 'idx_pa_status');
        });

        // Posts power blog listing and detail pages.
        Schema::table('posts', function (Blueprint $table) {
            $table->index('is_published', 'idx_posts_published');
            $table->index('published_at', 'idx_posts_published_at');
            $table->index('featured', 'idx_posts_featured');
            $table->index('category', 'idx_posts_category');
        });

        // Testimonials appear on the home page and testimonials page.
        Schema::table('testimonials', function (Blueprint $table) {
            $table->index('is_visible', 'idx_testimonials_visible');
            $table->index('sort_order', 'idx_testimonials_sort');
        });

        // FAQs drive the public FAQ page and category filtering.
        Schema::table('faqs', function (Blueprint $table) {
            $table->index('is_published', 'idx_faqs_published');
            $table->index('category', 'idx_faqs_category');
            $table->index('sort_order', 'idx_faqs_sort');
        });

        // Career openings drive the public careers listing.
        Schema::table('career_openings', function (Blueprint $table) {
            $table->index('is_open', 'idx_careers_open');
            $table->index('published_at', 'idx_careers_published_at');
        });

        // Services appear on the home page and services page.
        Schema::table('services', function (Blueprint $table) {
            $table->index('is_published', 'idx_services_published');
            $table->index('sort_order', 'idx_services_sort');
        });

        // Media assets feed the gallery and portal media views.
        Schema::table('media_assets', function (Blueprint $table) {
            $table->index('visible_to_client', 'idx_media_visible');
            $table->index('project_id', 'idx_media_project');
            $table->index('is_featured', 'idx_media_featured');
            $table->index('sort_order', 'idx_media_sort');
        });

        // Documents power the portal document list.
        Schema::table('documents', function (Blueprint $table) {
            $table->index('project_id', 'idx_docs_project');
            $table->index('visible_to_client', 'idx_docs_visible');
        });

        // Payments are queried by assignment across admin and portal.
        Schema::table('payments', function (Blueprint $table) {
            $table->index('project_assignment_id', 'idx_payments_assignment');
        });

        // KYC requests are filtered by review status.
        Schema::table('kyc_requests', function (Blueprint $table) {
            $table->index('status', 'idx_kyc_status');
        });

        // Support tickets drive the portal support list.
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->index('user_id', 'idx_tickets_user');
            $table->index('status', 'idx_tickets_status');
        });

        // Activity logs are sorted chronologically in admin.
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('created_at', 'idx_activity_created');
        });

        // Project updates feed portal update streams.
        Schema::table('project_updates', function (Blueprint $table) {
            $table->index('project_id', 'idx_updates_project');
            $table->index('update_date', 'idx_updates_date');
        });

        // Team members appear on the public about page.
        Schema::table('team_members', function (Blueprint $table) {
            $table->index('is_visible', 'idx_team_visible');
            $table->index('sort_order', 'idx_team_sort');
        });

        // Timeline entries appear on the public about page.
        Schema::table('timeline_entries', function (Blueprint $table) {
            $table->index('is_visible', 'idx_timeline_visible');
            $table->index('sort_order', 'idx_timeline_sort');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_status');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('idx_projects_published');
            $table->dropIndex('idx_projects_featured');
            $table->dropIndex('idx_projects_portfolio');
            $table->dropIndex('idx_projects_status');
            $table->dropIndex('idx_projects_category');
            $table->dropIndex('idx_projects_pub_feat');
        });

        Schema::table('project_assignments', function (Blueprint $table) {
            $table->dropIndex('idx_pa_user');
            $table->dropIndex('idx_pa_project');
            $table->dropIndex('idx_pa_status');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('idx_posts_published');
            $table->dropIndex('idx_posts_published_at');
            $table->dropIndex('idx_posts_featured');
            $table->dropIndex('idx_posts_category');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('idx_testimonials_visible');
            $table->dropIndex('idx_testimonials_sort');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex('idx_faqs_published');
            $table->dropIndex('idx_faqs_category');
            $table->dropIndex('idx_faqs_sort');
        });

        Schema::table('career_openings', function (Blueprint $table) {
            $table->dropIndex('idx_careers_open');
            $table->dropIndex('idx_careers_published_at');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('idx_services_published');
            $table->dropIndex('idx_services_sort');
        });

        Schema::table('media_assets', function (Blueprint $table) {
            $table->dropIndex('idx_media_visible');
            $table->dropIndex('idx_media_project');
            $table->dropIndex('idx_media_featured');
            $table->dropIndex('idx_media_sort');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('idx_docs_project');
            $table->dropIndex('idx_docs_visible');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_assignment');
        });

        Schema::table('kyc_requests', function (Blueprint $table) {
            $table->dropIndex('idx_kyc_status');
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropIndex('idx_tickets_user');
            $table->dropIndex('idx_tickets_status');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_activity_created');
        });

        Schema::table('project_updates', function (Blueprint $table) {
            $table->dropIndex('idx_updates_project');
            $table->dropIndex('idx_updates_date');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropIndex('idx_team_visible');
            $table->dropIndex('idx_team_sort');
        });

        Schema::table('timeline_entries', function (Blueprint $table) {
            $table->dropIndex('idx_timeline_visible');
            $table->dropIndex('idx_timeline_sort');
        });
    }
};
