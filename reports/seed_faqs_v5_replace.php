<?php

\App\Models\Faq::query()->delete();

$faqs = [
    // Premium Brand
    ['category' => 'Premium Brand', 'question' => 'Why choose Rockmould?', 'answer' => 'Because your home should be more than a place to live. At Rockmould, we create thoughtfully designed residential communities that combine exceptional craftsmanship, timeless architecture, and enduring value. Every development reflects our commitment to quality, integrity, and excellence.', 'sort_order' => 1],
    ['category' => 'Premium Brand', 'question' => 'What defines a Rockmould home?', 'answer' => 'A Rockmould home is designed to elevate everyday living. From intelligent layouts and quality finishes to carefully planned surroundings, every detail is considered to deliver comfort, functionality, and lasting value.', 'sort_order' => 2],
    ['category' => 'Premium Brand', 'question' => 'What makes Rockmould different?', 'answer' => 'Our developments are guided by a simple philosophy: build with purpose. We focus on creating homes that remain relevant for decades through quality construction, thoughtful planning, and a commitment to delivering beyond expectations.', 'sort_order' => 3],
    ['category' => 'Premium Brand', 'question' => 'What is your approach to residential development?', 'answer' => 'We believe every successful development begins with understanding how people live. Our communities are planned to balance architecture, comfort, accessibility, and long-term value while creating places families are proud to call home.', 'sort_order' => 4],
    ['category' => 'Premium Brand', 'question' => 'What inspires your developments?', 'answer' => 'Every Rockmould development is inspired by the belief that exceptional homes improve lives. We create environments that encourage connection, comfort, security, and a genuine sense of belonging.', 'sort_order' => 5],
    ['category' => 'Premium Brand', 'question' => 'How do you select development locations?', 'answer' => 'We invest significant time in understanding each location before development begins. Accessibility, infrastructure, future growth, neighbourhood potential, and long-term value are all carefully evaluated.', 'sort_order' => 6],
    ['category' => 'Premium Brand', 'question' => 'How do you maintain quality?', 'answer' => 'Quality is not an inspection at the end of construction. It is a standard applied from the first concept to final delivery. Every stage of development is managed with careful oversight to ensure the finished home reflects the Rockmould standard.', 'sort_order' => 7],
    ['category' => 'Premium Brand', 'question' => 'What does luxury mean to Rockmould?', 'answer' => 'Luxury is found in thoughtful design, enduring quality, comfortable living spaces, premium craftsmanship, and attention to every detail. It is about creating homes that improve everyday life.', 'sort_order' => 8],
    ['category' => 'Premium Brand', 'question' => 'Who are Rockmould developments designed for?', 'answer' => 'Our homes are created for discerning homeowners, families, professionals, Nigerians in the diaspora, and investors who value quality, trust, and long-term ownership.', 'sort_order' => 9],
    ['category' => 'Premium Brand', 'question' => 'Why is buying from a trusted developer important?', 'answer' => 'The developer you choose shapes your entire ownership experience. A trusted developer delivers quality, communicates openly, honours commitments, and stands behind every home it builds.', 'sort_order' => 10],
    ['category' => 'Premium Brand', 'question' => 'How does Rockmould create long-term value?', 'answer' => 'Long-term value comes from disciplined planning, strategic locations, quality construction, timeless design, and developments that remain desirable as communities continue to grow.', 'sort_order' => 11],
    ['category' => 'Premium Brand', 'question' => 'How does Rockmould approach design?', 'answer' => 'We believe great design should be beautiful and practical. Every home is planned to maximise natural light, comfort, functionality, and efficient use of space while maintaining architectural elegance.', 'sort_order' => 12],
    ['category' => 'Premium Brand', 'question' => 'What should clients expect throughout their journey?', 'answer' => 'Clients can expect transparency, professionalism, timely communication, and dedicated support from the first enquiry through project completion and beyond.', 'sort_order' => 13],
    ['category' => 'Premium Brand', 'question' => 'How does Rockmould build trust?', 'answer' => 'Trust is earned through consistency. We communicate honestly, deliver responsibly, and maintain high standards across every aspect of our business.', 'sort_order' => 14],
    ['category' => 'Premium Brand', 'question' => 'What is the Rockmould Promise?', 'answer' => 'To create exceptional homes through uncompromising quality, responsible development, transparent relationships, and an unwavering commitment to lasting value.', 'sort_order' => 15],

    // Homeownership
    ['category' => 'Homeownership', 'question' => 'Why should I buy a newly developed home?', 'answer' => 'A newly developed home offers modern design, efficient layouts, updated construction standards, and lower maintenance requirements while providing greater long-term value.', 'sort_order' => 16],
    ['category' => 'Homeownership', 'question' => 'What makes a home a lasting investment?', 'answer' => 'A lasting investment combines excellent location, quality construction, timeless architecture, and sustained market demand. These principles guide every Rockmould development.', 'sort_order' => 17],
    ['category' => 'Homeownership', 'question' => 'How does Rockmould plan for future generations?', 'answer' => 'We build homes designed to remain valuable and relevant over time, creating assets that families can enjoy and pass on with confidence.', 'sort_order' => 18],
    ['category' => 'Homeownership', 'question' => 'What role does community play?', 'answer' => 'A home extends beyond its walls. We believe thoughtfully planned communities encourage stronger relationships, greater security, and a higher quality of life.', 'sort_order' => 19],
    ['category' => 'Homeownership', 'question' => 'What does excellence mean at Rockmould?', 'answer' => 'Excellence is reflected in every decision we make. It shapes our planning, design, construction, customer experience, and commitment to continuous improvement.', 'sort_order' => 20],

    // Brand Philosophy
    ['category' => 'Brand Philosophy', 'question' => 'What is Rockmould\'s vision?', 'answer' => 'To become one of Africa\'s most respected residential real estate developers by creating homes and communities defined by quality, trust, and lasting value.', 'sort_order' => 21],
    ['category' => 'Brand Philosophy', 'question' => 'What legacy is Rockmould building?', 'answer' => 'We aspire to create communities that continue to enrich lives for generations while setting new standards for residential development.', 'sort_order' => 22],
    ['category' => 'Brand Philosophy', 'question' => 'Why do clients return to Rockmould?', 'answer' => 'Because lasting relationships are built on trust, consistent delivery, and an unwavering commitment to quality.', 'sort_order' => 23],
    ['category' => 'Brand Philosophy', 'question' => 'What does Rockmould believe about homeownership?', 'answer' => 'We believe owning a home is one of life\'s greatest achievements. Every Rockmould home is designed to provide security, pride, comfort, and lasting value.', 'sort_order' => 24],
    ['category' => 'Brand Philosophy', 'question' => 'If you could describe Rockmould in one sentence, what would it be?', 'answer' => 'Rockmould creates exceptional homes where timeless design, uncompromising quality, and lasting value come together to shape better living.', 'sort_order' => 25],
];

foreach ($faqs as $faq) {
    \App\Models\Faq::create(array_merge($faq, ['is_published' => true]));
}
echo count($faqs) . " FAQs created.\n";
