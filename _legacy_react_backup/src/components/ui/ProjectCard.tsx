import { motion } from "framer-motion";
import { Link } from "react-router-dom";
import { MapPin, ArrowUpRight } from "lucide-react";

interface ProjectCardProps {
  id: string;
  title: string;
  location: string;
  category: string;
  status: string;
  image: string;
  index?: number;
}

export function ProjectCard({ id, title, location, category, status, image, index = 0 }: ProjectCardProps) {
  const statusClass = status === "Completed" ? "status-completed" :
    status === "Ongoing" ? "status-active" : "status-pending";

  return (
    <motion.div
      initial={{ opacity: 0, y: 24 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5, delay: index * 0.1 }}
    >
      <Link to={`/projects/${id}`} className="group block">
        <div className="luxury-card p-0 overflow-hidden">
          <div className="img-zoom aspect-[4/3] relative">
            <img src={image} alt={title} className="w-full h-full object-cover" />
            <div className="absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent" />
            <span className={`absolute top-4 left-4 ${statusClass}`}>{status}</span>
            <div className="absolute bottom-4 right-4 w-10 h-10 rounded-full bg-primary/20 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
              <ArrowUpRight className="w-4 h-4 text-primary" />
            </div>
          </div>
          <div className="p-6">
            <span className="text-[11px] uppercase tracking-[0.2em] text-primary mb-2 block">{category}</span>
            <h3 className="font-display text-xl font-semibold mb-2 group-hover:text-primary transition-colors duration-300">{title}</h3>
            <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
              <MapPin className="w-3.5 h-3.5" />
              {location}
            </div>
          </div>
        </div>
      </Link>
    </motion.div>
  );
}
