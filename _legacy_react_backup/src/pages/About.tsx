import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import {
  ArrowRight,
  Award,
  Eye,
  Heart,
  Shield,
  Target,
  Users,
} from "lucide-react";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { MetricCard } from "@/components/ui/MetricCard";
import { SectionHeading } from "@/components/ui/SectionHeading";
import aboutHero from "@/assets/about-hero.jpg";
import project4 from "@/assets/project-4.jpg";

const values = [
  {
    icon: Shield,
    title: "Integrity",
    desc: "Transparent dealings and honest partnerships built on trust.",
  },
  {
    icon: Award,
    title: "Excellence",
    desc: "Uncompromising quality in every project we undertake.",
  },
  {
    icon: Heart,
    title: "Commitment",
    desc: "Dedicated to delivering beyond expectations, every time.",
  },
  {
    icon: Users,
    title: "Partnership",
    desc: "Building lasting relationships with clients, investors, and communities.",
  },
];

const timeline = [
  {
    year: "2004",
    title: "Company Founded",
    desc: "Rockmould Limited established with a vision for premium construction.",
  },
  {
    year: "2009",
    title: "First Major Development",
    desc: "Completed our first landmark residential project in Lagos.",
  },
  {
    year: "2014",
    title: "Expansion",
    desc: "Expanded into commercial real estate and mixed-use developments.",
  },
  {
    year: "2018",
    title: "100th Project",
    desc: "Reached milestone of 100 delivered projects across Nigeria.",
  },
  {
    year: "2022",
    title: "Premium Portfolio",
    desc: "Launched luxury portfolio exceeding NGN 50 billion in value.",
  },
  {
    year: "2026",
    title: "Global Standards",
    desc: "Setting new benchmarks in African real estate excellence.",
  },
];

const leadership = [
  {
    name: "Chief Adeyemi Rockmould",
    role: "Founder & Chairman",
    desc: "Over 25 years of experience in real estate and construction leadership.",
  },
  {
    name: "Engr. Oluwaseun Adebayo",
    role: "Managing Director",
    desc: "Structural engineer with a passion for innovative construction solutions.",
  },
  {
    name: "Arc. Folake Martins",
    role: "Director of Development",
    desc: "Award-winning architect driving Rockmould's design excellence.",
  },
];

export default function AboutPage() {
  return (
    <PublicLayout>
      <HeroSection
        image={aboutHero}
        title="Building Tomorrow's Landmarks, Today"
        subtitle="About Rockmould"
        description="Two decades of transforming visions into enduring structures. We build with purpose, precision, and an unwavering commitment to excellence."
        height="large"
      />

      <section className="section-padding">
        <div className="container-luxury">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <motion.div
              initial={{ opacity: 0, x: -20 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
            >
              <SectionHeading label="Our Story" title="From Vision to Legacy" align="left" />
              <div className="space-y-4 text-muted-foreground leading-relaxed">
                <p>
                  Founded in 2004, Rockmould Limited began with a singular vision: to redefine
                  the standards of real estate development and construction in Nigeria. What
                  started as a dedicated construction firm has grown into a comprehensive real
                  estate group handling everything from luxury residences to landmark commercial
                  properties.
                </p>
                <p>
                  Today, Rockmould stands as a symbol of quality, trust, and innovation in the
                  Nigerian property landscape. Our portfolio spans over 150 projects, serving
                  discerning clients who demand nothing less than excellence.
                </p>
              </div>
            </motion.div>
            <motion.div
              initial={{ opacity: 0, x: 20 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              className="img-zoom rounded-md overflow-hidden"
            >
              <img
                src={project4}
                alt="Rockmould excellence"
                loading="lazy"
                className="w-full aspect-[4/3] object-cover"
              />
            </motion.div>
          </div>
        </div>
      </section>

      <section className="section-padding bg-card">
        <div className="container-luxury">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              className="luxury-card"
            >
              <Target className="w-8 h-8 text-primary mb-5" />
              <h3 className="font-display text-2xl font-semibold mb-4">Our Vision</h3>
              <p className="text-muted-foreground leading-relaxed">
                To be Africa's most trusted and admired real estate and construction company,
                known for delivering world-class developments that stand the test of time.
              </p>
            </motion.div>
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: 0.1 }}
              className="luxury-card"
            >
              <Eye className="w-8 h-8 text-primary mb-5" />
              <h3 className="font-display text-2xl font-semibold mb-4">Our Mission</h3>
              <p className="text-muted-foreground leading-relaxed">
                To create exceptional living and working spaces through innovative design,
                superior craftsmanship, and an unwavering commitment to client satisfaction and
                community development.
              </p>
            </motion.div>
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury">
          <SectionHeading label="Core Values" title="The Pillars of Our Identity" />
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {values.map((value, index) => (
              <motion.div
                key={value.title}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.1 }}
                className="luxury-card text-center"
              >
                <value.icon className="w-8 h-8 text-primary mx-auto mb-4" />
                <h3 className="font-display text-lg font-semibold mb-2">{value.title}</h3>
                <p className="text-sm text-muted-foreground">{value.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding bg-card">
        <div className="container-luxury">
          <SectionHeading label="Leadership" title="The Team Behind the Vision" />
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {leadership.map((member, index) => (
              <motion.div
                key={member.name}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.1 }}
                className="luxury-card text-center"
              >
                <div className="w-24 h-24 rounded-full bg-muted mx-auto mb-6" />
                <h3 className="font-display text-lg font-semibold">{member.name}</h3>
                <p className="text-xs uppercase tracking-[0.15em] text-primary mb-3">
                  {member.role}
                </p>
                <p className="text-sm text-muted-foreground">{member.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury">
          <SectionHeading label="Our Journey" title="Milestones of Excellence" />
          <div className="max-w-3xl mx-auto space-y-0">
            {timeline.map((item, index) => (
              <motion.div
                key={item.year}
                initial={{ opacity: 0, x: index % 2 === 0 ? -20 : 20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                className="flex gap-6 pb-10 relative"
              >
                <div className="flex flex-col items-center">
                  <div className="w-3 h-3 rounded-full bg-primary flex-shrink-0" />
                  {index < timeline.length - 1 && <div className="w-px flex-1 bg-border" />}
                </div>
                <div className="-mt-1">
                  <span className="text-xs text-primary font-medium uppercase tracking-wider">
                    {item.year}
                  </span>
                  <h3 className="font-display text-lg font-semibold mt-1">{item.title}</h3>
                  <p className="text-sm text-muted-foreground mt-1">{item.desc}</p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="py-20 bg-card border-y border-border">
        <div className="container-luxury">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <MetricCard value="150" suffix="+" label="Projects" index={0} />
            <MetricCard value="20" suffix="+" label="Years" index={1} />
            <MetricCard value="500" suffix="+" label="Clients" index={2} />
            <MetricCard value="350" suffix="+" label="Professionals" index={3} />
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury text-center">
          <h2 className="font-display text-3xl md:text-4xl font-semibold mb-4">
            Partner with Excellence
          </h2>
          <p className="text-muted-foreground max-w-xl mx-auto mb-10">
            Whether you're an investor, client, or partner, let's create something extraordinary
            together.
          </p>
          <div className="flex flex-wrap gap-4 justify-center">
            <Link to="/contact" className="luxury-btn-primary">
              Schedule Consultation <ArrowRight className="w-4 h-4" />
            </Link>
            <Link to="/projects" className="luxury-btn-outline">
              View Projects
            </Link>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
