import { motion } from "framer-motion";

interface SectionHeadingProps {
  label?: string;
  title: string;
  description?: string;
  align?: "left" | "center";
  light?: boolean;
}

export function SectionHeading({ label, title, description, align = "center", light }: SectionHeadingProps) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-50px" }}
      transition={{ duration: 0.6 }}
      className={`mb-16 ${align === "center" ? "text-center max-w-3xl mx-auto" : "max-w-2xl"}`}
    >
      {label && (
        <span className="text-xs uppercase tracking-[0.25em] text-primary font-medium mb-4 block">
          {label}
        </span>
      )}
      <h2 className={`font-display text-3xl md:text-4xl lg:text-5xl font-semibold leading-tight ${light ? "text-foreground" : ""}`}>
        {title}
      </h2>
      {description && (
        <p className="mt-5 text-muted-foreground leading-relaxed text-base md:text-lg">
          {description}
        </p>
      )}
    </motion.div>
  );
}
