<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\CareerOpening;
use App\Models\ConsultationAvailability;
use App\Models\ConsultationBlockedDate;
use App\Models\ConsultationBooking;
use App\Models\ConsultationSetting;
use App\Models\ContactSubmission;
use App\Models\Document;
use App\Models\Faq;
use App\Models\KycRequest;
use App\Models\MediaAsset;
use App\Models\NewsletterSubscriber;
use App\Models\PartnershipInquiry;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectMilestone;
use App\Models\ProjectUpdate;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SupportTicket;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RockmouldSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->create([
            'name' => 'Adaeze Okafor',
            'email' => 'admin@rockmould.com',
            'password' => Hash::make('password'),
            'phone' => '+2348000000000',
            'role' => 'admin',
            'status' => 'active',
            'title' => 'Chief Operations Officer',
            'department' => 'Executive',
            'address' => 'Victoria Island, Lagos',
            'last_active_at' => now(),
        ]);

        $editor = User::query()->create([
            'name' => 'Damilola Akin',
            'email' => 'editor@rockmould.com',
            'password' => Hash::make('password'),
            'phone' => '+2348000000001',
            'role' => 'editor',
            'status' => 'active',
            'title' => 'Content Manager',
            'department' => 'Marketing',
            'address' => 'Lekki, Lagos',
            'last_active_at' => now()->subMinutes(25),
        ]);

        $projectManager = User::query()->create([
            'name' => 'Oluwaseun Adebayo',
            'email' => 'projects@rockmould.com',
            'password' => Hash::make('password'),
            'phone' => '+2348000000002',
            'role' => 'project_manager',
            'status' => 'active',
            'title' => 'Project Director',
            'department' => 'Projects',
            'address' => 'Ikoyi, Lagos',
            'last_active_at' => now()->subHour(),
        ]);

        $finance = User::query()->create([
            'name' => 'Ifeoma George',
            'email' => 'finance@rockmould.com',
            'password' => Hash::make('password'),
            'phone' => '+2348000000003',
            'role' => 'finance',
            'status' => 'active',
            'title' => 'Finance Lead',
            'department' => 'Finance',
            'address' => 'Victoria Island, Lagos',
            'last_active_at' => now()->subHours(2),
        ]);

        $clients = collect([
            ['name' => 'Adebayo Ogunleye', 'email' => 'adebayo@example.com', 'phone' => '+2348012345678', 'status' => 'active', 'address' => 'Banana Island, Lagos'],
            ['name' => 'Chioma Nwankwo', 'email' => 'chioma@example.com', 'phone' => '+2348023456789', 'status' => 'active', 'address' => 'Ikoyi, Lagos'],
            ['name' => 'Ibrahim Musa', 'email' => 'ibrahim@example.com', 'phone' => '+2348034567890', 'status' => 'active', 'address' => 'Maitama, Abuja'],
            ['name' => 'Folake Bakare', 'email' => 'folake@example.com', 'phone' => '+2348045678901', 'status' => 'inactive', 'address' => 'Lekki Phase 1, Lagos'],
        ])->map(fn (array $client) => User::query()->create([
            ...$client,
            'role' => 'client',
            'title' => 'Client',
            'department' => 'Clients',
            'last_active_at' => now()->subDays(1),
        ]))->keyBy('email');

        collect([
            ['slug' => 'real-estate-development', 'title' => 'Real Estate Development', 'tagline' => 'Premium developments engineered for value.', 'summary' => 'Residential and commercial developments that define skylines and communities.', 'description' => 'From luxury apartments to landmark estates, our developments are designed for enduring value.', 'benefits' => ['Prime location selection', 'Architectural design', 'Premium sourcing', 'End-to-end delivery'], 'process_steps' => ['Viability review', 'Concept design', 'Execution', 'Handover'], 'related_projects' => ['azure-towers', 'harbor-view-estate'], 'image_path' => 'images/project-1.jpg', 'is_featured' => true, 'sort_order' => 1],
            ['slug' => 'construction-project-execution', 'title' => 'Construction & Project Execution', 'tagline' => 'Disciplined execution from foundation to finishing.', 'summary' => 'Structural, civil, and finishing works with rigorous quality control.', 'description' => 'We deploy engineering precision and site management systems that protect quality and schedule.', 'benefits' => ['Engineering excellence', 'Quality assurance', 'Safety-first protocols', 'On-schedule delivery'], 'process_steps' => ['Procurement', 'Mobilization', 'Construction control', 'Commissioning'], 'related_projects' => ['meridian-plaza', 'commerce-tower'], 'image_path' => 'images/project-2.jpg', 'is_featured' => true, 'sort_order' => 2],
            ['slug' => 'property-advisory', 'title' => 'Property Advisory', 'tagline' => 'Data-backed advice for serious decisions.', 'summary' => 'Strategic property consulting for investors, developers, and corporate entities.', 'description' => 'We deliver market intelligence, pricing frameworks, and underwriting support.', 'benefits' => ['Market analysis', 'Investment strategy', 'Portfolio optimization', 'Risk assessment'], 'process_steps' => ['Needs assessment', 'Market review', 'Strategy', 'Recommendation'], 'related_projects' => ['crown-heights'], 'image_path' => 'images/project-3.jpg', 'sort_order' => 3],
            ['slug' => 'investment-support', 'title' => 'Investment Support', 'tagline' => 'Curated opportunities with transparent governance.', 'summary' => 'Support for high-net-worth and institutional real estate investors.', 'description' => 'Opportunity sourcing, diligence, reporting, and relationship stewardship.', 'benefits' => ['Curated opportunities', 'Transparent reporting', 'Capital growth strategies', 'Due diligence support'], 'process_steps' => ['Review', 'Underwriting', 'Structuring', 'Reporting'], 'related_projects' => ['the-penthouse-collection', 'commerce-tower'], 'image_path' => 'images/project-4.jpg', 'sort_order' => 4],
            ['slug' => 'project-management', 'title' => 'Project Management', 'tagline' => 'Every milestone tracked and protected.', 'summary' => 'Disciplined coordination from concept through handover.', 'description' => 'We coordinate architects, engineers, contractors, and suppliers with clear reporting.', 'benefits' => ['Scheduling', 'Budget control', 'Stakeholder coordination', 'Progress reporting'], 'process_steps' => ['Programme', 'Coordination', 'Issue resolution', 'Closeout'], 'related_projects' => ['azure-towers'], 'image_path' => 'images/project-1.jpg', 'sort_order' => 5],
            ['slug' => 'luxury-property-delivery', 'title' => 'Luxury Property Delivery', 'tagline' => 'Bespoke premium homes for discerning buyers.', 'summary' => 'Refined luxury properties curated for comfort and prestige.', 'description' => 'Every finish and handover detail is shaped around an elevated ownership experience.', 'benefits' => ['Interior design', 'Premium finishes', 'Smart home integration', 'Personalized delivery'], 'process_steps' => ['Client briefing', 'Design coordination', 'Finishing control', 'White-glove handover'], 'related_projects' => ['the-penthouse-collection', 'royal-courts'], 'image_path' => 'images/project-4.jpg', 'sort_order' => 6],
            ['slug' => 'site-development', 'title' => 'Site Development', 'tagline' => 'Infrastructure-ready estates and platforms.', 'summary' => 'Site infrastructure for phased residential and mixed-use delivery.', 'description' => 'Drainage, access roads, utilities, and public-realm planning aligned early for delivery confidence.', 'benefits' => ['Road and drainage planning', 'Utility coordination', 'Estate phasing', 'Civil sequencing'], 'process_steps' => ['Survey', 'Infrastructure works', 'Utility commissioning', 'Phased rollout'], 'related_projects' => ['harbor-view-estate'], 'image_path' => 'images/project-2.jpg', 'sort_order' => 7],
            ['slug' => 'civil-works', 'title' => 'Civil Works & Technical Construction', 'tagline' => 'Technical construction support for resilient sites.', 'summary' => 'Groundworks, drainage, retaining systems, and heavy-duty infrastructure packages.', 'description' => 'We support developers with technical construction packages that protect long-term performance.', 'benefits' => ['Groundworks', 'Drainage systems', 'Retaining structures', 'QA/QC'], 'process_steps' => ['Scope', 'Planning', 'Delivery', 'Closeout'], 'related_projects' => ['commerce-tower'], 'image_path' => 'images/project-3.jpg', 'sort_order' => 8],
            ['slug' => 'end-to-end-client-support', 'title' => 'End-to-End Client Support', 'tagline' => 'Commercial clarity before and after delivery.', 'summary' => 'Support across documentation, reporting, payments, and post-delivery coordination.', 'description' => 'The service model extends beyond construction to protect trust, communication, and portfolio visibility.', 'benefits' => ['Relationship support', 'Reporting', 'Document coordination', 'Post-handover care'], 'process_steps' => ['Onboarding', 'Support', 'Reporting cadence', 'Post-delivery care'], 'related_projects' => ['azure-towers', 'meridian-plaza'], 'image_path' => 'images/project-1.jpg', 'sort_order' => 9],
        ])->each(fn (array $service) => Service::query()->create($service));

        $projects = collect([
            ['slug' => 'azure-towers', 'title' => 'Azure Towers', 'location' => 'Victoria Island, Lagos', 'category' => 'Residential', 'status' => 'ongoing', 'cover_image' => 'images/project-1.jpg', 'overview' => 'A landmark twin-tower residential development designed for panoramic waterfront living and investor-grade value.', 'description' => 'Azure Towers blends premium residences, wellness amenities, and concierge-led living in one flagship address.', 'features' => ['24/7 concierge', 'Infinity pool', 'Private cinema', 'Smart home systems'], 'highlights' => [['label' => 'Units', 'value' => '120'], ['label' => 'Floors', 'value' => '20'], ['label' => 'Completion', 'value' => 'Q4 2027'], ['label' => 'Type', 'value' => 'Luxury Apartments']], 'gallery' => ['images/project-1.jpg', 'images/project-2.jpg', 'images/project-3.jpg', 'images/project-4.jpg'], 'floor_plans' => ['storage/documents/floor-plan-azure.txt'], 'documents' => ['storage/documents/offer-letter-azure.txt'], 'progress' => 45, 'budget' => 30000000000, 'price_from' => 350000000, 'is_featured' => true, 'is_portfolio' => true, 'launch_date' => '2025-01-01', 'completion_date' => '2027-12-31'],
            ['slug' => 'meridian-plaza', 'title' => 'Meridian Plaza', 'location' => 'Ikoyi, Lagos', 'category' => 'Commercial', 'status' => 'completed', 'cover_image' => 'images/project-2.jpg', 'overview' => 'Premium office and retail space with efficient floorplates for blue-chip tenants.', 'description' => 'Meridian Plaza delivers executive arrival, resilience, and grade-A space planning.', 'features' => ['Grade-A office floors', 'Executive parking', 'Retail activation', 'Backup power'], 'highlights' => [['label' => 'Suites', 'value' => '48'], ['label' => 'Floors', 'value' => '12'], ['label' => 'Completion', 'value' => 'Delivered'], ['label' => 'Type', 'value' => 'Commercial']], 'gallery' => ['images/project-2.jpg', 'images/project-3.jpg', 'images/project-4.jpg', 'images/project-1.jpg'], 'progress' => 100, 'budget' => 18000000000, 'price_from' => 500000000, 'is_featured' => true, 'is_portfolio' => true, 'launch_date' => '2022-02-01', 'completion_date' => '2025-03-01'],
            ['slug' => 'crown-heights', 'title' => 'Crown Heights', 'location' => 'Lekki Phase 1, Lagos', 'category' => 'Mixed-Use', 'status' => 'upcoming', 'cover_image' => 'images/project-3.jpg', 'overview' => 'A mixed-use address combining residential comfort, curated retail, and lifestyle amenities.', 'description' => 'Crown Heights is structured for urban convenience, flexible unit mix, and a polished community feel.', 'features' => ['Retail frontage', 'Secure access', 'Shared amenities', 'Premium finishes'], 'highlights' => [['label' => 'Blocks', 'value' => '4'], ['label' => 'Launch', 'value' => 'Q3 2026'], ['label' => 'Completion', 'value' => 'Q1 2029'], ['label' => 'Type', 'value' => 'Mixed-Use']], 'gallery' => ['images/project-3.jpg', 'images/project-4.jpg', 'images/project-1.jpg', 'images/project-2.jpg'], 'progress' => 10, 'budget' => 24000000000, 'price_from' => 220000000, 'is_portfolio' => true, 'launch_date' => '2026-07-01', 'completion_date' => '2029-03-01'],
            ['slug' => 'the-penthouse-collection', 'title' => 'The Penthouse Collection', 'location' => 'Banana Island, Lagos', 'category' => 'Luxury Residential', 'status' => 'completed', 'cover_image' => 'images/project-4.jpg', 'overview' => 'A boutique offering of ultra-premium residences with private lift access and bespoke interior finishes.', 'description' => 'The Penthouse Collection is tailored for private owners who value discretion, skyline views, and craftsmanship.', 'features' => ['Private lift access', 'Roof terraces', 'Chef-grade kitchens', 'Custom joinery'], 'highlights' => [['label' => 'Residences', 'value' => '16'], ['label' => 'Floors', 'value' => '8'], ['label' => 'Completion', 'value' => 'Delivered'], ['label' => 'Type', 'value' => 'Ultra Luxury']], 'gallery' => ['images/project-4.jpg', 'images/project-1.jpg', 'images/project-2.jpg', 'images/project-3.jpg'], 'progress' => 100, 'budget' => 15000000000, 'price_from' => 650000000, 'is_featured' => true, 'is_portfolio' => true, 'launch_date' => '2021-05-01', 'completion_date' => '2024-07-01'],
            ['slug' => 'harbor-view-estate', 'title' => 'Harbor View Estate', 'location' => 'Lekki, Lagos', 'category' => 'Residential', 'status' => 'ongoing', 'cover_image' => 'images/project-1.jpg', 'overview' => 'A family-focused residential community with phased delivery and reliable infrastructure.', 'description' => 'Harbor View Estate is planned for steady investor absorption and practical livability.', 'features' => ['Gated estate', 'Clubhouse', 'Reliable utilities', 'Pedestrian streets'], 'highlights' => [['label' => 'Homes', 'value' => '84'], ['label' => 'Phase', 'value' => 'Phase 2'], ['label' => 'Completion', 'value' => 'Q2 2027'], ['label' => 'Type', 'value' => 'Estate Housing']], 'gallery' => ['images/project-1.jpg', 'images/project-3.jpg', 'images/project-2.jpg', 'images/project-4.jpg'], 'progress' => 65, 'budget' => 12000000000, 'price_from' => 120000000, 'is_portfolio' => true, 'launch_date' => '2024-02-01', 'completion_date' => '2027-06-01'],
            ['slug' => 'commerce-tower', 'title' => 'Commerce Tower', 'location' => 'Victoria Island, Lagos', 'category' => 'Commercial', 'status' => 'ongoing', 'cover_image' => 'images/project-3.jpg', 'overview' => 'A high-visibility commercial project designed for enterprise tenants seeking prestige and efficiency.', 'description' => 'Commerce Tower combines enterprise-ready floorplates with a strong tenant services backbone.', 'features' => ['Office floorplates', 'Meeting suites', 'Smart systems', 'Executive drop-off'], 'highlights' => [['label' => 'Floors', 'value' => '15'], ['label' => 'Leasing', 'value' => '62%'], ['label' => 'Completion', 'value' => 'Q1 2028'], ['label' => 'Type', 'value' => 'Commercial']], 'gallery' => ['images/project-3.jpg', 'images/project-2.jpg', 'images/project-1.jpg', 'images/project-4.jpg'], 'progress' => 52, 'budget' => 20000000000, 'price_from' => 750000000, 'is_portfolio' => true, 'launch_date' => '2025-03-01', 'completion_date' => '2028-03-01'],
        ])->map(fn (array $project) => Project::query()->create($project))->keyBy('slug');

        collect([
            ['project' => 'azure-towers', 'title' => 'Foundation Complete', 'description' => 'Piling and basement packages concluded.', 'milestone_date' => '2025-03-15', 'is_completed' => true, 'progress' => 30, 'sort_order' => 1],
            ['project' => 'azure-towers', 'title' => 'Structure Complete', 'description' => 'Main structure topped out.', 'milestone_date' => '2025-12-18', 'is_completed' => true, 'progress' => 45, 'sort_order' => 2],
            ['project' => 'meridian-plaza', 'title' => 'Operational Launch', 'description' => 'Completed building is now tenant-ready.', 'milestone_date' => '2025-03-01', 'is_completed' => true, 'progress' => 100, 'sort_order' => 1],
            ['project' => 'crown-heights', 'title' => 'Planning Approval', 'description' => 'Approvals workflow is underway.', 'milestone_date' => '2026-07-30', 'is_completed' => false, 'progress' => 20, 'sort_order' => 1],
            ['project' => 'harbor-view-estate', 'title' => 'Phase 1 Delivery', 'description' => 'First phase handover completed.', 'milestone_date' => '2026-02-15', 'is_completed' => true, 'progress' => 55, 'sort_order' => 1],
            ['project' => 'commerce-tower', 'title' => 'Facade Package', 'description' => 'Facade procurement and installation progressing.', 'milestone_date' => '2026-05-18', 'is_completed' => false, 'progress' => 58, 'sort_order' => 1],
        ])->each(fn (array $milestone) => ProjectMilestone::query()->create([
            'project_id' => $projects[$milestone['project']]->id,
            'title' => $milestone['title'],
            'description' => $milestone['description'],
            'milestone_date' => $milestone['milestone_date'],
            'is_completed' => $milestone['is_completed'],
            'progress' => $milestone['progress'],
            'sort_order' => $milestone['sort_order'],
        ]));

        collect([
            ['project' => 'azure-towers', 'title' => 'Foundation work completed', 'body' => 'Foundation and basement structure have reached 100% completion with QA sign-offs recorded.', 'update_date' => '2026-03-10', 'progress' => 45, 'attachments' => ['images/project-1.jpg']],
            ['project' => 'azure-towers', 'title' => 'Interior procurement underway', 'body' => 'Imported finishing packages and joinery schedules are now locked for phased installation.', 'update_date' => '2026-04-04', 'progress' => 49, 'attachments' => ['images/project-2.jpg']],
            ['project' => 'meridian-plaza', 'title' => 'Building fully delivered', 'body' => 'Tenant fit-out and operations handover completed successfully.', 'update_date' => '2026-01-15', 'progress' => 100, 'attachments' => ['images/project-2.jpg']],
            ['project' => 'crown-heights', 'title' => 'Concept design approved', 'body' => 'Design concept finalized and pre-sales preparation started.', 'update_date' => '2026-03-08', 'progress' => 10, 'attachments' => ['images/project-3.jpg']],
        ])->each(fn (array $update) => ProjectUpdate::query()->create([
            'project_id' => $projects[$update['project']]->id,
            'title' => $update['title'],
            'body' => $update['body'],
            'update_date' => $update['update_date'],
            'progress' => $update['progress'],
            'attachments' => $update['attachments'],
        ]));

        $assignments = collect([
            ['project_slug' => 'azure-towers', 'client_email' => 'adebayo@example.com', 'reference' => 'RM-2026-0001', 'unit_label' => 'Unit 14B', 'status' => 'active', 'progress_percentage' => 45, 'contract_amount' => 25000000, 'amount_paid' => 18500000, 'outstanding_balance' => 6500000, 'next_payment_amount' => 2500000, 'next_payment_due_at' => '2026-05-15', 'start_date' => '2025-02-01', 'handover_date' => '2027-12-31', 'account_manager_name' => 'Tomiwa Ajayi', 'account_manager_email' => 'support@rockmould.com', 'account_manager_phone' => '+2348090000000', 'client_notes' => 'Interested in monthly photo updates.'],
            ['project_slug' => 'meridian-plaza', 'client_email' => 'adebayo@example.com', 'reference' => 'RM-2026-0002', 'unit_label' => 'Office 302', 'status' => 'completed', 'progress_percentage' => 100, 'contract_amount' => 22000000, 'amount_paid' => 22000000, 'outstanding_balance' => 0, 'start_date' => '2023-06-01', 'handover_date' => '2025-01-10', 'account_manager_name' => 'Tomiwa Ajayi', 'account_manager_email' => 'support@rockmould.com', 'account_manager_phone' => '+2348090000000'],
            ['project_slug' => 'crown-heights', 'client_email' => 'adebayo@example.com', 'reference' => 'RM-2026-0003', 'unit_label' => 'Unit 8A', 'status' => 'upcoming', 'progress_percentage' => 10, 'contract_amount' => 11000000, 'amount_paid' => 4700000, 'outstanding_balance' => 6300000, 'next_payment_amount' => 1500000, 'next_payment_due_at' => '2026-06-10', 'start_date' => '2026-03-01', 'handover_date' => '2029-03-01', 'account_manager_name' => 'Tomiwa Ajayi', 'account_manager_email' => 'support@rockmould.com', 'account_manager_phone' => '+2348090000000'],
            ['project_slug' => 'the-penthouse-collection', 'client_email' => 'chioma@example.com', 'reference' => 'RM-2026-0004', 'unit_label' => 'Penthouse 02', 'status' => 'completed', 'progress_percentage' => 100, 'contract_amount' => 30000000, 'amount_paid' => 28000000, 'outstanding_balance' => 2000000, 'next_payment_amount' => 2000000, 'next_payment_due_at' => '2026-04-30', 'start_date' => '2023-01-01', 'handover_date' => '2024-10-15', 'account_manager_name' => 'Eniola James', 'account_manager_email' => 'support@rockmould.com', 'account_manager_phone' => '+2348090000001'],
            ['project_slug' => 'commerce-tower', 'client_email' => 'ibrahim@example.com', 'reference' => 'RM-2026-0005', 'unit_label' => 'Suite 601', 'status' => 'active', 'progress_percentage' => 52, 'contract_amount' => 25000000, 'amount_paid' => 15500000, 'outstanding_balance' => 9500000, 'next_payment_amount' => 3000000, 'next_payment_due_at' => '2026-05-30', 'start_date' => '2025-05-01', 'handover_date' => '2028-03-01', 'account_manager_name' => 'Eniola James', 'account_manager_email' => 'support@rockmould.com', 'account_manager_phone' => '+2348090000001'],
        ])->map(fn (array $assignment) => ProjectAssignment::query()->create([
            'project_id' => $projects[$assignment['project_slug']]->id,
            'user_id' => $clients[$assignment['client_email']]->id,
            'reference' => $assignment['reference'],
            'unit_label' => $assignment['unit_label'],
            'status' => $assignment['status'],
            'progress_percentage' => $assignment['progress_percentage'],
            'contract_amount' => $assignment['contract_amount'],
            'amount_paid' => $assignment['amount_paid'],
            'outstanding_balance' => $assignment['outstanding_balance'],
            'next_payment_amount' => $assignment['next_payment_amount'] ?? null,
            'next_payment_due_at' => $assignment['next_payment_due_at'] ?? null,
            'start_date' => $assignment['start_date'] ?? null,
            'handover_date' => $assignment['handover_date'] ?? null,
            'account_manager_name' => $assignment['account_manager_name'] ?? null,
            'account_manager_email' => $assignment['account_manager_email'] ?? null,
            'account_manager_phone' => $assignment['account_manager_phone'] ?? null,
            'client_notes' => $assignment['client_notes'] ?? null,
        ]))->keyBy('reference');

        collect([
            ['reference' => 'RM-2026-0001', 'amount' => 5000000, 'status' => 'paid', 'code' => 'PAY-AZ-001', 'due_at' => '2025-11-15', 'paid_at' => '2025-11-14 14:15:00', 'notes' => 'Deposit tranche'],
            ['reference' => 'RM-2026-0001', 'amount' => 2500000, 'status' => 'paid', 'code' => 'PAY-AZ-002', 'due_at' => '2026-03-05', 'paid_at' => '2026-03-05 10:00:00', 'notes' => 'March 2026 payment'],
            ['reference' => 'RM-2026-0001', 'amount' => 2500000, 'status' => 'due', 'code' => 'PAY-AZ-003', 'due_at' => '2026-05-15', 'notes' => 'Next scheduled payment'],
            ['reference' => 'RM-2026-0002', 'amount' => 22000000, 'status' => 'paid', 'code' => 'PAY-MP-001', 'due_at' => '2024-12-15', 'paid_at' => '2024-12-14 12:00:00', 'notes' => 'Full settlement'],
            ['reference' => 'RM-2026-0003', 'amount' => 1500000, 'status' => 'pending', 'code' => 'PAY-CH-001', 'due_at' => '2026-06-10', 'notes' => 'Upcoming installment'],
            ['reference' => 'RM-2026-0004', 'amount' => 2000000, 'status' => 'overdue', 'code' => 'PAY-PC-001', 'due_at' => '2026-04-30', 'notes' => 'Final balance outstanding'],
            ['reference' => 'RM-2026-0005', 'amount' => 3000000, 'status' => 'due', 'code' => 'PAY-CT-001', 'due_at' => '2026-05-30', 'notes' => 'Quarterly installment'],
        ])->each(fn (array $payment) => Payment::query()->create([
            'project_assignment_id' => $assignments[$payment['reference']]->id,
            'amount' => $payment['amount'],
            'status' => $payment['status'],
            'reference' => $payment['code'],
            'payment_method' => 'Bank Transfer',
            'due_at' => $payment['due_at'],
            'paid_at' => $payment['paid_at'] ?? null,
            'notes' => $payment['notes'],
        ]));

        collect([
            ['assignment' => 'RM-2026-0001', 'project' => 'azure-towers', 'title' => 'Offer Letter - Azure Towers', 'category' => 'Offer Letter', 'file_path' => 'documents/offer-letter-azure.txt'],
            ['assignment' => 'RM-2026-0001', 'project' => 'azure-towers', 'title' => 'Receipt - March 2026 Payment', 'category' => 'Receipt', 'file_path' => 'documents/receipt-march-2026.txt'],
            ['assignment' => 'RM-2026-0003', 'project' => 'crown-heights', 'title' => 'Agreement - Crown Heights', 'category' => 'Agreement', 'file_path' => 'documents/agreement-crown-heights.txt'],
            ['assignment' => 'RM-2026-0005', 'project' => 'commerce-tower', 'title' => 'Statement - Commerce Tower', 'category' => 'Statement', 'file_path' => 'documents/statement-commerce-tower.txt'],
        ])->each(fn (array $document) => Document::query()->create([
            'project_id' => $projects[$document['project']]->id,
            'project_assignment_id' => $assignments[$document['assignment']]->id,
            'user_id' => $assignments[$document['assignment']]->user_id,
            'uploaded_by' => $admin->id,
            'title' => $document['title'],
            'category' => $document['category'],
            'file_path' => $document['file_path'],
            'mime_type' => 'text/plain',
            'size' => 1024,
            'version' => 'v1',
            'visible_to_client' => true,
        ]));

        collect([
            ['assignment' => 'RM-2026-0001', 'project' => 'azure-towers', 'title' => 'Azure Towers Site Progress', 'file_path' => 'images/project-1.jpg', 'thumbnail_path' => 'images/project-1.jpg', 'gallery_section' => 'exterior', 'is_featured' => true, 'sort_order' => 1],
            ['assignment' => 'RM-2026-0001', 'project' => 'azure-towers', 'title' => 'Interior Material Mockup', 'file_path' => 'images/project-2.jpg', 'thumbnail_path' => 'images/project-2.jpg', 'gallery_section' => 'interior', 'sort_order' => 2],
            ['assignment' => 'RM-2026-0003', 'project' => 'crown-heights', 'title' => 'Concept Exterior', 'file_path' => 'images/project-3.jpg', 'thumbnail_path' => 'images/project-3.jpg', 'gallery_section' => 'exterior', 'sort_order' => 1],
            ['assignment' => 'RM-2026-0005', 'project' => 'commerce-tower', 'title' => 'Facade Progress', 'file_path' => 'images/project-4.jpg', 'thumbnail_path' => 'images/project-4.jpg', 'gallery_section' => 'exterior', 'sort_order' => 1],
        ])->each(fn (array $media) => MediaAsset::query()->create([
            'project_id' => $projects[$media['project']]->id,
            'project_assignment_id' => $assignments[$media['assignment']]->id,
            'uploaded_by' => $projectManager->id,
            'title' => $media['title'],
            'media_type' => 'image',
            'gallery_section' => $media['gallery_section'],
            'file_path' => $media['file_path'],
            'thumbnail_path' => $media['thumbnail_path'],
            'is_featured' => $media['is_featured'] ?? false,
            'sort_order' => $media['sort_order'],
        ]));

        collect([
            ['project' => 'azure-towers', 'title' => 'Azure Towers Exterior Overview', 'file_path' => 'images/project-1.jpg', 'thumbnail_path' => 'images/project-1.jpg', 'gallery_section' => 'exterior', 'is_featured' => true, 'sort_order' => 1],
            ['project' => 'azure-towers', 'title' => 'Azure Towers Interior Finish Preview', 'file_path' => 'images/project-2.jpg', 'thumbnail_path' => 'images/project-2.jpg', 'gallery_section' => 'interior', 'sort_order' => 2],
            ['project' => 'meridian-plaza', 'title' => 'Meridian Plaza Arrival View', 'file_path' => 'images/project-2.jpg', 'thumbnail_path' => 'images/project-2.jpg', 'gallery_section' => 'exterior', 'sort_order' => 1],
            ['project' => 'the-penthouse-collection', 'title' => 'Penthouse Living Room Detail', 'file_path' => 'images/project-4.jpg', 'thumbnail_path' => 'images/project-4.jpg', 'gallery_section' => 'interior', 'sort_order' => 1],
            ['project' => 'commerce-tower', 'title' => 'Commerce Tower Facade Progress', 'file_path' => 'images/project-3.jpg', 'thumbnail_path' => 'images/project-3.jpg', 'gallery_section' => 'exterior', 'sort_order' => 1],
        ])->each(fn (array $media) => MediaAsset::query()->create([
            'project_id' => $projects[$media['project']]->id,
            'project_assignment_id' => null,
            'uploaded_by' => $projectManager->id,
            'title' => $media['title'],
            'media_type' => 'image',
            'gallery_section' => $media['gallery_section'],
            'file_path' => $media['file_path'],
            'thumbnail_path' => $media['thumbnail_path'],
            'is_featured' => $media['is_featured'] ?? false,
            'visible_to_client' => true,
            'sort_order' => $media['sort_order'],
        ]));

        collect([
            ['slug' => 'future-luxury', 'title' => 'The Future of Luxury Real Estate in Lagos', 'excerpt' => 'An in-depth look at trends shaping premium property markets in Nigeria.', 'category' => 'Market Insight', 'author_name' => 'Rockmould Editorial', 'read_time' => '8 min', 'intro' => 'Lagos continues to evolve as a prime destination for luxury real estate investment.', 'quote' => 'The Lagos luxury market is moving beyond pure square footage toward service, experience, and lifestyle value.', 'content' => [['heading' => 'The Shifting Landscape', 'paragraphs' => ['Prime districts remain core anchors of demand, but buyer expectations have changed materially.', 'Developers that combine disciplined delivery with strong post-handover management are winning confidence.']], ['heading' => 'Investment Outlook', 'paragraphs' => ['Long-term performance will come from developments that balance prestige with operational resilience.']]], 'cover_image' => 'images/project-1.jpg', 'featured' => true, 'published_at' => now()->subWeeks(3)],
            ['slug' => 'sustainable-construction', 'title' => 'Sustainable Construction: Building for Tomorrow', 'excerpt' => 'How Rockmould integrates sustainable practices into premium developments.', 'category' => 'Industry', 'author_name' => 'Rockmould Editorial', 'read_time' => '6 min', 'intro' => 'Sustainability in premium construction is no longer a side conversation.', 'content' => [['heading' => 'Efficiency Starts in Early Planning', 'paragraphs' => ['Performance is shaped in the design stage through orientation, material selection, and systems planning.']], ['heading' => 'Luxury and Sustainability Can Coexist', 'paragraphs' => ['Resilient power strategies and durable finishes support a refined ownership experience.']]], 'cover_image' => 'images/project-2.jpg', 'published_at' => now()->subWeeks(6)],
            ['slug' => 'investment-guide', 'title' => 'Investment Guide: Premium Properties in Nigeria', 'excerpt' => 'A guide for investors looking at Nigeria\'s high-value real estate market.', 'category' => 'Advisory', 'author_name' => 'Rockmould Advisory', 'read_time' => '10 min', 'intro' => 'Premium property investment demands more than confidence in the market.', 'content' => [['heading' => 'Start with Sponsor Quality', 'paragraphs' => ['The developer\'s delivery history, governance approach, and communication culture matter as much as the site itself.']], ['heading' => 'Assess the Full Value Story', 'paragraphs' => ['Investors should review location fundamentals, pricing logic, and operating profile.']]], 'cover_image' => 'images/project-3.jpg', 'published_at' => now()->subWeeks(10)],
            ['slug' => 'smart-homes', 'title' => 'Smart Homes: The New Standard in Luxury Living', 'excerpt' => 'Exploring how technology is transforming premium residential developments.', 'category' => 'Industry', 'author_name' => 'Rockmould Editorial', 'read_time' => '5 min', 'intro' => 'Smart-home capability is becoming baseline infrastructure in luxury residential projects.', 'content' => [['heading' => 'Technology Must Feel Invisible', 'paragraphs' => ['The best smart-home systems simplify comfort, lighting, access control, and energy visibility.']], ['heading' => 'Integration Matters More Than Features', 'paragraphs' => ['Technology packages create more value when they are planned into the home from the outset.']]], 'cover_image' => 'images/project-4.jpg', 'published_at' => now()->subWeeks(12)],
        ])->each(fn (array $post) => Post::query()->create($post));

        collect([
            ['project' => 'azure-towers', 'client_name' => 'Adebayo O.', 'role' => 'Property Investor', 'quote' => 'Rockmould transformed our vision into reality. The attention to detail and professionalism is unmatched.', 'sort_order' => 1],
            ['project' => 'meridian-plaza', 'client_name' => 'Funke A.', 'role' => 'Corporate Client', 'quote' => 'Working with Rockmould was seamless. Their commitment to quality and timelines gave us complete confidence.', 'sort_order' => 2],
            ['project' => 'the-penthouse-collection', 'client_name' => 'Chioma N.', 'role' => 'Private Buyer', 'quote' => 'The delivery quality was calm, refined, and exactly as promised.', 'sort_order' => 3],
        ])->each(fn (array $testimonial) => Testimonial::query()->create([
            'project_id' => $projects[$testimonial['project']]->id,
            'client_name' => $testimonial['client_name'],
            'role' => $testimonial['role'],
            'quote' => $testimonial['quote'],
            'rating' => 5,
            'sort_order' => $testimonial['sort_order'],
        ]));

        collect([
            ['category' => 'Projects', 'question' => 'How do I book a consultation?', 'answer' => 'Use the consultation booking page to choose a live date and time, then submit your details for confirmation by the Rockmould team.', 'sort_order' => 1],
            ['category' => 'Projects', 'question' => 'Do you support off-plan investments?', 'answer' => 'Yes. Selected projects include structured off-plan purchase options with reporting and milestone visibility.', 'sort_order' => 2],
            ['category' => 'Construction', 'question' => 'Can Rockmould manage third-party developments?', 'answer' => 'Yes. Our project management and construction services support both internal and client-owned developments.', 'sort_order' => 3],
            ['category' => 'Portal', 'question' => 'How does client portal access work?', 'answer' => 'Recognized client phone numbers can access the portal directly. New clients submit a KYC request for manual approval.', 'sort_order' => 4],
            ['category' => 'Payments', 'question' => 'Can I download receipts and statements?', 'answer' => 'Yes. Receipts, offer letters, agreements, invoices, and statements appear in the client portal when uploaded by the admin team.', 'sort_order' => 5],
        ])->each(fn (array $faq) => Faq::query()->create($faq));

        collect([
            ['slug' => 'project-coordinator', 'title' => 'Project Coordinator', 'department' => 'Projects', 'location' => 'Lagos', 'employment_type' => 'Full Time', 'summary' => 'Support reporting, scheduling, and coordination across active construction and development projects.', 'description' => 'You will work with project directors, consultants, and contractors to maintain reporting discipline and delivery momentum.', 'requirements' => ['3+ years in project coordination', 'Strong reporting skills', 'Construction experience'], 'benefits' => ['Health coverage', 'Professional development', 'Performance bonus'], 'published_at' => now()->subDays(10)],
            ['slug' => 'finance-analyst', 'title' => 'Finance Analyst', 'department' => 'Finance', 'location' => 'Lagos', 'employment_type' => 'Full Time', 'summary' => 'Support project cashflow reporting, client balance visibility, and management dashboards.', 'description' => 'You will partner with finance and project teams on payment schedules and reconciliations.', 'requirements' => ['3+ years in finance', 'Excel and reporting proficiency', 'Real estate exposure preferred'], 'benefits' => ['Health coverage', 'Annual bonus', 'Training budget'], 'published_at' => now()->subDays(8)],
        ])->each(fn (array $career) => CareerOpening::query()->create($career));

        collect([
            ['full_name' => 'Emeka Obi', 'phone' => '+2348056789012', 'email' => 'emeka@example.com', 'residential_address' => 'Ajah, Lagos', 'project_of_interest' => 'Sunrise Villas', 'note' => 'Interested in a flexible payment plan.', 'status' => 'pending'],
            ['full_name' => 'Mariam Hassan', 'phone' => '+2348067890123', 'email' => 'mariam@example.com', 'residential_address' => 'Wuse 2, Abuja', 'project_of_interest' => 'Commerce Tower', 'note' => 'Please confirm office suite availability.', 'status' => 'needs_update', 'admin_note' => 'Request clearer identification upload.'],
        ])->each(fn (array $kyc) => KycRequest::query()->create($kyc));

        ContactSubmission::query()->create(['full_name' => 'Lekan Bassey', 'email' => 'lekan@example.com', 'phone' => '+2348071111111', 'department' => 'Sales and Projects', 'message' => 'I would like to schedule a consultation for a premium residential development.', 'status' => 'new']);
        PartnershipInquiry::query()->create(['full_name' => 'Nneka Umeh', 'company_name' => 'Prime Axis Capital', 'email' => 'nneka@primeaxis.com', 'phone' => '+2348072222222', 'interest_type' => 'Investment', 'message' => 'We are exploring a co-investment position in high-end mixed-use developments.', 'status' => 'new']);
        NewsletterSubscriber::query()->create(['email' => 'subscriber@rockmould.com', 'status' => 'subscribed', 'subscribed_at' => now()->subDays(4)]);
        SupportTicket::query()->create(['user_id' => $clients['adebayo@example.com']->id, 'project_assignment_id' => $assignments['RM-2026-0001']->id, 'full_name' => 'Adebayo Ogunleye', 'email' => 'adebayo@example.com', 'phone' => '+2348012345678', 'subject' => 'Need updated payment statement', 'message' => 'Please upload my current statement and confirm the next due amount.', 'status' => 'open']);

        collect([
            ['name' => 'Chief Adeyemi Rockmould', 'role' => 'Founder & Chairman', 'bio' => 'Over 25 years of experience in real estate and construction leadership.', 'sort_order' => 1],
            ['name' => 'Engr. Oluwaseun Adebayo', 'role' => 'Managing Director', 'bio' => 'Structural engineer with a passion for innovative construction solutions.', 'sort_order' => 2],
            ['name' => 'Arc. Folake Martins', 'role' => 'Director of Development', 'bio' => 'Architect driving Rockmould\'s design excellence and product quality.', 'sort_order' => 3],
        ])->each(fn (array $member) => TeamMember::query()->create($member));

        collect([
            ['year' => '2004', 'title' => 'Company Founded', 'description' => 'Rockmould Limited established with a vision for premium construction.', 'sort_order' => 1],
            ['year' => '2009', 'title' => 'First Major Development', 'description' => 'Completed our first landmark residential project in Lagos.', 'sort_order' => 2],
            ['year' => '2014', 'title' => 'Expansion', 'description' => 'Expanded into commercial real estate and mixed-use developments.', 'sort_order' => 3],
            ['year' => '2018', 'title' => '100th Project', 'description' => 'Reached a milestone of 100 delivered projects across Nigeria.', 'sort_order' => 4],
            ['year' => '2022', 'title' => 'Premium Portfolio', 'description' => 'Launched a luxury portfolio exceeding NGN 50 billion in value.', 'sort_order' => 5],
            ['year' => '2026', 'title' => 'Global Standards', 'description' => 'Setting new benchmarks in African real estate excellence.', 'sort_order' => 6],
        ])->each(fn (array $entry) => TimelineEntry::query()->create($entry));

        Setting::query()->create(['key' => 'company', 'value' => ['name' => 'Rockmould Limited', 'tagline' => 'Premium Real Estate and Construction', 'phone' => '+234 123 456 7890', 'email' => 'info@rockmould.com', 'address' => '12 Admiralty Way, Victoria Island, Lagos, Nigeria', 'hours' => 'Mon - Fri: 9:00 AM - 6:00 PM', 'support_phone' => '+234 801 234 5678', 'support_email' => 'support@rockmould.com', 'socials' => [['label' => 'Instagram', 'url' => '#'], ['label' => 'LinkedIn', 'url' => '#'], ['label' => 'YouTube', 'url' => '#']]]]);
        Setting::query()->create(['key' => 'homepage', 'value' => ['hero_label' => 'Premium Real Estate & Construction', 'hero_title' => 'Building Legacies That Endure', 'hero_description' => 'Rockmould Limited delivers world-class real estate developments and construction projects. Every structure we build is a testament to excellence, precision, and lasting value.', 'hero_primary_cta' => 'Explore Projects', 'hero_secondary_cta' => 'Book Consultation', 'hero_portal_cta' => 'Client Portal', 'stats' => [['label' => 'Projects Delivered', 'value' => '150+'], ['label' => 'Years of Excellence', 'value' => '20+'], ['label' => 'Happy Clients', 'value' => '500+'], ['label' => 'Portfolio Value', 'value' => 'NGN 50B']]]]);
        Setting::query()->create(['key' => 'about', 'value' => ['story_title' => 'From Vision to Legacy', 'story' => ['Founded in 2004, Rockmould Limited began with a singular vision: to redefine the standards of real estate development and construction in Nigeria.', 'Today, Rockmould stands as a symbol of quality, trust, and innovation in the Nigerian property landscape.'], 'vision' => 'To be Africa\'s most trusted and admired real estate and construction company, known for delivering world-class developments that stand the test of time.', 'mission' => 'To create exceptional living and working spaces through innovative design, superior craftsmanship, and an unwavering commitment to client satisfaction.', 'values' => [['title' => 'Integrity', 'description' => 'Transparent dealings and honest partnerships built on trust.'], ['title' => 'Excellence', 'description' => 'Uncompromising quality in every project we undertake.'], ['title' => 'Commitment', 'description' => 'Dedicated to delivering beyond expectations, every time.'], ['title' => 'Partnership', 'description' => 'Building lasting relationships with clients, investors, and communities.']]]]);

        $consultationSettings = ConsultationSetting::query()->create([
            'timezone' => 'Africa/Lagos',
            'slot_duration_minutes' => 60,
            'buffer_minutes' => 15,
            'booking_window_days' => 60,
            'minimum_notice_hours' => 12,
            'notification_email' => 'support@rockmould.com',
        ]);

        collect([
            ['day_of_week' => 1, 'label' => 'Monday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1],
            ['day_of_week' => 2, 'label' => 'Tuesday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1],
            ['day_of_week' => 3, 'label' => 'Wednesday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1],
            ['day_of_week' => 4, 'label' => 'Thursday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1],
            ['day_of_week' => 5, 'label' => 'Friday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1],
            ['day_of_week' => 6, 'label' => 'Saturday Consultations', 'start_time' => '10:00:00', 'end_time' => '14:00:00', 'sort_order' => 1],
        ])->each(fn (array $window) => ConsultationAvailability::query()->create($window));

        ConsultationBlockedDate::query()->create([
            'blocked_on' => now('Africa/Lagos')->addDays(14)->toDateString(),
            'is_all_day' => true,
            'reason' => 'Executive strategy retreat',
        ]);

        ConsultationBooking::query()->create([
            'reference' => 'CONS-SEEDED-001',
            'consultation_type' => 'project_consultation',
            'full_name' => 'Olumide Afolayan',
            'email' => 'olumide@example.com',
            'phone' => '+2348091111111',
            'company_name' => 'Prime Crest Holdings',
            'subject' => 'Azure Towers acquisition discussion',
            'message' => 'We would like to review premium unit options and expected delivery milestones.',
            'consultation_date' => now($consultationSettings->timezone)->addDays(3)->toDateString(),
            'start_time' => '11:30:00',
            'end_time' => '12:30:00',
            'timezone' => $consultationSettings->timezone,
            'status' => ConsultationBooking::STATUS_PENDING,
            'assigned_staff_id' => $projectManager->id,
            'admin_notes' => 'Initial request awaiting confirmation.',
        ]);

        ConsultationBooking::query()->create([
            'reference' => 'CONS-SEEDED-002',
            'consultation_type' => 'investment_discussion',
            'full_name' => 'Nkem Duru',
            'email' => 'nkem@example.com',
            'phone' => '+2348092222222',
            'company_name' => 'Atlas Equity',
            'subject' => 'Mixed-use development investment review',
            'message' => 'Please share investment structure expectations and near-term opportunities.',
            'consultation_date' => now($consultationSettings->timezone)->addDays(5)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'timezone' => $consultationSettings->timezone,
            'status' => ConsultationBooking::STATUS_CONFIRMED,
            'assigned_staff_id' => $admin->id,
            'meeting_link' => 'https://meet.google.com/example-rockmould',
            'admin_notes' => 'Confirmed with investor relations lead.',
        ]);

        collect([
            ['user_id' => $admin->id, 'action' => 'system.seeded', 'description' => 'Initial Rockmould platform data seeded.'],
            ['user_id' => $projectManager->id, 'action' => 'project.updated', 'description' => 'Azure Towers update published to client portal.'],
            ['user_id' => $finance->id, 'action' => 'payment.recorded', 'description' => 'March 2026 Azure Towers payment received.'],
        ])->each(fn (array $log) => ActivityLog::query()->create([
            'user_id' => $log['user_id'],
            'action' => $log['action'],
            'description' => $log['description'],
            'properties' => ['seeded' => true],
        ]));
    }
}
