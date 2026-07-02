<?php
/**
 * Seed FAQs from the rockmould_faq.html design file.
 * Clears all existing FAQs and replaces with the full set.
 *
 * Run via:
 * php artisan tinker --execute="require base_path('reports/seed_faqs_v6_html.php');"
 */

use App\Models\Faq;

Faq::query()->delete();
echo "Cleared all FAQs.\n";

$faqs = [

    // ── About Rockmould ──────────────────────────────────────────────────────
    ['About Rockmould', "What makes Rockmould different from other real estate developers?",
        "We control the full journey — from land and design through construction to handover and after-sales support — rather than acting only as a marketer or middleman. That gives buyers a single accountable party, tighter quality control, and a build standard we are willing to stand behind long after handover. Our focus is on doing a smaller number of developments properly rather than chasing volume."],
    ['About Rockmould', "What types of developments does Rockmould specialise in?",
        "We develop premium residential and mixed-use properties, with a focus on well-finished homes in prime Lagos locations, including luxury apartments, terraced homes, and mixed-use developments."],
    ['About Rockmould', "Where does Rockmould currently develop properties?",
        "Our developments are concentrated in Lagos, particularly in prime and high-growth corridors such as Ikoyi and the wider Lagos Island axis."],
    ['About Rockmould', "Why do clients choose Rockmould?",
        "Clients come to us for transparency, build quality, and reliable delivery. They know what they are paying for, they can track progress, and they receive a property that matches what was promised — backed by genuine after-handover support."],
    ['About Rockmould', "What is Rockmould's development philosophy?",
        "We build properties we would be comfortable owning ourselves. That means sound construction, thoughtful design, honest pricing, and developments that hold and grow their value over time rather than looking good only on launch day."],
    ['About Rockmould', "Is Rockmould a registered real estate developer?",
        "Yes. Rockmould Limited is a registered company operating within Nigerian property and corporate regulations."],
    ['About Rockmould', "Does Rockmould partner with international consultants?",
        "Where a project benefits from specialist expertise, we work with experienced local and international consultants across architecture, engineering, and project management."],
    ['About Rockmould', "Does Rockmould undertake bespoke developments?",
        "Yes. Alongside our standard developments, we take on bespoke projects for clients and landowners who want a property built to their own specification. Get in touch with our team to discuss scope and feasibility."],

    // ── Buying & Payment ─────────────────────────────────────────────────────
    ['Buying & Payment', "How do I purchase a Rockmould property?",
        "The process is straightforward:\n1. Send an enquiry through the website or speak to our sales team.\n2. Select your unit and agree the price and payment plan.\n3. Pay the reservation fee to secure the unit.\n4. Complete the sales documentation and your chosen payment schedule.\n5. Receive your title documents and, on completion, take handover."],
    ['Buying & Payment', "Can I reserve a unit before construction is completed?",
        "Yes. Many of our buyers reserve early — often during the off-plan stage — by paying a reservation fee that secures a specific unit at the current price while construction progresses."],
    ['Buying & Payment', "What is included in the purchase price?",
        "The purchase price covers the property itself with its specified finishes and fittings. Items such as legal fees, documentation charges, and service charges are usually separate, and we set these out clearly before you commit."],
    ['Buying & Payment', "What payment plans are available?",
        "We offer flexible options ranging from outright payment to structured instalments spread over the construction period, so you can choose what fits your cash flow. Contact our sales team to discuss the plans available for each project."],
    ['Buying & Payment', "What payment methods do you accept?",
        "We accept bank transfers and other secure channels into our official company accounts only. We never collect payments through unofficial or personal accounts — always confirm details directly with our sales team."],
    ['Buying & Payment', "Can I pay in foreign currency?",
        "We can accommodate foreign-currency buyers, with conversion handled through approved banking channels. Contact our team for details on how the rate is set."],
    ['Buying & Payment', "Can I purchase from outside Nigeria?",
        "Yes. We regularly work with diaspora and international buyers and handle the entire process remotely — including selection, documentation, payment, and progress updates — so you can buy with confidence without travelling."],
    ['Buying & Payment', "Can properties be jointly owned?",
        "Yes. Properties can be purchased and registered under joint ownership. We will guide you on how the names and ownership shares are reflected in the title and sales documents."],
    ['Buying & Payment', "Can I purchase through my company?",
        "Yes. You can buy in the name of a registered company. We will require your company's incorporation documents and authorised signatory details as part of the paperwork."],
    ['Buying & Payment', "What documents are required to purchase?",
        "Typically you will need:\n- A valid means of identification\n- Proof of address\n- Your completed reservation and sales forms\n\nCompany and joint purchases require additional documents. Our team will guide you through the KYC requirements."],
    ['Buying & Payment', "Do you accept mortgage financing?",
        "Where a buyer is using a mortgage or financing from an approved institution, we can work with that arrangement. Contact our sales team to discuss your specific situation."],
    ['Buying & Payment', "Are there hidden charges?",
        "No. We set out every cost upfront — including the purchase price and any separate legal, documentation, or service charges — before you commit. You will not be surprised by undisclosed fees."],
    ['Buying & Payment', "Are service charges included in the purchase price?",
        "Service charges are usually billed separately and cover the upkeep of shared facilities and security. We explain how they are calculated before you buy."],
    ['Buying & Payment', "What happens if I miss a payment?",
        "We will reach out to discuss your situation and, where possible, agree a workable solution. The specific consequences of missed or late payments are set out in your sales agreement."],
    ['Buying & Payment', "Is my deposit refundable?",
        "Refund terms depend on the stage and reason for cancellation and are governed by your sales agreement. Please speak with our sales team to understand the policy before committing."],
    ['Buying & Payment', "How long does the purchase process take?",
        "Reservation can be completed in a day once terms are agreed. The full process through to documentation and title depends on your payment plan and the project stage — we keep you informed at every step."],

    // ── Off-Plan Sales ───────────────────────────────────────────────────────
    ['Off-Plan Sales', "What does buying off-plan mean?",
        "Buying off-plan means purchasing a property before it is finished — sometimes before construction begins — based on approved designs and specifications. You commit early and the property is delivered to you on completion."],
    ['Off-Plan Sales', "Why should I buy before construction is completed?",
        "Off-plan units are usually priced lower than completed ones, so you lock in today's price and benefit from any appreciation during construction. You also get first pick of the best units and, in some cases, more flexibility on payment."],
    ['Off-Plan Sales', "Is buying off-plan safe?",
        "It is, when you buy from a developer with a track record and proper documentation. We back our off-plan sales with clear contracts, approved plans, and regular progress updates — so you always know where your money and your property stand."],
    ['Off-Plan Sales', "How are off-plan payments structured?",
        "Off-plan payments are typically staged, starting with a reservation and deposit, followed by instalments tied to the construction timeline. Our team will walk you through the specific structure for each project."],
    ['Off-Plan Sales', "Will the price increase after launch?",
        "Off-plan prices generally rise as construction advances and the launch phase closes — which is exactly why buying early tends to offer the best value. We confirm the price applicable to your reservation in writing."],
    ['Off-Plan Sales', "Can I sell my off-plan property before completion?",
        "In most cases yes, subject to our resale and transfer terms. We can assist with the resale through our sales channels."],
    ['Off-Plan Sales', "What happens if construction finishes earlier than expected?",
        "If we complete ahead of schedule, we notify you and bring forward the handover steps. Any outstanding balance falls due in line with your agreement before the unit is handed over."],
    ['Off-Plan Sales', "What happens if construction is delayed?",
        "We communicate any change to the timeline early and keep you updated through to completion. Your sales agreement sets out how delays are handled, so your position is protected."],

    // ── Construction & Design ────────────────────────────────────────────────
    ['Construction & Design', "How long does construction typically take?",
        "It depends on the size and type of development, but most of our residential projects run on a defined schedule that we share with buyers at the outset and track against throughout."],
    ['Construction & Design', "How do you ensure construction quality?",
        "Quality is controlled through experienced project supervision, vetted contractors, defined material standards, and stage-by-stage inspections — with a final quality review before handover. Nothing moves to the next phase until the current one meets our standard."],
    ['Construction & Design', "How do you manage project timelines?",
        "Each project runs to a planned schedule with defined milestones. Our project team monitors progress against that plan and manages resources and contractors to keep delivery on track."],
    ['Construction & Design', "How often do buyers receive construction updates?",
        "We provide regular progress updates throughout construction, including milestone reports and visuals, so off-plan and reserved buyers can follow their property's progress through our client portal."],
    ['Construction & Design', "Can buyers visit the site during construction?",
        "Yes. Buyers can arrange supervised site visits at suitable stages of construction. Visits are scheduled with our team to keep everyone safe on an active site."],
    ['Construction & Design', "What architectural style does Rockmould use?",
        "Our developments lean towards clean, contemporary design with an emphasis on practical, livable spaces suited to the Lagos climate and lifestyle. The aim is timeless rather than trend-driven, so the property still looks current years later."],
    ['Construction & Design', "Can buyers customise their interiors?",
        "Depending on the stage at which you buy, there is often room to personalise interior finishes and selected fittings. The earlier you commit, the more flexibility you usually have."],
    ['Construction & Design', "Are smart home features included?",
        "Selected developments include smart home and modern convenience features, either as standard or as an option. Ask our sales team about the specification for the project you are interested in."],
    ['Construction & Design', "What finishes come with each property?",
        "Each unit is delivered with a defined specification covering flooring, fittings, kitchen, and bathroom finishes, which we detail before purchase."],
    ['Construction & Design', "Do you use sustainable building materials?",
        "Where practical, we incorporate durable and environmentally responsible materials and design choices that improve efficiency and longevity."],
    ['Construction & Design', "How do you maximise space and natural lighting?",
        "Our designs prioritise efficient layouts, good cross-ventilation, and generous windows — so homes feel open and bright while staying comfortable in the Lagos climate."],

    // ── Legal, Ownership & Handover ──────────────────────────────────────────
    ['Legal, Ownership & Handover', "What title documents come with your properties?",
        "Buyers receive proper title documentation appropriate to the development, which may include a registered title, deed of assignment, and supporting purchase documents. The exact type is confirmed per project."],
    ['Legal, Ownership & Handover', "What ownership rights do buyers receive?",
        "Buyers receive full ownership rights to their property as conveyed in the title and sales documents, subject to the estate's rules and any shared-facility obligations."],
    ['Legal, Ownership & Handover', "What legal due diligence do you carry out before development?",
        "Before we develop, we verify the land's title and confirm it is free of encumbrances and adverse claims, and that the necessary approvals are in place. This protects every buyer who comes after."],
    ['Legal, Ownership & Handover', "Are your developments government approved?",
        "Yes. Our developments are pursued with the relevant statutory approvals and permits required for construction and sale, including building approvals from the appropriate Lagos State authorities."],
    ['Legal, Ownership & Handover', "Who prepares the sales documentation?",
        "Sales and title documentation is prepared and reviewed by our legal team and advisers to ensure it is accurate and properly executed. We recommend buyers also have their own lawyer review it."],
    ['Legal, Ownership & Handover', "What legal fees should buyers expect?",
        "Buyers should budget for legal and documentation fees, typically charged as a percentage of the purchase price in line with standard Nigerian property practice. We confirm the exact figure before you commit."],
    ['Legal, Ownership & Handover', "How is ownership transferred?",
        "Ownership is transferred through execution of the relevant title documents in your favour, followed by the appropriate registration and perfection steps, once your payment obligations are met."],
    ['Legal, Ownership & Handover', "How long does title perfection take?",
        "Title perfection in Lagos — including consent and registration — can take several months and depends on the relevant government processes rather than on us alone. We guide and support you through it."],
    ['Legal, Ownership & Handover', "What happens before handover?",
        "Before handover we complete final construction, carry out a full quality inspection, finalise outstanding documentation, and confirm your payment is settled. Only then is the unit prepared for you to receive."],
    ['Legal, Ownership & Handover', "Can I inspect my unit before handover?",
        "Yes. You are welcome to inspect your unit before accepting it, raise any issues, and have them addressed before the property is formally handed over to you."],
    ['Legal, Ownership & Handover', "What is included in the handover package?",
        "At handover you receive:\n- Keys to your unit\n- Completion and handover documentation\n- Your title documents\n- Applicable warranties and manuals\n- Guidance on your property and the estate"],

    // ── After Handover & Management ──────────────────────────────────────────
    ['After Handover & Management', "What happens after I receive my property?",
        "Once you take handover the property is yours to occupy, rent, or hold. We remain available for after-sales support and, where applicable, ongoing estate and facility management."],
    ['After Handover & Management', "Do you provide post-handover support?",
        "Yes. We provide after-handover support to help you settle in and to address any covered defects within the agreed period. Contact our support team through the client portal or by reaching out directly."],
    ['After Handover & Management', "Is there a defects liability period?",
        "Yes. Our properties come with a defects liability period during which we remedy qualifying construction defects at no cost to you."],
    ['After Handover & Management', "How do I report maintenance issues?",
        "You can report issues through our client portal or by contacting our after-sales team directly. We log and respond to all reported issues promptly."],
    ['After Handover & Management', "Who manages the estate, and do you provide facility management?",
        "Yes. For applicable developments we provide or arrange professional facility management covering security, maintenance, and the upkeep of shared spaces — so the estate is kept to standard long after handover."],
    ['After Handover & Management', "How are shared facilities maintained?",
        "Shared facilities are maintained by the appointed facility management team, funded through service charges — keeping security, cleaning, landscaping, and common areas in good condition."],
    ['After Handover & Management', "How are service charges calculated and reviewed?",
        "Service charges are based on the actual cost of running and maintaining the estate, shared across units on a fair basis, and reviewed periodically to reflect real running costs. Any changes are communicated to residents."],
    ['After Handover & Management', "Can you lease my property on my behalf?",
        "Yes. We can help owners lease their units, including marketing, tenant sourcing, and management. Contact us to discuss the terms of this service."],
    ['After Handover & Management', "Do you help investors find tenants?",
        "Yes. We assist investors with tenant sourcing and placement to help units start earning income quickly after completion."],
    ['After Handover & Management', "Do you provide short-let management?",
        "Where suitable, we can support short-let arrangements and management for owners who prefer that model. Contact us to discuss availability and terms."],

    // ── Investment ───────────────────────────────────────────────────────────
    ['Investment', "Why is real estate one of the safest investments?",
        "Real estate is a tangible asset that tends to hold its value and appreciate over time, while also generating rental income. In a market like Lagos — where demand for quality housing consistently outpaces supply — well-located property is a particularly resilient store of value."],
    ['Investment', "Why should I invest in a Rockmould development?",
        "You get a quality-built property in a sought-after location, backed by a developer that delivers and supports its buyers. That combination protects both the asset's value and your rental potential."],
    ['Investment', "What makes a property appreciate in value?",
        "Location, build quality, infrastructure, and demand are the main drivers. Properties in prime, well-connected areas with strong demand and good construction appreciate faster and more reliably than poorly located or poorly built ones."],
    ['Investment', "What factors influence rental income?",
        "Location, the quality and condition of the unit, available amenities, security, and overall demand in the area all shape what a property can command in rent and how consistently it stays occupied."],
    ['Investment', "What should investors consider before buying?",
        "Look at location and growth prospects, the developer's track record, title security, build quality, expected rental yield, and the total cost of ownership including service charges. We are happy to walk you through the numbers."],
    ['Investment', "Are your developments suitable for rental investments?",
        "Yes. Our developments are built and located with rental demand in mind, making them well suited to investors targeting both rental income and long-term appreciation."],
    ['Investment', "How do you protect investor value?",
        "We protect value through quality construction, secure title, sound location selection, and ongoing estate management that keeps developments desirable and well maintained over time."],
    ['Investment', "Can diaspora investors purchase remotely?",
        "Yes. We handle the full process remotely for diaspora investors — from selection and documentation to payment and updates — and can support tenant placement and management after completion."],

    // ── Joint Venture for Landowners ─────────────────────────────────────────
    ['Joint Venture for Landowners', "What is a Joint Venture development?",
        "A Joint Venture is a partnership where you provide the land and Rockmould provides the funding, design, and construction expertise to develop it. On completion, the value created is shared between both parties on agreed terms — so you unlock far more from your land than a straight sale."],
    ['Joint Venture for Landowners', "Why should I partner with Rockmould instead of selling my land?",
        "Selling gives you a one-off sum, while a Joint Venture lets you keep a stake in a developed, higher-value asset. You typically end up with completed units or a share of proceeds worth significantly more than the raw land's sale price — without funding the build yourself."],
    ['Joint Venture for Landowners', "How is profit shared?",
        "Profit or finished units are shared between you and Rockmould on a pre-agreed ratio set out in the Joint Venture agreement, reflecting the value of the land and our investment in developing it."],
    ['Joint Venture for Landowners', "Who finances the project?",
        "Rockmould finances the development, covering design, approvals, and construction costs — so you are not required to fund the build. Your contribution is the land."],
    ['Joint Venture for Landowners', "How do you protect landowners?",
        "Landowners are protected through a clear, legally binding Joint Venture agreement, secured ownership of your agreed share, defined timelines, and transparency throughout. Your interest in the land and the finished asset is documented and enforceable."],
    ['Joint Venture for Landowners', "What happens to my existing property?",
        "If there is an existing structure on the land, its treatment is agreed at the outset — whether it is incorporated, demolished, or compensated for — and reflected in the Joint Venture terms."],
    ['Joint Venture for Landowners', "What legal agreements are signed?",
        "A formal Joint Venture agreement is signed, supported by the relevant title and development documents, setting out each party's contribution, the sharing arrangement, timelines, and obligations. We recommend you have your own lawyer review it."],
    ['Joint Venture for Landowners', "Can I monitor construction progress?",
        "Yes. As a Joint Venture partner you receive regular progress updates and can arrange supervised site visits to follow the development through to completion."],
    ['Joint Venture for Landowners', "What happens if the project is delayed?",
        "We communicate any delay early and work to keep delivery on track. The Joint Venture agreement sets out how delays are treated, so your position is protected."],
    ['Joint Venture for Landowners', "When will I receive my completed units?",
        "Your agreed units or share are delivered on completion and handover of the development, in line with the timeline set out in the Joint Venture agreement."],

    // ── Living at a Rockmould Development ────────────────────────────────────
    ['Living at a Rockmould Development', "What security features do your developments include?",
        "Our developments are designed with security in mind, typically including controlled access, perimeter protection, CCTV surveillance, and on-site security personnel."],
    ['Living at a Rockmould Development', "Are your estates gated?",
        "Yes. Our estates are gated with controlled entry and exit points to keep residents and their property secure."],
    ['Living at a Rockmould Development', "Is visitor access controlled?",
        "Yes. Visitor access is managed at the gate through verification and access procedures, so only authorised people enter the estate."],
    ['Living at a Rockmould Development', "Is there 24-hour security?",
        "Yes. Our estates are secured around the clock through a combination of on-site personnel and surveillance systems."],
    ['Living at a Rockmould Development', "What amenities are included?",
        "Amenities vary by development but commonly include secure parking, recreational spaces, reliable power and water provisioning, and well-maintained common areas."],
    ['Living at a Rockmould Development', "Is parking allocated?",
        "Yes. Our developments provide allocated parking for residents, with arrangements for visitor parking where space allows."],
    ['Living at a Rockmould Development', "Are your developments family-friendly?",
        "Yes. Our developments are designed as safe, comfortable communities well suited to families — with secure surroundings and shared spaces."],

    // ── Resale & Warranty ────────────────────────────────────────────────────
    ['Resale & Warranty', "Can I resell my property after purchase?",
        "Yes. Once you own your property you are free to resell it, subject to any estate rules and, for off-plan units, our transfer terms."],
    ['Resale & Warranty', "Can Rockmould assist with resale?",
        "Yes. We can help you resell through our sales channels and buyer network, which often makes the process faster and smoother."],
    ['Resale & Warranty', "Does buying from Rockmould improve resale value?",
        "Quality construction, a good location, and a recognised developer all support stronger resale value — and a well-managed estate keeps demand high, all of which work in your favour at resale."],
    ['Resale & Warranty', "Do Rockmould properties come with warranties?",
        "Yes. Our properties come with a warranty covering the quality of construction and key elements of the build, giving you confidence in what you have bought."],
    ['Resale & Warranty', "What does the warranty cover?",
        "The warranty covers defects in construction and workmanship on the elements we are responsible for, such as structural and finishing defects that arise under normal use."],
    ['Resale & Warranty', "What is excluded from the warranty?",
        "The warranty generally excludes wear and tear, damage caused by misuse or alterations, and issues outside our construction responsibility."],

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

$total = Faq::query()->count();
echo "Done. {$total} FAQs seeded across " . count(array_unique(array_column($faqs, 0))) . " categories.\n";
