import { FormEvent, useState } from "react";
import { Link } from "react-router-dom";
import { Mail, Phone, MapPin } from "lucide-react";
import { toast } from "@/components/ui/sonner";

const footerLinks = [
  {
    title: "Company",
    links: [
      { label: "About Us", path: "/about" },
      { label: "Our Services", path: "/services" },
      { label: "Portfolio", path: "/portfolio" },
      { label: "Careers", path: "/careers" },
      { label: "Contact", path: "/contact" },
    ],
  },
  {
    title: "Projects",
    links: [
      { label: "All Projects", path: "/projects" },
      { label: "Residential", path: "/projects?type=residential" },
      { label: "Commercial", path: "/projects?type=commercial" },
      { label: "Ongoing", path: "/projects?status=ongoing" },
      { label: "Completed", path: "/projects?status=completed" },
    ],
  },
  {
    title: "Resources",
    links: [
      { label: "Insights and Blog", path: "/blog" },
      { label: "FAQ", path: "/faq" },
      { label: "Partnerships", path: "/partnerships" },
      { label: "Testimonials", path: "/testimonials" },
      { label: "Gallery", path: "/gallery" },
    ],
  },
  {
    title: "Portal",
    links: [
      { label: "Client Login", path: "/portal" },
      { label: "My Projects", path: "/portal/projects" },
      { label: "Documents", path: "/portal/documents" },
      { label: "Support", path: "/portal/support" },
    ],
  },
];

export function Footer() {
  const [email, setEmail] = useState("");

  const handleSubscribe = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    if (!email.trim()) {
      toast.error("Enter an email address to subscribe.");
      return;
    }

    toast.success("Thanks for subscribing to Rockmould updates.");
    setEmail("");
  };

  return (
    <footer className="bg-card border-t border-border">
      <div className="container-luxury section-padding">
        <div className="grid grid-cols-1 lg:grid-cols-6 gap-12 lg:gap-8">
          <div className="lg:col-span-2">
            <Link to="/" className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 rounded-sm bg-primary flex items-center justify-center">
                <span className="font-display text-primary-foreground text-lg font-bold">R</span>
              </div>
              <div className="flex flex-col">
                <span className="font-display text-lg font-semibold text-foreground">Rockmould</span>
                <span className="text-[10px] uppercase tracking-[0.25em] text-muted-foreground -mt-0.5">
                  Limited
                </span>
              </div>
            </Link>
            <p className="text-sm text-muted-foreground leading-relaxed mb-8 max-w-sm">
              Building legacies in real estate and construction. Premium developments,
              enduring quality, and unwavering commitment to excellence.
            </p>
            <div className="space-y-3">
              <a
                href="tel:+2341234567890"
                className="flex items-center gap-3 text-sm text-muted-foreground hover:text-primary transition-colors"
              >
                <Phone className="w-4 h-4 text-primary" />
                +234 123 456 7890
              </a>
              <a
                href="mailto:info@rockmould.com"
                className="flex items-center gap-3 text-sm text-muted-foreground hover:text-primary transition-colors"
              >
                <Mail className="w-4 h-4 text-primary" />
                info@rockmould.com
              </a>
              <div className="flex items-start gap-3 text-sm text-muted-foreground">
                <MapPin className="w-4 h-4 text-primary mt-0.5" />
                <span>Victoria Island, Lagos, Nigeria</span>
              </div>
            </div>
          </div>

          {footerLinks.map((group) => (
            <div key={group.title}>
              <h4 className="text-xs uppercase tracking-[0.2em] text-foreground font-medium mb-6">
                {group.title}
              </h4>
              <ul className="space-y-3">
                {group.links.map((link) => (
                  <li key={link.label}>
                    <Link
                      to={link.path}
                      className="text-sm text-muted-foreground hover:text-primary transition-colors duration-300"
                    >
                      {link.label}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        <div className="luxury-divider my-12" />
        <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
          <div>
            <h4 className="font-display text-lg mb-1">Stay Informed</h4>
            <p className="text-sm text-muted-foreground">
              Subscribe for exclusive project updates and market insights.
            </p>
          </div>
          <form className="flex gap-2 w-full md:w-auto" onSubmit={handleSubscribe}>
            <input
              type="email"
              value={email}
              onChange={(event) => setEmail(event.target.value)}
              placeholder="Enter your email"
              className="bg-muted border border-border rounded-sm px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors w-full md:w-72"
            />
            <button type="submit" className="luxury-btn-primary text-xs py-2.5 px-6 whitespace-nowrap">
              Subscribe
            </button>
          </form>
        </div>

        <div className="luxury-divider my-12" />
        <div className="flex flex-col md:flex-row items-center justify-between gap-4">
          <p className="text-xs text-muted-foreground">
            Copyright {new Date().getFullYear()} Rockmould Limited. All rights reserved.
          </p>
          <div className="flex items-center gap-6">
            <Link to="/privacy" className="text-xs text-muted-foreground hover:text-primary transition-colors">
              Privacy Policy
            </Link>
            <Link to="/terms" className="text-xs text-muted-foreground hover:text-primary transition-colors">
              Terms of Service
            </Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
