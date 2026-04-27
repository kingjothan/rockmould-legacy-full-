import { useState } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { Mail, Phone, MapPin, Clock, ArrowRight, MessageSquare } from "lucide-react";

export default function ContactPage() {
  const [submitted, setSubmitted] = useState(false);

  return (
    <PublicLayout>
      <HeroSection
        title="Get in Touch"
        subtitle="Contact Us"
        description="Whether you're interested in our projects, services, or partnerships, we'd love to hear from you."
        height="medium"
      />

      <section className="section-padding">
        <div className="container-luxury">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div className="space-y-8">
              <div>
                <h3 className="font-display text-xl font-semibold mb-6">Contact Information</h3>
                <div className="space-y-5">
                  {[
                    { icon: Phone, label: "Phone", value: "+234 123 456 7890", href: "tel:+2341234567890" },
                    { icon: Mail, label: "Email", value: "info@rockmould.com", href: "mailto:info@rockmould.com" },
                    { icon: MapPin, label: "Office", value: "12 Admiralty Way, Victoria Island, Lagos, Nigeria" },
                    { icon: Clock, label: "Hours", value: "Mon - Fri: 9:00 AM - 6:00 PM" },
                  ].map((item) => (
                    <div key={item.label} className="flex items-start gap-4">
                      <div className="w-10 h-10 rounded-md bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <item.icon className="w-4 h-4 text-primary" />
                      </div>
                      <div>
                        <p className="text-xs uppercase tracking-wider text-muted-foreground mb-0.5">
                          {item.label}
                        </p>
                        {item.href ? (
                          <a href={item.href} className="text-sm text-foreground hover:text-primary transition-colors">
                            {item.value}
                          </a>
                        ) : (
                          <p className="text-sm text-foreground">{item.value}</p>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              <div className="luxury-card">
                <MessageSquare className="w-6 h-6 text-primary mb-3" />
                <h4 className="font-display text-lg font-semibold mb-2">Quick Access</h4>
                <div className="space-y-2 text-sm">
                  <Link to="/portal" className="block text-muted-foreground hover:text-primary transition-colors">
                    Client Portal
                  </Link>
                  <Link to="/projects" className="block text-muted-foreground hover:text-primary transition-colors">
                    View Projects
                  </Link>
                  <Link to="/services" className="block text-muted-foreground hover:text-primary transition-colors">
                    Our Services
                  </Link>
                </div>
              </div>
            </div>

            <div className="lg:col-span-2">
              <div className="luxury-card">
                <h3 className="font-display text-xl font-semibold mb-6">Send Us a Message</h3>
                {submitted ? (
                  <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="text-center py-12">
                    <div className="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                      <Mail className="w-7 h-7 text-primary" />
                    </div>
                    <h4 className="font-display text-xl font-semibold mb-2">Message Sent</h4>
                    <p className="text-muted-foreground">
                      Thank you for reaching out. We'll get back to you within 24 hours.
                    </p>
                  </motion.div>
                ) : (
                  <form
                    onSubmit={(e) => {
                      e.preventDefault();
                      setSubmitted(true);
                    }}
                    className="space-y-6"
                  >
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <label className="text-xs uppercase tracking-wider text-muted-foreground mb-2 block">
                          Full Name *
                        </label>
                        <input
                          required
                          className="w-full bg-muted border border-border rounded-sm px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors"
                          placeholder="John Doe"
                        />
                      </div>
                      <div>
                        <label className="text-xs uppercase tracking-wider text-muted-foreground mb-2 block">
                          Email Address *
                        </label>
                        <input
                          required
                          type="email"
                          className="w-full bg-muted border border-border rounded-sm px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors"
                          placeholder="john@example.com"
                        />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <label className="text-xs uppercase tracking-wider text-muted-foreground mb-2 block">
                          Phone Number
                        </label>
                        <input
                          className="w-full bg-muted border border-border rounded-sm px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors"
                          placeholder="+234..."
                        />
                      </div>
                      <div>
                        <label className="text-xs uppercase tracking-wider text-muted-foreground mb-2 block">
                          Department
                        </label>
                        <select className="w-full bg-muted border border-border rounded-sm px-4 py-3 text-sm text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors">
                          <option>General Inquiry</option>
                          <option>Sales and Projects</option>
                          <option>Partnerships and Investment</option>
                          <option>Construction Services</option>
                          <option>Client Support</option>
                        </select>
                      </div>
                    </div>
                    <div>
                      <label className="text-xs uppercase tracking-wider text-muted-foreground mb-2 block">
                        Message *
                      </label>
                      <textarea
                        required
                        rows={5}
                        className="w-full bg-muted border border-border rounded-sm px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors resize-none"
                        placeholder="Tell us about your inquiry..."
                      />
                    </div>
                    <button type="submit" className="luxury-btn-primary">
                      Send Message <ArrowRight className="w-4 h-4" />
                    </button>
                  </form>
                )}
              </div>
            </div>
          </div>

          <div className="mt-16 luxury-card p-0 overflow-hidden">
            <div className="aspect-[21/9] bg-muted flex items-center justify-center">
              <div className="text-center">
                <MapPin className="w-8 h-8 text-muted-foreground mx-auto mb-2" />
                <p className="text-sm text-muted-foreground">
                  Map integration available upon backend connection
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
