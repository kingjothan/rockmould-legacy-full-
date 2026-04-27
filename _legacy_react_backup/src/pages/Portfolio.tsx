import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { MetricCard } from "@/components/ui/MetricCard";
import project1 from "@/assets/project-1.jpg";
import project2 from "@/assets/project-2.jpg";
import project3 from "@/assets/project-3.jpg";
import project4 from "@/assets/project-4.jpg";

const cases = [
  {
    title: "Azure Towers Residential Complex",
    category: "Luxury Residential",
    location: "Victoria Island",
    impact: "120 premium units delivered",
    image: project1,
  },
  {
    title: "Meridian Plaza Commercial Hub",
    category: "Commercial",
    location: "Ikoyi",
    impact: "50,000 sqm office space",
    image: project2,
  },
  {
    title: "Crown Heights Mixed-Use",
    category: "Mixed-Use Development",
    location: "Lekki",
    impact: "200+ units and retail spaces",
    image: project3,
  },
  {
    title: "The Penthouse Collection",
    category: "Ultra Luxury",
    location: "Banana Island",
    impact: "12 bespoke penthouses",
    image: project4,
  },
];

export default function PortfolioPage() {
  return (
    <PublicLayout>
      <HeroSection
        title="Our Portfolio"
        subtitle="Proven Excellence"
        description="A showcase of our finest developments, construction achievements, and the impact we've made across Nigeria's property landscape."
        height="medium"
      />

      <section className="section-padding">
        <div className="container-luxury space-y-16">
          {cases.map((item, index) => (
            <motion.div
              key={item.title}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center"
            >
              <div className={index % 2 === 1 ? "lg:order-2" : ""}>
                <div className="img-zoom rounded-md overflow-hidden aspect-[16/10]">
                  <img
                    src={item.image}
                    alt={item.title}
                    loading="lazy"
                    className="w-full h-full object-cover"
                  />
                </div>
              </div>
              <div className={index % 2 === 1 ? "lg:order-1" : ""}>
                <span className="text-xs uppercase tracking-[0.2em] text-primary mb-3 block">
                  {item.category}
                </span>
                <h3 className="font-display text-3xl font-semibold mb-3">{item.title}</h3>
                <p className="text-muted-foreground mb-2">{item.location}</p>
                <p className="text-sm text-primary font-medium">{item.impact}</p>
              </div>
            </motion.div>
          ))}
        </div>
      </section>

      <section className="py-20 bg-card border-y border-border">
        <div className="container-luxury">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <MetricCard value="150" suffix="+" label="Projects" index={0} />
            <MetricCard value="NGN 50B" label="Portfolio Value" index={1} />
            <MetricCard value="98" suffix="%" label="On-Time Delivery" index={2} />
            <MetricCard value="500" suffix="+" label="Happy Clients" index={3} />
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
