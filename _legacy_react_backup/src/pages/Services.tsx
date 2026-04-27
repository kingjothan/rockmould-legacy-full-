import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import {
  ArrowRight,
  Briefcase,
  Building2,
  CheckCircle,
  ClipboardList,
  Hammer,
  Home,
  LineChart,
} from "lucide-react";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import aboutHero from "@/assets/about-hero.jpg";

const services = [
  {
    id: "real-estate",
    icon: Building2,
    title: "Real Estate Development",
    desc: "We conceive, plan, and deliver premium residential and commercial developments that define skylines and communities. From luxury apartments to landmark estates, our developments are designed for enduring value.",
    benefits: [
      "Prime location selection",
      "World-class architectural design",
      "Premium material sourcing",
      "End-to-end project management",
    ],
  },
  {
    id: "construction",
    icon: Hammer,
    title: "Construction & Project Execution",
    desc: "Our construction expertise spans structural, civil, and finishing works. We deploy cutting-edge engineering and rigorous quality control to deliver structures that exceed international standards.",
    benefits: [
      "Structural engineering excellence",
      "Quality assurance at every stage",
      "Safety-first construction protocols",
      "On-schedule delivery guarantee",
    ],
  },
  {
    id: "advisory",
    icon: Briefcase,
    title: "Property Advisory",
    desc: "Our advisory team provides strategic property consulting for investors, developers, and corporate entities. We deliver market intelligence and investment frameworks that drive informed decisions.",
    benefits: [
      "Market analysis and insights",
      "Investment strategy development",
      "Portfolio optimization",
      "Risk assessment and mitigation",
    ],
  },
  {
    id: "investment",
    icon: LineChart,
    title: "Investment Support",
    desc: "We guide high-net-worth individuals and institutional investors through curated real estate opportunities. Our track record of returns and transparent governance builds lasting investor confidence.",
    benefits: [
      "Curated investment opportunities",
      "Transparent reporting",
      "Capital growth strategies",
      "Due diligence support",
    ],
  },
  {
    id: "management",
    icon: ClipboardList,
    title: "Project Management",
    desc: "From conceptualization to handover, our project management teams ensure every milestone is met with precision. We coordinate architects, engineers, contractors, and suppliers seamlessly.",
    benefits: [
      "Comprehensive scheduling",
      "Budget control and optimization",
      "Stakeholder coordination",
      "Progress reporting and transparency",
    ],
  },
  {
    id: "luxury",
    icon: Home,
    title: "Luxury Property Delivery",
    desc: "We specialize in delivering bespoke luxury properties that reflect the aspirations of discerning clients. Every detail, from spatial design to finishing touches, is curated for perfection.",
    benefits: [
      "Bespoke interior design",
      "Premium finishing standards",
      "Smart home integration",
      "Personalized client experience",
    ],
  },
];

export default function ServicesPage() {
  return (
    <PublicLayout>
      <HeroSection
        image={aboutHero}
        title="Comprehensive Solutions for Premium Properties"
        subtitle="Our Services"
        description="From vision to delivery, Rockmould offers a complete suite of real estate and construction services engineered for excellence."
      />

      <section className="section-padding">
        <div className="container-luxury space-y-24">
          {services.map((service, index) => (
            <motion.div
              key={service.id}
              id={service.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              className={`grid grid-cols-1 lg:grid-cols-2 gap-16 items-start ${
                index % 2 === 1 ? "lg:flex-row-reverse" : ""
              }`}
            >
              <div className={index % 2 === 1 ? "lg:order-2" : ""}>
                <div className="flex items-center gap-4 mb-6">
                  <div className="w-12 h-12 rounded-md bg-primary/10 flex items-center justify-center">
                    <service.icon className="w-6 h-6 text-primary" />
                  </div>
                  <span className="text-xs uppercase tracking-[0.2em] text-primary font-medium">
                    Service
                  </span>
                </div>
                <h2 className="font-display text-3xl md:text-4xl font-semibold mb-4">
                  {service.title}
                </h2>
                <p className="text-muted-foreground leading-relaxed mb-8">{service.desc}</p>
                <div className="space-y-3 mb-8">
                  {service.benefits.map((benefit) => (
                    <div key={benefit} className="flex items-center gap-3">
                      <CheckCircle className="w-4 h-4 text-primary flex-shrink-0" />
                      <span className="text-sm text-muted-foreground">{benefit}</span>
                    </div>
                  ))}
                </div>
                <Link to="/contact" className="luxury-btn-primary text-xs">
                  Inquire About This Service <ArrowRight className="w-4 h-4" />
                </Link>
              </div>
              <div
                className={`aspect-[4/3] bg-muted rounded-md ${
                  index % 2 === 1 ? "lg:order-1" : ""
                }`}
              />
            </motion.div>
          ))}
        </div>
      </section>

      <section className="section-padding bg-card">
        <div className="container-luxury text-center">
          <h2 className="font-display text-3xl md:text-4xl font-semibold mb-4">
            Need a Tailored Solution?
          </h2>
          <p className="text-muted-foreground max-w-xl mx-auto mb-10">
            Every project is unique. Let us understand your needs and craft the perfect approach.
          </p>
          <Link to="/contact" className="luxury-btn-primary">
            Book a Consultation <ArrowRight className="w-4 h-4" />
          </Link>
        </div>
      </section>
    </PublicLayout>
  );
}
