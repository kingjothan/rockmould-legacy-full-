import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import {
  ArrowRight,
  Award,
  Briefcase,
  Building2,
  CheckCircle,
  ChevronRight,
  Clock,
  Shield,
  Star,
} from "lucide-react";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { MetricCard } from "@/components/ui/MetricCard";
import { ProjectCard } from "@/components/ui/ProjectCard";
import { SectionHeading } from "@/components/ui/SectionHeading";
import heroImage from "@/assets/hero-main.jpg";
import project4 from "@/assets/project-4.jpg";
import { previewArticles } from "@/lib/articles";
import { featuredProjects } from "@/lib/projects";

const services = [
  {
    icon: Building2,
    title: "Real Estate Development",
    desc: "Premium residential and commercial developments that set new standards.",
  },
  {
    icon: Shield,
    title: "Construction Excellence",
    desc: "World-class construction management from foundation to finishing.",
  },
  {
    icon: Briefcase,
    title: "Property Advisory",
    desc: "Strategic property consulting for investors and corporate clients.",
  },
  {
    icon: Award,
    title: "Investment Support",
    desc: "Guided investment pathways into high-value real estate opportunities.",
  },
];

const processSteps = [
  { num: "01", title: "Discovery", desc: "Understanding your vision, objectives, and requirements." },
  { num: "02", title: "Planning", desc: "Detailed architectural and engineering planning." },
  { num: "03", title: "Development", desc: "Precision construction with quality at every stage." },
  { num: "04", title: "Delivery", desc: "Handover of a finished masterpiece, on time." },
];

const testimonials = [
  {
    name: "Adebayo O.",
    role: "Property Investor",
    quote: "Rockmould transformed our vision into reality. The attention to detail and professionalism is unmatched.",
  },
  {
    name: "Funke A.",
    role: "Corporate Client",
    quote: "Working with Rockmould was seamless. Their commitment to quality and timelines gave us complete confidence.",
  },
];

export default function HomePage() {
  return (
    <PublicLayout>
      <section className="relative min-h-screen flex items-center overflow-hidden">
        <img
          src={heroImage}
          alt="Rockmould luxury development"
          className="absolute inset-0 w-full h-full object-cover"
          width={1920}
          height={1080}
        />
        <div className="absolute inset-0 bg-background/60" />
        <div className="absolute inset-0 bg-gradient-to-t from-background via-background/30 to-transparent" />
        <div className="container-luxury relative z-10 py-32">
          <motion.div
            initial={{ opacity: 0, y: 40 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 1, ease: [0.25, 0.46, 0.45, 0.94] }}
            className="max-w-4xl"
          >
            <span className="text-xs uppercase tracking-[0.3em] text-primary mb-6 block font-medium">
              Premium Real Estate & Construction
            </span>
            <h1 className="font-display text-5xl md:text-6xl lg:text-8xl font-bold leading-[1.05] mb-8">
              Building <span className="text-gold-gradient">Legacies</span> That Endure
            </h1>
            <p className="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-2xl mb-10">
              Rockmould Limited delivers world-class real estate developments and construction
              projects. Every structure we build is a testament to excellence, precision, and
              lasting value.
            </p>
            <div className="flex flex-wrap gap-4">
              <Link to="/projects" className="luxury-btn-primary">
                Explore Projects <ArrowRight className="w-4 h-4" />
              </Link>
              <Link to="/contact" className="luxury-btn-outline">
                Book Consultation
              </Link>
              <Link to="/portal" className="luxury-btn-ghost text-primary">
                Client Portal <ChevronRight className="w-4 h-4" />
              </Link>
            </div>
          </motion.div>
        </div>
      </section>

      <section className="section-padding bg-background">
        <div className="container-luxury">
          <SectionHeading
            label="Featured Developments"
            title="Exceptional Projects, Extraordinary Standards"
            description="From premium residences to landmark commercial spaces, every Rockmould project embodies craftsmanship and vision."
          />
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {featuredProjects.map((project, index) => (
              <ProjectCard key={project.id} {...project} index={index} />
            ))}
          </div>
          <div className="text-center mt-12">
            <Link to="/projects" className="luxury-btn-outline">
              View All Projects <ArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </section>

      <section className="section-padding bg-card">
        <div className="container-luxury">
          <SectionHeading
            label="Our Services"
            title="Comprehensive Real Estate & Construction Solutions"
            description="End-to-end expertise across every facet of property development."
          />
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {services.map((service, index) => (
              <motion.div
                key={service.title}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.1 }}
                className="luxury-card group"
              >
                <service.icon className="w-8 h-8 text-primary mb-5 group-hover:scale-110 transition-transform duration-500" />
                <h3 className="font-display text-lg font-semibold mb-3">{service.title}</h3>
                <p className="text-sm text-muted-foreground leading-relaxed">{service.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <motion.div
              initial={{ opacity: 0, x: -20 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
            >
              <SectionHeading
                label="Why Rockmould"
                title="A Legacy of Excellence in Every Foundation"
                align="left"
              />
              <div className="space-y-6">
                {[
                  "Uncompromising quality and precision craftsmanship",
                  "On-time project delivery with transparent communication",
                  "Premium materials and world-class engineering standards",
                  "Dedicated client relationship management",
                  "Comprehensive post-delivery support",
                ].map((item, index) => (
                  <div key={index} className="flex items-start gap-3">
                    <CheckCircle className="w-5 h-5 text-primary mt-0.5 flex-shrink-0" />
                    <p className="text-muted-foreground">{item}</p>
                  </div>
                ))}
              </div>
              <Link to="/about" className="luxury-btn-primary mt-10 inline-flex">
                Learn More <ArrowRight className="w-4 h-4" />
              </Link>
            </motion.div>
            <motion.div
              initial={{ opacity: 0, x: 20 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              className="img-zoom rounded-md overflow-hidden"
            >
              <img
                src={project4}
                alt="Luxury interior"
                loading="lazy"
                width={800}
                height={600}
                className="w-full h-full object-cover aspect-[4/3]"
              />
            </motion.div>
          </div>
        </div>
      </section>

      <section className="py-20 bg-card border-y border-border">
        <div className="container-luxury">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <MetricCard value="150" suffix="+" label="Projects Delivered" index={0} />
            <MetricCard value="20" suffix="+" label="Years of Excellence" index={1} />
            <MetricCard value="500" suffix="+" label="Happy Clients" index={2} />
            <MetricCard value="NGN 50B" suffix="" label="Portfolio Value" index={3} />
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury">
          <SectionHeading
            label="Our Process"
            title="From Vision to Reality"
            description="A refined process that ensures every project is delivered with precision and excellence."
          />
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {processSteps.map((step, index) => (
              <motion.div
                key={step.num}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.15 }}
                className="relative"
              >
                <span className="font-display text-6xl font-bold text-primary/10">
                  {step.num}
                </span>
                <h3 className="font-display text-xl font-semibold -mt-4 mb-3">{step.title}</h3>
                <p className="text-sm text-muted-foreground leading-relaxed">{step.desc}</p>
                {index < 3 && (
                  <div className="hidden lg:block absolute top-8 -right-4 w-8 h-px bg-border" />
                )}
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding bg-card">
        <div className="container-luxury">
          <SectionHeading label="Client Stories" title="Trusted by Visionary Clients" />
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            {testimonials.map((testimonial, index) => (
              <motion.div
                key={index}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.15 }}
                className="luxury-card"
              >
                <div className="flex gap-1 mb-4">
                  {[...Array(5)].map((_, starIndex) => (
                    <Star key={starIndex} className="w-4 h-4 text-primary fill-primary" />
                  ))}
                </div>
                <p className="text-muted-foreground leading-relaxed mb-6 italic">
                  "{testimonial.quote}"
                </p>
                <div>
                  <p className="font-semibold text-sm">{testimonial.name}</p>
                  <p className="text-xs text-muted-foreground">{testimonial.role}</p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury">
          <SectionHeading
            label="Insights"
            title="Latest from Rockmould"
            description="Market insights, project updates, and thought leadership from our team."
          />
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {previewArticles.map((post, index) => (
              <motion.div
                key={index}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.1 }}
              >
                <Link
                  to={`/blog/${post.id}`}
                  className="group block luxury-card p-0 overflow-hidden"
                >
                  <div className="aspect-[16/9] bg-muted" />
                  <div className="p-6">
                    <div className="flex items-center gap-3 mb-3">
                      <span className="text-[11px] uppercase tracking-[0.15em] text-primary">
                        {post.category}
                      </span>
                      <span className="text-[11px] text-muted-foreground">{post.date}</span>
                    </div>
                    <h3 className="font-display text-lg font-semibold group-hover:text-primary transition-colors">
                      {post.title}
                    </h3>
                  </div>
                </Link>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="py-20 bg-card border-y border-border">
        <div className="container-luxury text-center">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
          >
            <Clock className="w-8 h-8 text-primary mx-auto mb-4" />
            <h2 className="font-display text-3xl md:text-4xl font-semibold mb-4">
              Client Portal
            </h2>
            <p className="text-muted-foreground max-w-xl mx-auto mb-8">
              Track your projects, view documents, monitor payments, and stay updated in one
              secure, premium portal.
            </p>
            <Link to="/portal" className="luxury-btn-primary">
              Access Portal <ArrowRight className="w-4 h-4" />
            </Link>
          </motion.div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury">
          <div className="luxury-card p-12 md:p-16 text-center bg-gradient-to-br from-card to-secondary">
            <h2 className="font-display text-3xl md:text-4xl lg:text-5xl font-semibold mb-4">
              Ready to Build Your Legacy?
            </h2>
            <p className="text-muted-foreground max-w-xl mx-auto mb-10 text-lg">
              Let's discuss your next premium development or investment opportunity.
            </p>
            <div className="flex flex-wrap gap-4 justify-center">
              <Link to="/contact" className="luxury-btn-primary">
                Schedule Consultation <ArrowRight className="w-4 h-4" />
              </Link>
              <Link to="/projects" className="luxury-btn-outline">
                Browse Projects
              </Link>
            </div>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
