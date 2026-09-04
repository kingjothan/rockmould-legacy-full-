<?php
// Seeds privacy_sections and terms_sections into the legal_pages setting
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;

$privacySections = [
    [
        'title' => 'Introduction',
        'body'  => '<p>Rockmould Limited ("Rockmould", "we", "us" or "our") is committed to protecting the privacy and personal data of everyone who visits our website, requests our brochures, makes an enquiry, or otherwise interacts with us. This Privacy Policy explains what personal data we collect, why we collect it, how we use and protect it, and the rights available to you under the Nigeria Data Protection Act 2023 ("NDPA") and related regulations issued by the Nigeria Data Protection Commission ("NDPC").</p>',
    ],
    [
        'title' => 'Who We Are (Data Controller)',
        'body'  => '<p>For the purposes of the NDPA, the data controller responsible for your personal data is:</p><ul><li><strong>Company:</strong> Rockmould Limited</li><li><strong>Registered address:</strong> 24A Taiye Olowu Street, Lekki Phase 1, Lagos, Nigeria</li><li><strong>RC number:</strong> 1853172</li><li><strong>Email:</strong> <a href="mailto:info@rockmould.com">info@rockmould.com</a></li><li><strong>Phone:</strong> +234 201 4537 464</li><li><strong>Data Protection Officer / Contact:</strong> Jonathan Okoiruele</li></ul>',
    ],
    [
        'title' => 'Scope of This Policy',
        'body'  => '<p>This policy applies to personal data we process through our website, online forms, brochure downloads, the client/customer portal, our booking system, email and telephone enquiries, and in the ordinary course of providing our real estate and construction services. Where we link to third-party websites or platforms, those services are governed by their own privacy policies.</p>',
    ],
    [
        'title' => 'Personal Data We Collect',
        'body'  => '<p>Depending on how you interact with us, we may collect the following categories of personal data:</p><ul><li><strong>Identity and contact data:</strong> full name, email address, phone number, and postal or business address.</li><li><strong>Enquiry and preference data:</strong> property or project of interest, budget range, unit preferences, viewing requests, and the content of messages you send us.</li><li><strong>Lead-capture data:</strong> information you provide to download gated brochures or marketing materials, and to subscribe to updates.</li><li><strong>Booking data:</strong> dates, times and details relating to viewings, appointments or consultations booked through the website.</li><li><strong>Portal and account data:</strong> login credentials, account activity, documents and communications exchanged through the customer portal.</li><li><strong>Transaction and financial data:</strong> where applicable, information relating to expressions of interest, reservations, payments or contracts.</li><li><strong>Technical and usage data:</strong> IP address, browser type, device information, pages visited, referral source, and similar data collected through cookies and analytics tools.</li></ul>',
    ],
    [
        'title' => 'How We Collect Your Data',
        'body'  => '<ul><li>Directly from you, when you complete a form, request a brochure, make an enquiry, book an appointment, register for the portal, or communicate with us.</li><li>Automatically, through cookies and similar technologies when you use our website.</li><li>From third parties, such as referral partners, marketing platforms or publicly available sources, where lawful.</li></ul>',
    ],
    [
        'title' => 'Why We Use Your Data and Our Lawful Basis',
        'body'  => '<p>Under the NDPA we must have a lawful basis for processing personal data. The table below sets out our main purposes and the corresponding lawful basis:</p><div class="tbl-wrap"><table><thead><tr><th>Purpose of processing</th><th>Lawful basis under the NDPA</th></tr></thead><tbody><tr><td>Responding to enquiries and providing information about our properties and services</td><td>Consent; and/or steps taken at your request prior to entering a contract</td></tr><tr><td>Providing gated brochures and requested marketing materials</td><td>Consent</td></tr><tr><td>Managing viewings, bookings and appointments</td><td>Performance of a contract or pre-contractual steps</td></tr><tr><td>Administering the customer portal and your account</td><td>Performance of a contract; legitimate interest</td></tr><tr><td>Processing reservations, contracts and related transactions</td><td>Performance of a contract; compliance with a legal obligation</td></tr><tr><td>Sending marketing updates, offers and newsletters</td><td>Consent (which you may withdraw at any time)</td></tr><tr><td>Improving our website, security and services through analytics</td><td>Legitimate interest; consent for non-essential cookies</td></tr><tr><td>Meeting legal, regulatory, tax and record-keeping obligations</td><td>Compliance with a legal obligation</td></tr></tbody></table></div>',
    ],
    [
        'title' => 'Cookies and Tracking Technologies',
        'body'  => '<p>Our website uses cookies and similar technologies to enable core functionality, remember your preferences, and understand how the site is used. Essential cookies are necessary for the site to work. Non-essential cookies (such as analytics and marketing cookies) are used only with your consent, which you can give, decline or withdraw through our cookie banner or your browser settings.</p>',
    ],
    [
        'title' => 'Marketing Communications',
        'body'  => '<p>Where you have consented, we may send you information about our projects, offers and events. Every marketing message will include a simple way to unsubscribe, and you can opt out at any time by contacting us. Opting out of marketing does not affect service-related communications, such as responses to your enquiries or booking confirmations.</p>',
    ],
    [
        'title' => 'Sharing and Disclosure of Your Data',
        'body'  => '<p>We do not sell your personal data. We may share it, only as necessary, with:</p><ul><li><strong>Service providers and processors</strong> who support our operations (for example website hosting, email, analytics, CRM, and IT support), under contracts that require them to protect your data.</li><li><strong>Professional advisers</strong> such as legal, accounting and consultancy partners, where required.</li><li><strong>Regulators, courts and authorities</strong> where disclosure is required by law or to protect our legal rights.</li><li><strong>Prospective or actual purchasers</strong> of our business or assets, in the event of a reorganisation, merger or sale, subject to appropriate safeguards.</li></ul>',
    ],
    [
        'title' => 'Cross-Border Data Transfers',
        'body'  => '<p>Some of our service providers may store or process data outside Nigeria. Where personal data is transferred outside Nigeria, we will ensure the transfer complies with the NDPA, including that the destination provides an adequate level of protection or that appropriate safeguards and lawful transfer mechanisms recognised by the NDPC are in place.</p>',
    ],
    [
        'title' => 'Data Retention',
        'body'  => '<p>We keep personal data only for as long as necessary for the purposes described in this policy, or as required to meet legal, regulatory, tax and accounting obligations, after which it is securely deleted or anonymised. Retention periods vary by data type and the nature of our relationship with you.</p>',
    ],
    [
        'title' => 'How We Protect Your Data',
        'body'  => '<p>We apply appropriate technical and organisational measures to protect personal data against unauthorised access, loss, misuse or disclosure. These include access controls, encryption in transit where appropriate, secure hosting, and staff handling procedures. No online transmission or storage is completely secure, but we work to protect your data and to respond promptly to any incident in line with our obligations under the NDPA.</p>',
    ],
    [
        'title' => 'Your Rights Under the NDPA',
        'body'  => '<p>Subject to the conditions set out in the NDPA, you have the right to:</p><ul><li>be informed about how your personal data is processed;</li><li>access the personal data we hold about you;</li><li>request correction of inaccurate or incomplete data;</li><li>request deletion of your data in certain circumstances (the "right to be forgotten");</li><li>restrict or object to processing in certain circumstances;</li><li>request portability of data you have provided to us;</li><li>withdraw consent at any time, where processing is based on consent;</li><li>not be subject to a decision based solely on automated processing that significantly affects you; and</li><li>lodge a complaint with the Nigeria Data Protection Commission.</li></ul><p>To exercise any of these rights, contact us using the details in section 15. We will respond within the timeframe required by the NDPA. There is normally no charge, though we may charge a reasonable fee or decline clearly unfounded or excessive requests as permitted by law.</p>',
    ],
    [
        'title' => "Children's Data",
        'body'  => '<p>Our website and services are intended for adults. We do not knowingly collect personal data from children without the consent of a parent or guardian as required by the NDPA. If you believe a child has provided us with personal data, please contact us so we can take appropriate action.</p>',
    ],
    [
        'title' => 'How to Contact Us and Make a Complaint',
        'body'  => '<p>For any question about this policy or to exercise your rights, please contact:</p><ul><li><strong>Data Protection Contact:</strong> Jonathan Okoiruele</li><li><strong>Email:</strong> <a href="mailto:info@rockmould.com">info@rockmould.com</a></li><li><strong>Address:</strong> 24A Taiye Olowu Street, Lekki Phase 1, Lagos, Nigeria</li></ul><p>If you are not satisfied with our response, you may lodge a complaint with the Nigeria Data Protection Commission (NDPC), the regulator responsible for data protection in Nigeria.</p>',
    ],
    [
        'title' => 'Changes to This Policy',
        'body'  => '<p>We may update this policy from time to time. The latest version will always be available on our website. Material changes will be communicated where appropriate.</p>',
    ],
];

$termsSections = [
    [
        'title' => 'Acceptance of These Terms',
        'body'  => '<p>These Terms and Conditions ("Terms") govern your access to and use of the Rockmould Limited website and related services (the "Website"). By accessing or using the Website, you agree to be bound by these Terms. If you do not agree, please do not use the Website.</p>',
    ],
    [
        'title' => 'Definitions',
        'body'  => '<ul><li><strong>"Rockmould", "we", "us", "our"</strong> means Rockmould Limited.</li><li><strong>"You", "user"</strong> means any person accessing or using the Website.</li><li><strong>"Content"</strong> means all text, images, designs, plans, brochures, media and materials on the Website.</li><li><strong>"Listings"</strong> means property, project and unit information published on the Website.</li></ul>',
    ],
    [
        'title' => 'Use of the Website',
        'body'  => '<p>You may use the Website for lawful purposes only. You agree not to use the Website in any way that is unlawful, harmful, or that could damage, disable or impair its operation or interfere with any other user\'s use of it.</p>',
    ],
    [
        'title' => 'Property Listings and Information',
        'body'  => '<p>Listings, prices, availability, dimensions, specifications, images, renders and completion timelines are provided for general information and marketing purposes only. They may change without notice and do not constitute an offer, contract, or a representation or warranty of any kind. Computer-generated images and artist impressions are illustrative and may differ from the finished property. No information on the Website should be relied upon as the sole basis for any decision. Any purchase, reservation or engagement is subject to a separate written agreement and applicable due diligence.</p>',
    ],
    [
        'title' => 'Brochures and Downloadable Materials',
        'body'  => '<p>Brochures and other materials may require you to provide contact details before download. By requesting them, you consent to us contacting you in connection with your request and, where you have opted in, sending you related marketing, in line with our Privacy Policy. These materials remain our property and are provided for your personal, non-commercial reference only.</p>',
    ],
    [
        'title' => 'Enquiries and Bookings',
        'body'  => '<p>Submitting an enquiry or booking a viewing or appointment through the Website does not create a binding agreement to sell, lease or reserve any property. All bookings are subject to availability and confirmation by us. We may reschedule or decline any booking where reasonably necessary.</p>',
    ],
    [
        'title' => 'Customer Portal and Accounts',
        'body'  => '<p>Certain features may require you to register for an account or access the customer portal. You are responsible for keeping your login details confidential and for all activity under your account. You must notify us promptly of any unauthorised use. We may suspend or terminate access where these Terms are breached or where necessary to protect the Website or other users.</p>',
    ],
    [
        'title' => 'Intellectual Property',
        'body'  => '<p>All Content on the Website, including the Rockmould name, logo, project names, designs, text, graphics and images, is owned by or licensed to Rockmould and is protected by intellectual property laws. You may not copy, reproduce, distribute, modify or use any Content for commercial purposes without our prior written consent.</p>',
    ],
    [
        'title' => 'Prohibited Conduct',
        'body'  => '<p>You must not:</p><ul><li>use the Website in breach of any applicable law or regulation;</li><li>attempt to gain unauthorised access to any part of the Website, its systems or the portal;</li><li>introduce viruses, malware or other harmful code;</li><li>scrape, harvest or collect data from the Website without our consent; or</li><li>post or transmit unlawful, misleading, defamatory or infringing material.</li></ul>',
    ],
    [
        'title' => 'Third-Party Links',
        'body'  => '<p>The Website may contain links to third-party websites or resources. We are not responsible for the content, availability or practices of those sites, and links do not imply endorsement. Your use of third-party sites is at your own risk and subject to their terms.</p>',
    ],
    [
        'title' => 'Disclaimers',
        'body'  => '<p>The Website and its Content are provided "as is" and "as available". To the fullest extent permitted by law, we make no warranties, express or implied, regarding the accuracy, completeness, reliability or availability of the Website or its Content. We do not warrant that the Website will be uninterrupted, error-free or free of harmful components.</p>',
    ],
    [
        'title' => 'Limitation of Liability',
        'body'  => '<p>To the fullest extent permitted by law, Rockmould shall not be liable for any indirect, incidental, special or consequential loss, or any loss of profits, revenue, data or goodwill, arising from your use of or inability to use the Website or reliance on its Content. Nothing in these Terms excludes or limits liability that cannot lawfully be excluded or limited.</p>',
    ],
    [
        'title' => 'Indemnity',
        'body'  => '<p>You agree to indemnify and hold Rockmould harmless from any claims, losses, liabilities and expenses arising out of your breach of these Terms or your misuse of the Website.</p>',
    ],
    [
        'title' => 'Privacy',
        'body'  => '<p>Your use of the Website is also governed by our Privacy Policy, which explains how we collect and handle personal data. By using the Website you acknowledge that policy.</p>',
    ],
    [
        'title' => 'Changes to These Terms',
        'body'  => '<p>We may update these Terms from time to time. The current version will always be available on the Website. Continued use of the Website after changes take effect constitutes acceptance of the revised Terms.</p>',
    ],
    [
        'title' => 'Governing Law and Jurisdiction',
        'body'  => '<p>These Terms are governed by and construed in accordance with the laws of the Federal Republic of Nigeria. Any dispute arising out of or in connection with the Website or these Terms shall be subject to the exclusive jurisdiction of the courts of Lagos State, Nigeria.</p>',
    ],
    [
        'title' => 'Contact',
        'body'  => '<p>For questions about these Terms, contact:</p><ul><li><strong>Rockmould Limited</strong> — 24A Taiye Olowu Street, Lekki Phase 1, Lagos, Nigeria</li><li><strong>Email:</strong> <a href="mailto:info@rockmould.com">info@rockmould.com</a></li><li><strong>Phone:</strong> +234 201 4537 464</li></ul>',
    ],
];

$current = Setting::query()->where('key', 'legal_pages')->value('value') ?? [];

$updated = array_merge($current, [
    'privacy_hero_title'       => 'Privacy Policy',
    'privacy_hero_subtitle'    => 'Legal',
    'privacy_hero_description' => 'How Rockmould Limited collects, uses and protects your personal data, in line with the Nigeria Data Protection Act 2023.',
    'privacy_sections'         => $privacySections,
    'terms_hero_title'         => 'Terms of Service',
    'terms_hero_subtitle'      => 'Legal',
    'terms_hero_description'   => 'The terms that govern your use of the Rockmould Limited website and related services.',
    'terms_sections'           => $termsSections,
]);

Setting::query()->updateOrCreate(['key' => 'legal_pages'], ['value' => $updated]);

echo "Done. Privacy: " . count($privacySections) . " sections. Terms: " . count($termsSections) . " sections.\n";
