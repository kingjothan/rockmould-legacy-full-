<?php
/**
 * Append Client Portal and Website & Navigation FAQ categories.
 * Does NOT delete existing FAQs — only adds the new categories.
 *
 * Run via:
 * php artisan tinker --execute="require base_path('reports/seed_faqs_v7_portal_nav.php');"
 */

use App\Models\Faq;

// Remove only these two categories if they already exist (safe re-run)
Faq::query()->whereIn('category', ['Client Portal', 'Website & Navigation'])->delete();

$faqs = [

    // ── Client Portal ────────────────────────────────────────────────────────
    ['Client Portal', "What is the Rockmould client portal?",
        "The client portal is a secure, private dashboard exclusively for Rockmould buyers and investors. Once you have purchased or reserved a property, you are given access to track your project's progress, view payment records, download documents, and communicate directly with our team — all in one place."],
    ['Client Portal', "How do I get access to the client portal?",
        "Access is set up for you after your reservation or purchase is confirmed. Our team will send you login credentials to the email address you registered with. If you have not received your details, contact us and we will get them to you promptly."],
    ['Client Portal', "What can I do inside the client portal?",
        "Inside the portal you can:\n- Track live construction progress on your project\n- View and download your payment history and receipts\n- Access your project documents and title paperwork\n- Submit enquiries and receive responses from our team\n- View updates, milestones, and site photos posted by our project managers"],
    ['Client Portal', "Can I track my payment history on the portal?",
        "Yes. Every payment you make is recorded in the portal. You can view your full payment history, see outstanding balances, and download official receipts at any time."],
    ['Client Portal', "Can I download my receipts and documents from the portal?",
        "Yes. Payment receipts, invoices, contracts, and other documents related to your purchase are available to download directly from your portal dashboard."],
    ['Client Portal', "How do I reset my portal password?",
        "Use the 'Forgot Password' link on the portal login page and a reset link will be sent to your registered email address. If you run into any issues, contact our support team and we will assist you."],
    ['Client Portal', "Can I access the portal on my phone?",
        "Yes. The portal is designed to work on any device — desktop, tablet, or mobile — so you can check your project status and documents wherever you are."],
    ['Client Portal', "Is my data secure on the client portal?",
        "Yes. The portal uses secure, encrypted connections and your data is accessible only to you and authorised members of our team. We take the privacy and security of your information seriously."],
    ['Client Portal', "Can more than one person access the same portal account?",
        "If a property is jointly owned, we can arrange access for multiple parties. Contact our team after purchase and we will set up the appropriate account access."],
    ['Client Portal', "I am having trouble logging in. What should I do?",
        "First try the 'Forgot Password' option on the login page. If the problem persists, contact our support team with your registered email address and we will resolve it for you."],

    // ── Website & Navigation ─────────────────────────────────────────────────
    ['Website & Navigation', "What can I find on the Rockmould website?",
        "The website covers everything about Rockmould as a company — our projects (active and completed), gallery of construction progress and finished work, blog and news, FAQ, contact details, and direct links to the client portal. It is the first stop for any information about our developments and services."],
    ['Website & Navigation', "Where can I view current and upcoming projects?",
        "Visit the Projects section from the main navigation. Each project has its own page with details on location, specification, progress, and how to enquire or reserve a unit."],
    ['Website & Navigation', "Where is the gallery of project photos and videos?",
        "The Gallery is accessible from the main menu. It is organised by project and section — exterior views, interior fit-out, general project media, and live construction updates — so you can browse all our visual content in one place."],
    ['Website & Navigation', "How do I contact Rockmould?",
        "Use the 'Get in Touch' button in the navigation bar, or visit our Contact page, to reach our sales and support team. You can send an enquiry directly from the website and a member of our team will respond promptly."],
    ['Website & Navigation', "Where can I read about Rockmould's story and team?",
        "Visit the About section from the navigation menu to learn about our company history, values, team, and approach to development."],
    ['Website & Navigation', "Where can I find your blog and news?",
        "Our Blog is accessible from the Resources section in the navigation. It covers property market insights, development updates, investment guidance, and company news."],
    ['Website & Navigation', "How do I send a general enquiry from the website?",
        "Click 'Get in Touch' in the top navigation bar or scroll to the contact form on any project page. Fill in your details and message and our team will be in touch shortly."],
    ['Website & Navigation', "Can I submit a review or testimonial on the website?",
        "Yes. If you are a Rockmould buyer or client and would like to share your experience, you can submit a review directly on our website. Approved reviews are published on our testimonials section."],
    ['Website & Navigation', "How do I reach the client portal from the website?",
        "Click the 'Client Portal' button in the top navigation bar on any page of the website. You will be taken to the secure login page where you can sign in with your registered credentials."],
    ['Website & Navigation', "Can I get newsletter updates from Rockmould?",
        "Yes. You can subscribe to our newsletter to receive project updates, market insights, and news directly to your inbox. Look for the newsletter sign-up section on our website."],

];

$sort = 1;
$lastCat = null;
foreach ($faqs as $row) {
    if ($lastCat !== $row[0]) { $sort = 1; $lastCat = $row[0]; }
    Faq::query()->create([
        'category'     => $row[0],
        'question'     => $row[1],
        'answer'       => $row[2],
        'is_published' => true,
        'sort_order'   => $sort++,
    ]);
}

$added = count($faqs);
$total = Faq::query()->count();
echo "Added {$added} FAQs. Total in database: {$total}.\n";
