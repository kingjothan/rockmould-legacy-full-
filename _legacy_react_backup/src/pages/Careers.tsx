import { useState } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { MapPin, Briefcase, Clock, ArrowRight, Users, Heart, Award, ChevronDown, ChevronUp } from "lucide-react";

const jobs = [
  { id: "1", title: "Senior Project Manager", department: "Construction", location: "Lagos", type: "Full-time", desc: "Lead premium construction projects from planning through delivery. 10+ years experience required." },
  { id: "2", title: "Architect", department: "Design", location: "Lagos", type: "Full-time", desc: "Design luxury residential and commercial developments. Must have portfolio of premium projects." },
  { id: "3", title: "Sales Executive", department: "Sales", location: "Lagos", type: "Full-time", desc: "Drive sales for premium real estate developments. Strong client relationship skills required." },
  { id: "4", title: "Civil Engineer", department: "Construction", location: "Lagos", type: "Full-time", desc: "Oversee structural and civil works on premium construction sites." },
  { id: "5", title: "Marketing Manager", department: "Marketing", location: "Lagos", type: "Full-time", desc: "Develop and execute marketing strategies for luxury property developments." },
];

const benefits = [
  { icon: Award, title: "Growth & Development", desc: "Continuous learning opportunities and career advancement." },
  { icon: Heart, title: "Health & Wellbeing", desc: "Comprehensive health insurance and wellness programs." },
  { icon: Users, title: "Collaborative Culture", desc: "Work with industry-leading professionals on landmark projects." },
  { icon: Briefcase, title: "Competitive Package", desc: "Premium compensation packages that reward excellence." },
];

export default function CareersPage() {
  const [expandedJob, setExpandedJob] = useState<string | null>(null);

  return (
    <PublicLayout>
      <HeroSection title="Build Your Career with Rockmould" subtitle="Careers" description="Join a team of professionals dedicated to delivering excellence in real estate and construction." height="medium" />

      {/* Culture */}
      <section className="section-padding">
        <div className="container-luxury">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
            {benefits.map((b, i) => (
              <motion.div key={b.title} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="luxury-card text-center">
                <b.icon className="w-8 h-8 text-primary mx-auto mb-4" />
                <h3 className="font-display text-lg font-semibold mb-2">{b.title}</h3>
                <p className="text-sm text-muted-foreground">{b.desc}</p>
              </motion.div>
            ))}
          </div>

          {/* Open Positions */}
          <h2 className="font-display text-3xl font-semibold mb-8 text-center">Open Positions</h2>
          <div className="max-w-3xl mx-auto space-y-3">
            {jobs.map((job) => (
              <motion.div key={job.id} initial={{ opacity: 0 }} whileInView={{ opacity: 1 }} viewport={{ once: true }} className="luxury-card">
                <button onClick={() => setExpandedJob(expandedJob === job.id ? null : job.id)} className="w-full flex items-center justify-between text-left">
                  <div>
                    <h3 className="font-semibold">{job.title}</h3>
                    <div className="flex items-center gap-4 mt-1 text-xs text-muted-foreground">
                      <span className="flex items-center gap-1"><Briefcase className="w-3 h-3" />{job.department}</span>
                      <span className="flex items-center gap-1"><MapPin className="w-3 h-3" />{job.location}</span>
                      <span className="flex items-center gap-1"><Clock className="w-3 h-3" />{job.type}</span>
                    </div>
                  </div>
                  {expandedJob === job.id ? <ChevronUp className="w-4 h-4 text-primary" /> : <ChevronDown className="w-4 h-4 text-muted-foreground" />}
                </button>
                {expandedJob === job.id && (
                  <motion.div initial={{ height: 0, opacity: 0 }} animate={{ height: "auto", opacity: 1 }} className="mt-4 pt-4 border-t border-border">
                    <p className="text-sm text-muted-foreground mb-4">{job.desc}</p>
                    <Link to="/contact" className="luxury-btn-primary text-xs">Apply Now <ArrowRight className="w-4 h-4" /></Link>
                  </motion.div>
                )}
              </motion.div>
            ))}
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
