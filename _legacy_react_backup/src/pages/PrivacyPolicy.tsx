import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";

const sections = [
  {
    title: "Information We Collect",
    body: [
      "We may collect information you submit through consultation forms, contact requests, newsletter signups, partnership inquiries, and client portal KYC requests.",
      "Depending on the interaction, this can include your name, phone number, email address, company name, address, project interest, and supporting documents you choose to upload.",
    ],
  },
  {
    title: "How We Use Information",
    body: [
      "Rockmould Limited uses submitted information to respond to inquiries, coordinate consultations, evaluate portal access requests, manage project communication, and improve the experience across the website and client portal.",
      "We may also use limited operational data to monitor platform performance, improve service delivery, and maintain security.",
    ],
  },
  {
    title: "Sharing and Disclosure",
    body: [
      "We do not sell personal information. Data may be shared with internal teams, trusted service providers, or professional advisers only where needed to operate the website, process requests, support client delivery, or meet legal obligations.",
      "Where external support is used, access should be limited to the information reasonably required for that service.",
    ],
  },
  {
    title: "Retention and Security",
    body: [
      "We retain information for as long as reasonably necessary to support client relationships, operational reporting, compliance, dispute resolution, and legitimate business records.",
      "We aim to use appropriate administrative and technical safeguards for submitted data, especially within client-facing and internal management workflows.",
    ],
  },
  {
    title: "Your Choices",
    body: [
      "You may contact Rockmould to request updates to inaccurate contact information or to ask questions about the data you have submitted through the website or portal experience.",
      "Where legally required or operationally appropriate, we will review requests for access, correction, or deletion.",
    ],
  },
  {
    title: "Contact",
    body: [
      "For privacy-related questions, please contact Rockmould Limited through the contact page, your assigned account manager, or the primary company support channels listed on the website.",
    ],
  },
];

export default function PrivacyPolicyPage() {
  return (
    <PublicLayout>
      <HeroSection
        title="Privacy Policy"
        subtitle="Legal"
        description="How Rockmould Limited handles information submitted through the website, client portal, and related digital touchpoints."
        height="medium"
      />

      <section className="section-padding">
        <div className="container-luxury max-w-4xl">
          <p className="text-sm uppercase tracking-[0.18em] text-muted-foreground mb-10">
            Last updated April 9, 2026
          </p>

          <div className="space-y-10">
            {sections.map((section) => (
              <div key={section.title} className="luxury-card">
                <h2 className="font-display text-2xl font-semibold mb-4">{section.title}</h2>
                <div className="space-y-4 text-muted-foreground leading-relaxed">
                  {section.body.map((paragraph) => (
                    <p key={paragraph}>{paragraph}</p>
                  ))}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
