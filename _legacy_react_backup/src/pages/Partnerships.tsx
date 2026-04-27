import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import {
  ArrowRight,
  CheckCircle,
  Handshake,
  Shield,
  TrendingUp,
} from "lucide-react";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { MetricCard } from "@/components/ui/MetricCard";
import { SectionHeading } from "@/components/ui/SectionHeading";

export default function PartnershipsPage() {
  return (
    <PublicLayout>
      <HeroSection
        title="Invest & Partner with Confidence"
        subtitle="Partnerships"
        description="Join forces with Rockmould for premium real estate investment opportunities and strategic partnerships."
        height="medium"
      />

      <section className="section-padding">
        <div className="container-luxury">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <div>
              <SectionHeading
                label="Why Invest"
                title="Unparalleled Real Estate Returns"
                align="left"
              />
              <p className="text-muted-foreground leading-relaxed mb-8">
                Rockmould offers curated investment opportunities in premium real estate with a
                proven track record of delivering strong returns. Our transparent governance,
                rigorous due diligence, and market expertise make us the partner of choice for
                serious investors.
              </p>
              <div className="space-y-4">
                {[
                  "Consistent above-market returns",
                  "Transparent reporting and governance",
                  "Diversified portfolio across premium segments",
                  "Dedicated investor relations team",
                  "Priority access to off-plan opportunities",
                ].map((item) => (
                  <div key={item} className="flex items-center gap-3">
                    <CheckCircle className="w-4 h-4 text-primary flex-shrink-0" />
                    <span className="text-sm text-muted-foreground">{item}</span>
                  </div>
                ))}
              </div>
            </div>
            <div className="aspect-square bg-muted rounded-md" />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            {[
              {
                icon: Shield,
                title: "Trust & Governance",
                desc: "Regulatory compliance, transparent operations, and institutional-grade governance.",
              },
              {
                icon: TrendingUp,
                title: "Growth Potential",
                desc: "Premium locations and strategic developments that appreciate in value.",
              },
              {
                icon: Handshake,
                title: "Partnership Model",
                desc: "Flexible structures for individual, institutional, and corporate investors.",
              },
            ].map((item, index) => (
              <motion.div
                key={item.title}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.1 }}
                className="luxury-card text-center"
              >
                <item.icon className="w-8 h-8 text-primary mx-auto mb-4" />
                <h3 className="font-display text-lg font-semibold mb-2">{item.title}</h3>
                <p className="text-sm text-muted-foreground">{item.desc}</p>
              </motion.div>
            ))}
          </div>

          <div className="py-16 bg-card rounded-md border border-border">
            <div className="grid grid-cols-2 md:grid-cols-4 gap-8 px-8">
              <MetricCard value="NGN 50B" label="Portfolio Value" index={0} />
              <MetricCard value="25" suffix="%" label="Avg. ROI" index={1} />
              <MetricCard value="150" suffix="+" label="Projects" index={2} />
              <MetricCard value="100" suffix="%" label="Delivery Rate" index={3} />
            </div>
          </div>

          <div className="text-center mt-20">
            <h2 className="font-display text-3xl font-semibold mb-4">Ready to Partner?</h2>
            <p className="text-muted-foreground max-w-xl mx-auto mb-8">
              Schedule a private consultation to discuss investment opportunities.
            </p>
            <Link to="/contact" className="luxury-btn-primary">
              Request Private Consultation <ArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
