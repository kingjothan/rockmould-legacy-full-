<?php

use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\CmsController as AdminCmsController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\FormController as AdminFormController;
use App\Http\Controllers\Admin\KycController as AdminKycController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UpdateController as AdminUpdateController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Portal\AuthController as PortalAuthController;
use App\Http\Controllers\Portal\BalanceController as PortalBalanceController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\DocumentController as PortalDocumentController;
use App\Http\Controllers\Portal\MediaController as PortalMediaController;
use App\Http\Controllers\Portal\ProfileController as PortalProfileController;
use App\Http\Controllers\Portal\ProjectController as PortalProjectController;
use App\Http\Controllers\Portal\SupportController as PortalSupportController;
use App\Http\Controllers\Portal\UpdateController as PortalUpdateController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\SiteController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/uploaded-media/{path}', function (string $path) {
    $normalized = trim(str_replace('\\', '/', $path), '/');

    abort_if($normalized === '' || Str::contains($normalized, ['..']), 404);
    abort_unless(Storage::disk('public')->exists($normalized), 404);

    return Storage::disk('public')->response($normalized, null, [
        'Cache-Control' => 'public, max-age=31536000, immutable',
    ]);
})->where('path', '.*')->name('uploaded-media.show');

Route::get('/public/{path?}', function (?string $path = null): RedirectResponse {
    $target = $path ? '/'.ltrim($path, '/') : '/';

    return redirect($target);
})->where('path', '.*');

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/projects', 'projects')->name('projects.index');
    Route::get('/projects/{project:slug}', 'project')->name('projects.show');
    Route::get('/portfolio', 'portfolio')->name('portfolio');
    Route::get('/blog', 'blog')->name('blog.index');
    Route::get('/blog/{post:slug}', 'post')->name('blog.show');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/careers', 'careers')->name('careers');
    Route::get('/careers/{careerOpening:slug}', 'career')->name('careers.show');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/partnerships', 'partnerships')->name('partnerships');
    Route::get('/testimonials', 'testimonials')->name('testimonials');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/privacy', 'privacy')->name('privacy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/coming-soon', 'comingSoon')->name('utility.coming-soon');
    Route::get('/maintenance', 'maintenance')->name('utility.maintenance');
});

Route::controller(PublicFormController::class)->group(function () {
    Route::post('/contact', 'contact')->name('forms.contact');
    Route::post('/newsletter', 'newsletter')->name('forms.newsletter');
    Route::post('/partnerships', 'partnership')->name('forms.partnership');
    Route::post('/projects/{project:slug}/inquire', 'projectInquiry')->name('forms.project-inquiry');
    Route::post('/projects/{project:slug}/brochure', [\App\Http\Controllers\BrochureController::class, 'store'])->name('brochure.download');
    Route::post('/careers/{careerOpening:slug}/apply', 'career')->name('forms.career');
    Route::post('/testimonials', 'review')->name('forms.review');
});

Route::prefix('consultation')->name('consultations.')->controller(ConsultationController::class)->group(function () {
    Route::get('/', 'create')->name('create');
    Route::get('/availability', 'availability')->middleware('throttle:consultation-availability')->name('availability');
    Route::post('/', 'store')->middleware('throttle:consultation-bookings')->name('store');
    Route::get('/success/{reference}', 'success')->name('success');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [PortalAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [PortalAuthController::class, 'login'])->middleware('throttle:portal-otp-request')->name('authenticate');
        Route::post('/otp', [PortalAuthController::class, 'verifyOtp'])->middleware('throttle:portal-otp-verify')->name('otp.verify');
        Route::post('/otp/resend', [PortalAuthController::class, 'resendOtp'])->middleware('throttle:portal-otp-request')->name('otp.resend');
        Route::post('/kyc', [PortalAuthController::class, 'kyc'])->name('kyc.store');
    });

    Route::middleware(['auth', 'role:client'])->group(function () {
        Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [PortalDashboardController::class, 'index'])->name('dashboard');
        Route::get('/projects', [PortalProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{assignment}', [PortalProjectController::class, 'show'])->name('projects.show');
        Route::get('/documents', [PortalDocumentController::class, 'index'])->name('documents');
        Route::get('/documents/{document}/download', [PortalDocumentController::class, 'download'])->name('documents.download');
        Route::get('/media', [PortalMediaController::class, 'index'])->name('media');
        Route::get('/media/{mediaAsset}/download', [PortalMediaController::class, 'download'])->name('media.download');
        Route::get('/balance', [PortalBalanceController::class, 'index'])->name('balance');
        Route::get('/balance/{payment}/receipt', [PortalBalanceController::class, 'receipt'])->name('balance.receipt');
        Route::post('/balance/{payment}/email-receipt', [PortalBalanceController::class, 'emailReceipt'])->name('balance.email-receipt');
        Route::get('/balance/statement/{assignment}', [PortalBalanceController::class, 'statement'])->name('balance.statement');
        Route::get('/updates', [PortalUpdateController::class, 'index'])->name('updates');
        Route::get('/profile', [PortalProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [PortalProfileController::class, 'update'])->name('profile.update');
        Route::get('/support', [PortalSupportController::class, 'index'])->name('support');
        Route::post('/support', [PortalSupportController::class, 'store'])->name('support.store');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('authenticate');
    });

    Route::middleware(['auth', 'role:admin,editor,project_manager,finance,content_manager'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:admin')->group(function () {
            Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
            Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store');
            Route::get('/clients/{user}', [AdminClientController::class, 'show'])->name('clients.show');
            Route::put('/clients/{user}', [AdminClientController::class, 'update'])->name('clients.update');
            Route::delete('/clients/{user}', [AdminClientController::class, 'destroy'])->name('clients.destroy');
        });

        Route::middleware('role:admin')->group(function () {
            Route::get('/kyc', [AdminKycController::class, 'index'])->name('kyc.index');
            Route::put('/kyc/{kycRequest}', [AdminKycController::class, 'update'])->name('kyc.update');
        });

        Route::middleware('role:admin,project_manager')->group(function () {
            Route::get('/consultations', [AdminConsultationController::class, 'index'])->name('consultations.index');
            Route::post('/consultations/settings', [AdminConsultationController::class, 'updateSettings'])->name('consultations.settings.update');
            Route::post('/consultations/availability', [AdminConsultationController::class, 'storeAvailability'])->name('consultations.availability.store');
            Route::put('/consultations/availability/{consultationAvailability}', [AdminConsultationController::class, 'updateAvailability'])->name('consultations.availability.update');
            Route::delete('/consultations/availability/{consultationAvailability}', [AdminConsultationController::class, 'destroyAvailability'])->name('consultations.availability.destroy');
            Route::post('/consultations/blocked-dates', [AdminConsultationController::class, 'storeBlockedDate'])->name('consultations.blocked-dates.store');
            Route::delete('/consultations/blocked-dates/{consultationBlockedDate}', [AdminConsultationController::class, 'destroyBlockedDate'])->name('consultations.blocked-dates.destroy');
            Route::get('/consultations/{consultationBooking}', [AdminConsultationController::class, 'show'])->name('consultations.show');
            Route::put('/consultations/{consultationBooking}', [AdminConsultationController::class, 'update'])->name('consultations.update');

            Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
            Route::post('/projects', [AdminProjectController::class, 'store'])->name('projects.store');
            Route::get('/projects/{project}', [AdminProjectController::class, 'show'])->name('projects.show');
            Route::match(['PUT', 'POST'], '/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');
            Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');
            Route::post('/projects/{project}/assignments', [AdminProjectController::class, 'storeAssignment'])->name('projects.assignments.store');
            Route::match(['PUT', 'POST'], '/projects/{project}/assignments/{assignment}', [AdminProjectController::class, 'updateAssignment'])->name('projects.assignments.update');
            Route::get('/brochure-downloads', [\App\Http\Controllers\Admin\BrochureDownloadController::class, 'index'])->name('brochure-downloads.index');
        });

        Route::middleware('role:admin,finance')->group(function () {
            Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
            Route::post('/payments', [AdminPaymentController::class, 'store'])->name('payments.store');
            Route::put('/payments/assignments/{assignment}', [AdminPaymentController::class, 'updateAssignmentFinancials'])->name('payments.assignments.update');
            Route::get('/payments/{payment}/receipt', [AdminPaymentController::class, 'receipt'])->name('payments.receipt');
        });

        Route::middleware('role:admin,project_manager')->group(function () {
            Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
            Route::post('/documents', [AdminDocumentController::class, 'store'])->name('documents.store');
            Route::put('/documents/{document}', [AdminDocumentController::class, 'update'])->name('documents.update');
            Route::delete('/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
        });

        Route::middleware('role:admin,project_manager,content_manager')->group(function () {
            Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
            Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
            Route::match(['PUT', 'POST'], '/media/{mediaAsset}', [AdminMediaController::class, 'update'])->name('media.update');
            Route::delete('/media/{mediaAsset}', [AdminMediaController::class, 'destroy'])->name('media.destroy');

            Route::get('/updates', [AdminUpdateController::class, 'index'])->name('updates.index');
            Route::post('/updates', [AdminUpdateController::class, 'store'])->name('updates.store');
            Route::put('/updates/{projectUpdate}', [AdminUpdateController::class, 'update'])->name('updates.update');
            Route::delete('/updates/{projectUpdate}', [AdminUpdateController::class, 'destroy'])->name('updates.destroy');
        });

        Route::middleware('role:admin,editor,content_manager')->group(function () {
            Route::get('/cms/homepage', [AdminCmsController::class, 'homepage'])->name('cms.homepage');
            Route::match(['PUT', 'POST'], '/cms/homepage', [AdminCmsController::class, 'updateHomepage'])->name('cms.homepage.update');
            Route::get('/cms/pages', [AdminCmsController::class, 'pages'])->name('cms.pages');
            Route::match(['PUT', 'POST'], '/cms/pages', [AdminCmsController::class, 'updatePages'])->name('cms.pages.update');
            Route::get('/cms/admin-login', [AdminCmsController::class, 'adminLogin'])->name('cms.admin-login');
            Route::match(['PUT', 'POST'], '/cms/admin-login', [AdminCmsController::class, 'updateAdminLogin'])->name('cms.admin-login.update');
            Route::get('/cms/portal', [AdminCmsController::class, 'portal'])->name('cms.portal');
            Route::match(['PUT', 'POST'], '/cms/portal', [AdminCmsController::class, 'updatePortal'])->name('cms.portal.update');
            Route::get('/cms/about', [AdminCmsController::class, 'about'])->name('cms.about');
            Route::match(['PUT', 'POST'], '/cms/about', [AdminCmsController::class, 'updateAbout'])->name('cms.about.update');
            Route::post('/cms/about/team', [AdminCmsController::class, 'storeTeamMember'])->name('cms.about.team.store');
            Route::put('/cms/about/team/{teamMember}', [AdminCmsController::class, 'updateTeamMember'])->name('cms.about.team.update');
            Route::delete('/cms/about/team/{teamMember}', [AdminCmsController::class, 'destroyTeamMember'])->name('cms.about.team.destroy');
            Route::post('/cms/about/timeline', [AdminCmsController::class, 'storeTimelineEntry'])->name('cms.about.timeline.store');
            Route::put('/cms/about/timeline/{timelineEntry}', [AdminCmsController::class, 'updateTimelineEntry'])->name('cms.about.timeline.update');
            Route::delete('/cms/about/timeline/{timelineEntry}', [AdminCmsController::class, 'destroyTimelineEntry'])->name('cms.about.timeline.destroy');
            Route::get('/cms/services', [AdminCmsController::class, 'services'])->name('cms.services');
            Route::post('/cms/services', [AdminCmsController::class, 'storeService'])->name('cms.services.store');
            Route::put('/cms/services/{service}', [AdminCmsController::class, 'updateService'])->name('cms.services.update');
            Route::delete('/cms/services/{service}', [AdminCmsController::class, 'destroyService'])->name('cms.services.destroy');
            Route::get('/cms/projects', [AdminCmsController::class, 'projectListings'])->name('cms.projects');
            Route::put('/cms/projects/{project}', [AdminCmsController::class, 'updateProjectListing'])->name('cms.projects.update');
            Route::get('/cms/portfolio', [AdminCmsController::class, 'portfolio'])->name('cms.portfolio');
            Route::put('/cms/portfolio', [AdminCmsController::class, 'updatePortfolio'])->name('cms.portfolio.update');
            Route::get('/cms/footer', [AdminCmsController::class, 'footer'])->name('cms.footer');
            Route::match(['PUT', 'POST'], '/cms/footer', [AdminCmsController::class, 'updateFooter'])->name('cms.footer.update');
            Route::get('/cms/blog', [AdminCmsController::class, 'blog'])->name('cms.blog');
            Route::post('/cms/blog', [AdminCmsController::class, 'storePost'])->name('cms.blog.store');
            Route::put('/cms/blog/{post}', [AdminCmsController::class, 'updatePost'])->name('cms.blog.update');
            Route::delete('/cms/blog/{post}', [AdminCmsController::class, 'destroyPost'])->name('cms.blog.destroy');
            Route::get('/cms/testimonials', [AdminCmsController::class, 'testimonials'])->name('cms.testimonials');
            Route::post('/cms/testimonials', [AdminCmsController::class, 'storeTestimonial'])->name('cms.testimonials.store');
            Route::put('/cms/testimonials/{testimonial}', [AdminCmsController::class, 'updateTestimonial'])->name('cms.testimonials.update');
            Route::get('/cms/faq', [AdminCmsController::class, 'faq'])->name('cms.faq');
            Route::post('/cms/faq', [AdminCmsController::class, 'storeFaq'])->name('cms.faq.store');
            Route::put('/cms/faq/{faq}', [AdminCmsController::class, 'updateFaq'])->name('cms.faq.update');
            Route::delete('/cms/faq/{faq}', [AdminCmsController::class, 'destroyFaq'])->name('cms.faq.destroy');
            Route::get('/cms/careers', [AdminCmsController::class, 'careers'])->name('cms.careers');
            Route::post('/cms/careers', [AdminCmsController::class, 'storeCareer'])->name('cms.careers.store');
            Route::put('/cms/careers/{careerOpening}', [AdminCmsController::class, 'updateCareer'])->name('cms.careers.update');
            Route::delete('/cms/careers/{careerOpening}', [AdminCmsController::class, 'destroyCareer'])->name('cms.careers.destroy');
        });

        Route::middleware('role:admin,editor,content_manager')->group(function () {
            Route::get('/forms', [AdminFormController::class, 'index'])->name('forms.index');
            Route::get('/newsletter', [AdminNewsletterController::class, 'index'])->name('newsletter.index');
            Route::post('/newsletter/send', [AdminNewsletterController::class, 'send'])->name('newsletter.send');
            Route::put('/newsletter/{subscriber}', [AdminNewsletterController::class, 'destroy'])->name('newsletter.unsubscribe');
            Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        });

        Route::middleware('role:admin')->group(function () {
            Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
            Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
            Route::put('/roles/{user}', [AdminRoleController::class, 'update'])->name('roles.update');
            Route::get('/activity', [AdminActivityController::class, 'index'])->name('activity.index');
        });
    });
});
