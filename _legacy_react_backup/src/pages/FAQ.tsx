import { useState } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { Search, ChevronDown, ChevronUp, Mail } from "lucide-react";

const faqCategories = [
  {
    name: "General",
    faqs: [
      {
        q: "What does Rockmould Limited do?",
        a: "Rockmould Limited is a premium real estate development and construction company. We deliver luxury residential, commercial, and mixed-use properties, along with comprehensive construction and property advisory services.",
      },
      {
        q: "Where are your projects located?",
        a: "Our projects are primarily located across prime areas in Lagos, Nigeria, including Victoria Island, Ikoyi, Lekki, and Banana Island. We are expanding into other major cities as part of our growth strategy.",
      },
      {
        q: "How long has Rockmould been in business?",
        a: "Rockmould has been delivering excellence for over 20 years, having been founded in 2004. We have completed over 150 projects with a portfolio value exceeding NGN 50 billion.",
      },
    ],
  },
  {
    name: "Projects and Investment",
    faqs: [
      {
        q: "How can I invest in a Rockmould project?",
        a: "You can explore our current projects on our website and reach out through our contact form or call our sales team directly. We also have a dedicated partnerships page for investors.",
      },
      {
        q: "What payment plans are available?",
        a: "We offer flexible payment plans tailored to each project. Options include outright purchase, installment payments, and mortgage facilitation. Contact our sales team for specific project payment structures.",
      },
      {
        q: "Can I visit project sites?",
        a: "Yes, we welcome site visits for prospective buyers and investors. Please contact our team to schedule a guided tour of any of our active projects.",
      },
    ],
  },
  {
    name: "Client Portal",
    faqs: [
      {
        q: "How do I access the client portal?",
        a: "You can access the portal using your registered phone number. If you are a new client, you can submit a KYC request for account setup.",
      },
      {
        q: "What can I do in the client portal?",
        a: "The portal allows you to track project progress, view documents, monitor payments, access media galleries, and communicate with your account manager.",
      },
      {
        q: "I forgot my registered phone number. What should I do?",
        a: "Contact our support team via email at support@rockmould.com or call +234 123 456 7890 for assistance with your account.",
      },
    ],
  },
];

export default function FAQPage() {
  const [search, setSearch] = useState("");
  const [activeCategory, setActiveCategory] = useState("General");
  const [openFaq, setOpenFaq] = useState<string | null>(null);

  const currentFaqs =
    faqCategories
      .find((category) => category.name === activeCategory)
      ?.faqs.filter(
        (faq) =>
          faq.q.toLowerCase().includes(search.toLowerCase()) ||
          faq.a.toLowerCase().includes(search.toLowerCase()),
      ) ?? [];

  return (
    <PublicLayout>
      <HeroSection
        title="Frequently Asked Questions"
        subtitle="Support"
        description="Find answers to common questions about our services, projects, and client portal."
        height="medium"
      />

      <section className="section-padding">
        <div className="container-luxury max-w-4xl">
          <div className="relative mb-10">
            <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground" />
            <input
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Search questions..."
              className="w-full bg-card border border-border rounded-md pl-12 pr-4 py-4 text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors"
            />
          </div>

          <div className="flex gap-2 mb-10">
            {faqCategories.map((category) => (
              <button
                key={category.name}
                onClick={() => setActiveCategory(category.name)}
                className={`px-4 py-2 text-xs uppercase tracking-[0.12em] rounded-sm border transition-all ${
                  activeCategory === category.name
                    ? "bg-primary text-primary-foreground border-primary"
                    : "border-border text-muted-foreground hover:border-primary/30"
                }`}
              >
                {category.name}
              </button>
            ))}
          </div>

          <div className="space-y-3">
            {currentFaqs.length === 0 ? (
              <div className="text-center py-12">
                <p className="text-muted-foreground">No matching questions found.</p>
              </div>
            ) : (
              currentFaqs.map((faq) => (
                <motion.div key={faq.q} initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="luxury-card">
                  <button
                    onClick={() => setOpenFaq(openFaq === faq.q ? null : faq.q)}
                    className="w-full flex items-center justify-between text-left"
                  >
                    <span className="font-medium pr-4">{faq.q}</span>
                    {openFaq === faq.q ? (
                      <ChevronUp className="w-4 h-4 text-primary flex-shrink-0" />
                    ) : (
                      <ChevronDown className="w-4 h-4 text-muted-foreground flex-shrink-0" />
                    )}
                  </button>
                  {openFaq === faq.q && (
                    <motion.div
                      initial={{ height: 0, opacity: 0 }}
                      animate={{ height: "auto", opacity: 1 }}
                      className="mt-4 text-sm text-muted-foreground leading-relaxed border-t border-border pt-4"
                    >
                      {faq.a}
                    </motion.div>
                  )}
                </motion.div>
              ))
            )}
          </div>

          <div className="luxury-card text-center mt-16 p-12">
            <Mail className="w-8 h-8 text-primary mx-auto mb-4" />
            <h3 className="font-display text-xl font-semibold mb-2">Still Have Questions?</h3>
            <p className="text-muted-foreground mb-6">
              Our team is ready to help with any questions you may have.
            </p>
            <Link to="/contact" className="luxury-btn-primary text-xs">
              Contact Support
            </Link>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
