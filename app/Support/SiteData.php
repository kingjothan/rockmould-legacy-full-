<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SiteData
{
    public static function company(): array
    {
        return self::setting('company', [
            'name' => 'Rockmould Limited',
            'tagline' => 'Premium Real Estate and Construction',
            'phone' => '+234 123 456 7890',
            'email' => 'info@rockmould.com',
            'address' => '12 Admiralty Way, Victoria Island, Lagos, Nigeria',
            'hours' => 'Mon - Fri: 9:00 AM - 6:00 PM',
            'support_phone' => '+234 801 234 5678',
            'support_email' => 'info@rockmould.com',
            'socials' => [
                ['label' => 'Instagram', 'url' => '#'],
                ['label' => 'LinkedIn', 'url' => '#'],
                ['label' => 'YouTube', 'url' => '#'],
            ],
            'public_copy_scale' => '1',
            'public_heading_scale' => '1',
            'portal_copy_scale' => '1',
            'portal_heading_scale' => '1',
            'admin_copy_scale' => '1',
            'admin_heading_scale' => '1',
        ]);
    }

    public static function portfolio(): array
    {
        return self::setting('portfolio', [
            'hero_subtitle' => 'Proven Excellence',
            'hero_title' => 'Our Portfolio',
            'hero_description' => 'A showcase of our finest developments, construction achievements, and the impact we have made across Nigeria\'s property landscape.',
            'metrics' => [
                ['label' => 'Projects', 'value' => '150+'],
                ['label' => 'Portfolio Value', 'value' => 'NGN 50B'],
                ['label' => 'On-Time Delivery', 'value' => '98%'],
                ['label' => 'Happy Clients', 'value' => '500+'],
            ],
            'client_success_label' => 'Client Success',
            'client_success_title' => 'What Clients Remember Most',
        ]);
    }

    public static function homepage(): array
    {
        return self::setting('homepage', [
            'hero_label' => 'Premium Real Estate & Construction',
            'hero_title' => 'Building Legacies That Endure',
            'hero_description' => 'Rockmould Limited delivers world-class real estate developments and construction projects for investors, corporate clients, and high-value private buyers.',
            'hero_media_path' => 'images/project-1.jpg',
            'hero_primary_cta' => 'Explore Projects',
            'hero_secondary_cta' => 'Book Consultation',
            'hero_portal_cta' => 'Client Portal',
            'feature_image' => 'images/project-4.jpg',
            'featured_section' => [
                'label' => 'Featured Developments',
                'title' => 'Exceptional Projects, Extraordinary Standards',
                'description' => 'From premium residences to landmark commercial spaces, every Rockmould project embodies craftsmanship and vision.',
                'cta' => 'View All Projects',
            ],
            'services_section' => [
                'label' => 'Our Services',
                'title' => 'Comprehensive Real Estate & Construction Solutions',
                'description' => 'End-to-end expertise across every facet of property development.',
            ],
            'why_section' => [
                'label' => 'Why Rockmould',
                'title' => 'A Legacy of Excellence in Every Foundation',
                'cta' => 'Learn More',
            ],
            'process_section' => [
                'label' => 'Our Process',
                'title' => 'From Vision to Reality',
                'description' => 'A refined process that ensures every project is delivered with precision and excellence.',
            ],
            'testimonials_section' => [
                'label' => 'Client Stories',
                'title' => 'Trusted by Visionary Clients',
            ],
            'insights_section' => [
                'label' => 'Insights',
                'title' => 'Latest from Rockmould',
                'description' => 'Market insights, project updates, and thought leadership from our team.',
            ],
            'portal_section' => [
                'title' => 'Client Portal',
                'description' => 'Track your projects, view documents, monitor payments, and stay updated in one secure, premium portal.',
                'cta' => 'Access Portal',
            ],
            'final_cta' => [
                'title' => 'Ready to Build Your Legacy?',
                'description' => 'Let\'s discuss your next premium development or investment opportunity.',
                'primary_cta' => 'Schedule Consultation',
                'secondary_cta' => 'Browse Projects',
            ],
            'stats' => [
                ['label' => 'Projects Delivered', 'value' => '150+'],
                ['label' => 'Years of Excellence', 'value' => '20+'],
                ['label' => 'Happy Clients', 'value' => '500+'],
                ['label' => 'Portfolio Value', 'value' => 'NGN 50B'],
            ],
            'why_choose' => [
                'Uncompromising quality and precision craftsmanship',
                'On-time delivery with transparent communication',
                'Premium materials and world-class engineering standards',
                'Dedicated client relationship management',
                'Comprehensive post-delivery support',
            ],
            'process' => [
                ['number' => '01', 'title' => 'Discovery', 'description' => 'Understanding your vision, objectives, and requirements.'],
                ['number' => '02', 'title' => 'Planning', 'description' => 'Detailed architectural and engineering planning with commercial clarity.'],
                ['number' => '03', 'title' => 'Development', 'description' => 'Precision construction and disciplined reporting across every milestone.'],
                ['number' => '04', 'title' => 'Delivery', 'description' => 'Structured handover, documentation, and long-term client support.'],
            ],
        ]);
    }

    public static function publicPages(): array
    {
        return self::setting('public_pages', [
            'services' => [
                'hero_subtitle' => 'Our Services',
                'hero_title' => 'Comprehensive Solutions for Premium Properties',
                'hero_description' => 'From vision to delivery, Rockmould offers a complete suite of real estate and construction services engineered for excellence.',
                'hero_media_path' => 'images/project-3.jpg',
                'cta_title' => 'Need a Tailored Solution?',
                'cta_description' => 'Every project is unique. Let us understand your needs and craft the perfect approach.',
                'cta_button' => 'Book a Consultation',
            ],
            'projects' => [
                'hero_subtitle' => 'Premium Developments',
                'hero_title' => 'Our Projects',
                'hero_description' => 'Explore our portfolio of exceptional real estate developments across prime locations.',
                'hero_media_path' => 'images/project-2.jpg',
                'empty_title' => 'No Projects Found',
                'empty_description' => 'Try adjusting your filters or search terms.',
                'empty_button' => 'Clear Filters',
            ],
            'blog' => [
                'hero_subtitle' => 'Blog',
                'hero_title' => 'Insights & Resources',
                'hero_description' => 'Expert perspectives on real estate, construction, and investment from the Rockmould team.',
                'hero_media_path' => 'images/project-3.jpg',
                'featured_label' => 'Featured Article',
                'empty_title' => 'No Insights Found',
                'empty_description' => 'Try a different keyword or switch categories to explore more Rockmould insights.',
                'subscribe_title' => 'Subscribe to Our Insights',
                'subscribe_description' => 'Get exclusive market intelligence, project updates, and advisory notes delivered to your inbox.',
                'subscribe_button' => 'Subscribe',
            ],
            'faq' => [
                'hero_subtitle' => 'Support and Clarity',
                'hero_title' => 'Frequently Asked Questions',
                'hero_description' => 'Answers to common questions about our projects, services, portal access, and delivery model.',
                'hero_media_path' => 'images/project-2.jpg',
                'empty_title' => 'No Results Found',
                'empty_description' => 'Try a different search term or browse another category.',
            ],
            'careers' => [
                'hero_subtitle' => 'Careers',
                'hero_title' => 'Build Your Career with Rockmould',
                'hero_description' => 'Join a team of professionals dedicated to delivering excellence in real estate and construction.',
                'hero_media_path' => 'images/project-4.jpg',
                'benefits' => [
                    ['icon' => 'trending-up', 'title' => 'Growth & Development', 'description' => 'Continuous learning opportunities and real career progression across projects and operations.'],
                    ['icon' => 'shield-check', 'title' => 'Health & Wellbeing', 'description' => 'A serious work environment supported by stability, structure, and long-term team care.'],
                    ['icon' => 'users', 'title' => 'Collaborative Culture', 'description' => 'Work closely with designers, engineers, finance teams, and delivery leaders on landmark work.'],
                    ['icon' => 'briefcase', 'title' => 'Competitive Package', 'description' => 'Strong compensation and responsibility matched to high standards of ownership and execution.'],
                ],
                'openings_title' => 'Open Positions',
                'openings_description' => 'Explore current opportunities across projects, finance, client management, and operational delivery.',
                'open_application_kicker' => 'Can’t find your role?',
                'open_application_title' => 'Send an open application.',
                'open_application_description' => 'We welcome experienced professionals across development, construction, finance, design, and client operations.',
                'open_application_button' => 'Send Open Application',
                'empty_title' => 'No Open Positions',
                'empty_description' => 'There are no active roles right now. Check back soon or send an open application.',
            ],
            'contact' => [
                'hero_subtitle' => 'Contact Us',
                'hero_title' => 'Get in Touch',
                'hero_description' => 'Whether you’re interested in projects, services, investment opportunities, or partnerships, we’d love to hear from you.',
                'hero_media_path' => 'images/project-1.jpg',
                'info_title' => 'Contact Information',
                'quick_access_title' => 'Quick Access',
                'form_title' => 'Send Us a Message',
            ],
            'partnerships' => [
                'hero_subtitle' => 'Strategic Capital and Project Relationships',
                'hero_title' => 'Partnerships and Investment',
                'hero_description' => 'Rockmould works with investors, landowners, and project partners who value clarity, governance, and premium execution.',
                'hero_media_path' => 'images/project-4.jpg',
                'reasons_title' => 'Why Invest With Rockmould',
                'reasons' => [
                    'We prioritize disciplined underwriting, delivery visibility, and premium product positioning in prime markets.',
                    'Our platform is suited to co-development, strategic capital partnerships, project mandates, and land-led opportunities.',
                    'Investors and partners benefit from reporting clarity, structured execution, and a portfolio anchored in trust.',
                ],
                'metrics' => [
                    ['label' => 'Portfolio Value', 'value' => 'NGN 50B'],
                    ['label' => 'Prime District Projects', 'value' => '12'],
                    ['label' => 'Years of Delivery', 'value' => '20+'],
                ],
                'form_title' => 'Request a Private Consultation',
                'opportunities_label' => 'Current Opportunities',
                'opportunities_title' => 'Selected Projects',
                'submit_button' => 'Submit Inquiry',
            ],
            'testimonials' => [
                'hero_subtitle' => 'Testimonials',
                'hero_title' => 'Trust Built Through Delivery',
                'hero_description' => 'Selected stories from clients who value clarity, quality, and disciplined project execution.',
                'hero_media_path' => 'images/project-2.jpg',
            ],
            'gallery' => [
                'hero_subtitle' => 'Gallery',
                'hero_title' => 'Project and Media Gallery',
                'hero_description' => 'Construction photography, project visuals, and client-facing media updates from across the Rockmould portfolio.',
                'hero_media_path' => 'images/project-3.jpg',
                'empty_title' => 'No Media Found',
                'empty_description' => 'There are no gallery assets matching your selected filter.',
            ],
        ]);
    }

    public static function publicPage(string $key): array
    {
        return self::publicPages()[$key] ?? [];
    }

    public static function portalContent(): array
    {
        return self::setting('portal_content', [
            'login' => [
                'media_path' => null,
                'marketing_title' => 'Your Projects, At Your Fingertips',
                'marketing_highlight' => 'At Your Fingertips',
                'features' => [
                    'Track project progress in real-time',
                    'Access documents and statements',
                    'View payment history and balances',
                    'Secure and private portal access',
                ],
                'pending_title' => 'Request Submitted',
                'pending_description' => 'Your KYC request is under review.',
                'pending_helper' => 'Our team will verify your details and set up your account. This usually takes 1-2 business days.',
                'unrecognized_title' => 'Email Not Recognized',
                'unrecognized_description' => 'This email is not registered. Submit a KYC request to get started.',
                'unrecognized_button' => 'Submit KYC Request',
                'kyc_title' => 'KYC Request',
                'kyc_description' => 'Fill in your details. Our team will review and set up your account.',
                'welcome_title' => 'Welcome Back',
                'welcome_description' => 'Enter your registered email address to access your portal.',
                'access_button' => 'Access Portal',
                'request_access_text' => 'Don’t have an account?',
                'request_access_link' => 'Request Access',
                'support_text' => 'Need help?',
                'support_link' => 'Contact Support',
            ],
            'dashboard' => [
                'welcome_title' => 'Welcome Back, :name',
                'description' => 'Here’s an overview of your projects and activity.',
                'active_projects_label' => 'Active Projects',
                'amount_paid_label' => 'Amount Paid',
                'outstanding_label' => 'Outstanding',
                'documents_label' => 'Documents',
                'projects_title' => 'My Projects',
                'projects_link' => 'View All',
                'projects_empty' => 'No projects have been assigned to your portal yet.',
                'updates_title' => 'Recent Updates',
                'updates_empty' => 'No recent project updates yet.',
                'documents_title' => 'Recent Documents',
                'documents_empty' => 'No documents have been released yet.',
            ],
            'projects' => [
                'kicker' => 'Client Portfolio',
                'title' => 'Track every Rockmould project in one place.',
                'description' => 'Monitor delivery progress, payment position, and the latest activity across your assigned developments.',
                'filter_button' => 'Filter Projects',
                'assigned_label' => 'Assigned Projects',
                'assigned_description' => 'Every active and completed client allocation.',
                'paid_label' => 'Amount Paid',
                'paid_description' => 'Captured across all linked projects.',
                'outstanding_label' => 'Outstanding',
                'outstanding_description' => 'Current balance still open for settlement.',
                'empty_kicker' => 'No Projects Yet',
                'empty_title' => 'Your portfolio will appear here.',
                'empty_description' => 'Once Rockmould assigns a development to your account, you will see the project summary, financial position, and latest updates here.',
                'card_button' => 'View Details',
            ],
            'documents' => [
                'title' => 'Documents',
                'description' => 'Search, review, preview, and download the documents tied to your Rockmould projects.',
                'empty_text' => 'No documents found.',
            ],
            'media' => [
                'kicker' => 'Client Portal Media',
                'title' => 'Project gallery and progress visuals',
                'description' => 'Browse client-visible photos and videos for each assigned development, including shared project updates and assignment-specific media.',
                'assets_label' => 'Assets',
                'photos_label' => 'Photos',
                'videos_label' => 'Videos',
                'empty_title' => 'No Media Found',
                'empty_description' => 'No photos or videos match your current filters.',
            ],
            'balance' => [
                'kicker' => 'Financial Overview',
                'title' => 'A clear view of payments, balances, and next obligations.',
                'description' => 'Review project value, total paid, current outstanding balance, and each recorded installment tied to your Rockmould account.',
                'contract_label' => 'Total Project Value',
                'paid_label' => 'Total Paid',
                'outstanding_label' => 'Outstanding Balance',
                'history_title' => 'Payment History',
                'empty_title' => 'No financial records yet.',
                'empty_description' => 'Your balance sheet will become available after the first project assignment is activated on your portal.',
            ],
            'updates' => [
                'title' => 'Project Updates',
                'description' => 'Chronological updates across all your projects',
                'empty_title' => 'No updates yet.',
                'empty_description' => 'Project updates will appear here as soon as the Rockmould team publishes them to your account.',
            ],
            'profile' => [
                'title' => 'My Profile',
                'support_title' => 'Support Contact',
                'support_empty' => 'A dedicated account manager will appear here once a project has been assigned to your portal.',
                'update_title' => 'Update Profile',
            ],
            'support' => [
                'title' => 'Support',
                'description' => 'Get help with your portal, projects, or account',
                'manager_title' => 'Your Account Manager',
                'manager_role' => 'Senior Client Relations Manager',
                'phone_label' => 'Call Us',
                'phone_note' => 'Mon-Fri, 9am-5pm',
                'email_label' => 'Email',
                'email_note' => '24hr response time',
                'hours_label' => 'Office Hours',
                'hours_value' => '9:00 AM - 5:00 PM',
                'hours_note' => 'Monday to Friday',
                'message_title' => 'Send a Message',
                'message_button' => 'Send Message',
                'faq_title' => 'Frequently Asked Questions',
                'faqs' => [
                    ['question' => 'How do I check my project progress?', 'answer' => 'Navigate to My Projects from the sidebar, select your project, and view the progress bar and timeline updates.'],
                    ['question' => 'How can I download my documents?', 'answer' => 'Go to the Documents page, find the file you need, and click the preview or download action.'],
                    ['question' => 'How are payments tracked?', 'answer' => 'Visit the Balance Sheet page to see all payments, outstanding amounts, and installment history.'],
                    ['question' => 'What if I notice incorrect information?', 'answer' => 'Contact your assigned account manager or submit a message through this support page.'],
                ],
                'tickets_title' => 'Previous Tickets',
                'tickets_empty' => 'No support tickets yet.',
            ],
        ]);
    }

    public static function portalPage(string $key): array
    {
        return self::portalContent()[$key] ?? [];
    }

    public static function about(): array
    {
        return self::setting('about', [
            'hero_subtitle' => 'About Rockmould',
            'hero_title' => 'Building Tomorrow\'s Landmarks, Today',
            'hero_description' => 'Two decades of transforming visions into enduring structures. We build with purpose, precision, and an unwavering commitment to excellence.',
            'hero_media_path' => 'images/project-4.jpg',
            'story_label' => 'Our Story',
            'story_title' => 'From Vision to Legacy',
            'story' => [
                'Founded in 2004, Rockmould Limited began with a singular vision: to redefine the standards of real estate development and construction in Nigeria.',
                'Today, Rockmould stands as a symbol of quality, trust, and innovation in the Nigerian property landscape, serving clients who demand serious execution and premium value.',
            ],
            'story_image' => 'images/project-2.jpg',
            'vision_title' => 'Our Vision',
            'vision' => 'To be Africa\'s most trusted and admired real estate and construction company, known for developments that stand the test of time.',
            'mission_title' => 'Our Mission',
            'mission' => 'To create exceptional living and working environments through disciplined execution, refined design, and strong client stewardship.',
            'values_label' => 'Core Values',
            'values_title' => 'The Pillars of Our Identity',
            'values' => [
                ['title' => 'Integrity', 'description' => 'Transparent dealings and honest partnerships built on trust.'],
                ['title' => 'Excellence', 'description' => 'Uncompromising quality in every project we undertake.'],
                ['title' => 'Commitment', 'description' => 'Dedicated to delivering beyond expectations, every time.'],
                ['title' => 'Partnership', 'description' => 'Long-term relationships with clients, investors, and communities.'],
            ],
            'team_label' => 'Leadership',
            'team_title' => 'The Team Behind the Vision',
            'timeline_label' => 'Our Journey',
            'timeline_title' => 'Milestones of Excellence',
            'metrics' => [
                ['label' => 'Projects', 'value' => '150+'],
                ['label' => 'Years', 'value' => '20+'],
                ['label' => 'Clients', 'value' => '500+'],
                ['label' => 'Professionals', 'value' => '350+'],
            ],
            'cta_title' => 'Partner with Excellence',
            'cta_description' => 'Whether you\'re an investor, client, or partner, let\'s create something extraordinary together.',
            'cta_primary' => 'Schedule Consultation',
            'cta_secondary' => 'View Projects',
        ]);
    }

    public static function publicNavigation(): array
    {
        return [
            ['label' => 'Home', 'route' => 'home'],
            ['label' => 'About', 'route' => 'about'],
            [
                'label' => 'Services',
                'route' => 'services',
                'children' => [
                    ['label' => 'Real Estate Development', 'href' => route('services').'#real-estate-development'],
                    ['label' => 'Construction & Execution', 'href' => route('services').'#construction-project-execution'],
                    ['label' => 'Property Advisory', 'href' => route('services').'#property-advisory'],
                    ['label' => 'Investment Support', 'href' => route('services').'#investment-support'],
                    ['label' => 'Luxury Property Delivery', 'href' => route('services').'#luxury-property-delivery'],
                ],
            ],
            ['label' => 'Projects', 'route' => 'projects.index'],
            ['label' => 'Portfolio', 'route' => 'portfolio'],
            ['label' => 'Insights', 'route' => 'blog.index'],
            ['label' => 'Careers', 'route' => 'careers'],
            ['label' => 'Contact', 'route' => 'contact'],
        ];
    }

    public static function footerGroups(): array
    {
        return [
            [
                'title' => 'Company',
                'links' => [
                    ['label' => 'About Us', 'url' => route('about')],
                    ['label' => 'Our Services', 'url' => route('services')],
                    ['label' => 'Portfolio', 'url' => route('portfolio')],
                    ['label' => 'Careers', 'url' => route('careers')],
                    ['label' => 'Contact', 'url' => route('contact')],
                ],
            ],
            [
                'title' => 'Projects',
                'links' => [
                    ['label' => 'All Projects', 'url' => route('projects.index')],
                    ['label' => 'Residential', 'url' => route('projects.index', ['type' => 'residential'])],
                    ['label' => 'Commercial', 'url' => route('projects.index', ['type' => 'commercial'])],
                    ['label' => 'Ongoing', 'url' => route('projects.index', ['status' => 'ongoing'])],
                    ['label' => 'Completed', 'url' => route('projects.index', ['status' => 'completed'])],
                ],
            ],
            [
                'title' => 'Resources',
                'links' => [
                    ['label' => 'Insights and Blog', 'url' => route('blog.index')],
                    ['label' => 'FAQ', 'url' => route('faq')],
                    ['label' => 'Partnerships', 'url' => route('partnerships')],
                    ['label' => 'Testimonials', 'url' => route('testimonials')],
                    ['label' => 'Gallery', 'url' => route('gallery')],
                ],
            ],
            [
                'title' => 'Portal',
                'links' => [
                    ['label' => 'Client Login', 'url' => route('portal.login')],
                ],
            ],
        ];
    }

    public static function portalNavigation(): array
    {
        return [
            ['label' => 'Dashboard',     'route' => 'portal.dashboard',       'icon' => 'layout-dashboard'],
            ['label' => 'My Projects',   'route' => 'portal.projects.index',  'icon' => 'folder-kanban'],
            ['label' => 'Documents',     'route' => 'portal.documents',       'icon' => 'file-text'],
            ['label' => 'Media',         'route' => 'portal.media',           'icon' => 'image'],
            ['label' => 'Balance Sheet', 'route' => 'portal.balance',         'icon' => 'wallet'],
            ['label' => 'Updates',       'route' => 'portal.updates',         'icon' => 'refresh-cw'],
            ['label' => 'Profile',       'route' => 'portal.profile.show',    'icon' => 'user'],
            ['label' => 'Support',       'route' => 'portal.support',         'icon' => 'help-circle'],
        ];
    }

    public static function adminNavigation($user = null): array
    {
        $navigation = [
            'Overview' => [
                ['label' => 'Dashboard',       'route' => 'admin.dashboard',          'icon' => 'layout-dashboard'],
            ],
            'Management' => [
                ['label' => 'Clients',         'route' => 'admin.clients.index',      'icon' => 'users'],
                ['label' => 'KYC Requests',    'route' => 'admin.kyc.index',          'icon' => 'shield-check'],
                ['label' => 'Consultations',   'route' => 'admin.consultations.index', 'icon' => 'calendar'],
                ['label' => 'Projects',        'route' => 'admin.projects.index',     'icon' => 'folder-kanban'],
                ['label' => 'Payments',        'route' => 'admin.payments.index',     'icon' => 'credit-card'],
                ['label' => 'Documents',       'route' => 'admin.documents.index',    'icon' => 'file-text'],
                ['label' => 'Media',           'route' => 'admin.media.index',        'icon' => 'image'],
                ['label' => 'Updates',         'route' => 'admin.updates.index',      'icon' => 'refresh-cw'],
            ],
            'Website CMS' => [
                ['label' => 'Homepage',        'route' => 'admin.cms.homepage',       'icon' => 'home'],
                ['label' => 'Public Pages',    'route' => 'admin.cms.pages',          'icon' => 'file-text'],
                ['label' => 'Portal',          'route' => 'admin.cms.portal',         'icon' => 'layout-dashboard'],
                ['label' => 'About',           'route' => 'admin.cms.about',          'icon' => 'info'],
                ['label' => 'Services',        'route' => 'admin.cms.services',       'icon' => 'layers'],
                ['label' => 'Projects',        'route' => 'admin.cms.projects',       'icon' => 'building'],
                ['label' => 'Portfolio',       'route' => 'admin.cms.portfolio',      'icon' => 'layout-grid'],
                ['label' => 'Blog',            'route' => 'admin.cms.blog',           'icon' => 'book-open'],
                ['label' => 'Testimonials',    'route' => 'admin.cms.testimonials',   'icon' => 'star'],
                ['label' => 'FAQ',             'route' => 'admin.cms.faq',            'icon' => 'help-circle'],
                ['label' => 'Careers',         'route' => 'admin.cms.careers',        'icon' => 'briefcase'],
            ],
            'System' => [
                ['label' => 'Form Submissions', 'route' => 'admin.forms.index',        'icon' => 'inbox'],
                ['label' => 'Newsletter',      'route' => 'admin.newsletter.index',   'icon' => 'mail'],
                ['label' => 'Notifications',   'route' => 'admin.notifications.index', 'icon' => 'bell'],
                ['label' => 'Settings',        'route' => 'admin.settings.edit',      'icon' => 'settings'],
                ['label' => 'Roles',           'route' => 'admin.roles.index',        'icon' => 'key'],
                ['label' => 'Activity Log',    'route' => 'admin.activity.index',     'icon' => 'activity'],
            ],
        ];

        return self::filterAdminNavigationForRole($navigation, $user?->role);
    }

    public static function adminRouteAllowed(?string $role, string $routeName): bool
    {
        if ($role === 'admin') {
            return true;
        }

        $allowedPrefixes = [
            'editor' => [
                'admin.cms.',
            ],
            'project_manager' => [
                'admin.consultations.',
                'admin.projects.',
                'admin.documents.',
                'admin.media.',
                'admin.updates.',
            ],
            'finance' => [
                'admin.payments.',
            ],
            'content_manager' => [
                'admin.cms.',
                'admin.forms.',
                'admin.newsletter.',
                'admin.notifications.',
                'admin.media.',
                'admin.updates.',
            ],
        ];

        if ($routeName === 'admin.dashboard' || $routeName === 'admin.logout') {
            return in_array($role, array_keys($allowedPrefixes), true);
        }

        foreach ($allowedPrefixes[$role] ?? [] as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return true;
            }
        }

        return false;
    }

    protected static function filterAdminNavigationForRole(array $navigation, ?string $role): array
    {
        if ($role === 'admin') {
            return $navigation;
        }

        $filtered = [];

        foreach ($navigation as $group => $items) {
            $allowedItems = array_values(array_filter(
                $items,
                fn (array $item): bool => self::adminRouteAllowed($role, $item['route'])
            ));

            if ($allowedItems !== []) {
                $filtered[$group] = $allowedItems;
            }
        }

        return $filtered;
    }

    public static function roleMatrix(): array
    {
        return [
            'admin' => ['Full CMS control', 'Client management', 'Finance visibility', 'Role assignment'],
            'editor' => ['Content publishing', 'Blog and testimonial management', 'Homepage edits'],
            'project_manager' => ['Project updates', 'Document uploads', 'Client assignment'],
            'finance' => ['Payment records', 'Balance reporting', 'Statements'],
            'content_manager' => ['Media library', 'FAQ and careers', 'Forms triage'],
            'client' => ['Portal access', 'Project visibility', 'Support messaging'],
        ];
    }

    public static function setting(string $key, array $fallback = []): array
    {
        if (! Schema::hasTable('settings')) {
            return $fallback;
        }

        $setting = Setting::query()->where('key', $key)->first();

        if (! $setting || ! is_array($setting->value)) {
            return $fallback;
        }

        return array_replace_recursive($fallback, $setting->value);
    }
}
