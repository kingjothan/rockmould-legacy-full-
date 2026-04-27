import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { Star, Quote } from "lucide-react";

const testimonials = [
  { name: "Chief Adebayo Ogunleye", role: "Property Investor", quote: "Rockmould transformed our investment vision into a landmark reality. Their professionalism, attention to detail, and commitment to timelines is world-class. I wouldn't trust any other firm with my developments.", project: "Azure Towers" },
  { name: "Mrs. Funke Adeyemi", role: "Corporate Client", quote: "Working with Rockmould was an exceptional experience from start to finish. Their team's dedication to quality and transparency gave us complete confidence throughout the project lifecycle.", project: "Meridian Plaza" },
  { name: "Engr. Tunde Bakare", role: "Development Partner", quote: "As a development partner, I've been impressed by Rockmould's engineering excellence and project management capabilities. They consistently deliver beyond expectations.", project: "Crown Heights" },
  { name: "Dr. Amina Ibrahim", role: "Homeowner", quote: "Our home at The Penthouse Collection is a masterpiece. Rockmould's team understood our vision perfectly and delivered a living space that exceeds everything we imagined.", project: "The Penthouse Collection" },
  { name: "Mr. Chukwuma Eze", role: "Real Estate Fund Manager", quote: "Rockmould represents the gold standard in Nigerian real estate development. Their track record of returns and transparent governance makes them our preferred investment partner.", project: "Multiple Projects" },
  { name: "Arc. Yewande Oshodi", role: "Consulting Architect", quote: "Collaborating with Rockmould is a pleasure. Their construction quality, respect for architectural intent, and commitment to premium finishes sets them apart in the industry.", project: "Various Developments" },
];

export default function TestimonialsPage() {
  return (
    <PublicLayout>
      <HeroSection title="Voices of Trust" subtitle="Testimonials" description="Hear from the clients, investors, and partners who've experienced the Rockmould difference." height="medium" />

      <section className="section-padding">
        <div className="container-luxury">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {testimonials.map((t, i) => (
              <motion.div key={t.name} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.05 }} className="luxury-card">
                <Quote className="w-8 h-8 text-primary/20 mb-4" />
                <p className="text-muted-foreground leading-relaxed mb-6 italic">"{t.quote}"</p>
                <div className="flex items-center gap-1 mb-4">
                  {[...Array(5)].map((_, j) => <Star key={j} className="w-3.5 h-3.5 text-primary fill-primary" />)}
                </div>
                <div className="border-t border-border pt-4">
                  <p className="font-semibold text-sm">{t.name}</p>
                  <p className="text-xs text-muted-foreground">{t.role}</p>
                  <p className="text-xs text-primary mt-1">{t.project}</p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
