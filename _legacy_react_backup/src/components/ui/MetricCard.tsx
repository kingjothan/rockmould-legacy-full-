import { motion } from "framer-motion";
import { LucideIcon } from "lucide-react";

interface MetricCardProps {
  icon?: LucideIcon;
  value: string;
  label: string;
  suffix?: string;
  index?: number;
}

export function MetricCard({ icon: Icon, value, label, suffix, index = 0 }: MetricCardProps) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5, delay: index * 0.1 }}
      className="text-center p-6"
    >
      {Icon && <Icon className="w-6 h-6 text-primary mx-auto mb-4" />}
      <div className="font-display text-4xl md:text-5xl font-bold text-foreground mb-2">
        {value}<span className="text-primary">{suffix}</span>
      </div>
      <p className="text-sm text-muted-foreground uppercase tracking-[0.15em]">{label}</p>
    </motion.div>
  );
}
