import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";

const sections = [
  {
    title: "Website Use",
    body: [
      "This website is provided for information, inquiry submission, and client-service access related to Rockmould Limited's business activities.",
      "You agree to use the site and portal interfaces lawfully and not in any way that could interfere with service availability, security, or other users' access.",
    ],
  },
  {
    title: "Project and Service Information",
    body: [
      "Project content, illustrations, specifications, dates, availability, and service descriptions shown on the frontend are informational and may be refined as projects evolve.",
      "Nothing on the website should be treated as a binding offer, investment guarantee, or final legal commitment unless confirmed in formal documentation issued by Rockmould Limited.",
    ],
  },
  {
    title: "Portal Access",
    body: [
      "Client portal access is intended only for recognized and approved users. Access may be restricted, reviewed, suspended, or changed as part of account administration, compliance review, or operational updates.",
      "Users are responsible for ensuring that submitted identity and contact information is accurate and current.",
    ],
  },
  {
    title: "Intellectual Property",
    body: [
      "Website content, branding, layouts, copy, images, and interface elements are the property of Rockmould Limited or its licensors unless otherwise stated.",
      "You may not reproduce, republish, or distribute protected material from the site without appropriate permission.",
    ],
  },
  {
    title: "Acceptable Use",
    body: [
      "You must not attempt to misuse forms, upload malicious files, gain unauthorized access, scrape protected information, or disrupt the operation of the website, portal, or admin-connected systems.",
      "We may restrict access or preserve records where misuse, abuse, or security concerns are detected.",
    ],
  },
  {
    title: "Liability and Changes",
    body: [
      "Rockmould Limited aims to keep the frontend accurate and available, but does not guarantee uninterrupted access or that all informational content is free from omission or delay.",
      "We may update these terms, frontend content, and service pathways from time to time. Continued use of the site after updates means you accept the revised terms.",
    ],
  },
  {
    title: "Contact",
    body: [
      "Questions about these terms should be directed to Rockmould Limited through the website contact channels or your assigned relationship contact where applicable.",
    ],
  },
];

export default function TermsOfServicePage() {
  return (
    <PublicLayout>
      <HeroSection
        title="Terms of Service"
        subtitle="Legal"
        description="Core terms that govern the use of Rockmould Limited's website, portal access flow, and digital information services."
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
