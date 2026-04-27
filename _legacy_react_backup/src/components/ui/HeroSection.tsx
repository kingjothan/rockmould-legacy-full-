import { motion } from "framer-motion";
import { ReactNode } from "react";

interface HeroSectionProps {
  title: string;
  subtitle?: string;
  description?: string;
  image?: string;
  overlay?: boolean;
  children?: ReactNode;
  height?: "full" | "large" | "medium";
  align?: "left" | "center";
}

export function HeroSection({
  title, subtitle, description, image, overlay = true,
  children, height = "large", align = "center"
}: HeroSectionProps) {
  const heightClass = height === "full" ? "min-h-screen" : height === "large" ? "min-h-[80vh]" : "min-h-[50vh]";

  return (
    <section className={`relative ${heightClass} flex items-center overflow-hidden`}>
      {image && (
        <>
          <img src={image} alt="" className="absolute inset-0 w-full h-full object-cover" />
          {overlay && <div className="absolute inset-0 bg-background/70" />}
          <div className="absolute inset-0 bg-gradient-to-t from-background via-background/40 to-transparent" />
        </>
      )}
      {!image && <div className="absolute inset-0 bg-gradient-to-b from-secondary/50 to-background" />}

      <div className={`container-luxury relative z-10 py-32 ${align === "center" ? "text-center" : ""}`}>
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, ease: [0.25, 0.46, 0.45, 0.94] }}
          className={align === "center" ? "max-w-4xl mx-auto" : "max-w-3xl"}
        >
          {subtitle && (
            <motion.span
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.2 }}
              className="text-xs uppercase tracking-[0.3em] text-primary mb-6 block font-medium"
            >
              {subtitle}
            </motion.span>
          )}
          <h1 className="font-display text-4xl md:text-5xl lg:text-7xl font-bold leading-[1.1] mb-6">
            {title}
          </h1>
          {description && (
            <motion.p
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.4 }}
              className="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-2xl mx-auto"
            >
              {description}
            </motion.p>
          )}
          {children && (
            <motion.div
              initial={{ opacity: 0, y: 16 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.6 }}
              className="mt-10 flex flex-wrap gap-4 justify-center"
            >
              {children}
            </motion.div>
          )}
        </motion.div>
      </div>
    </section>
  );
}
