import { useParams, Link } from "react-router-dom";
import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { ProjectCard } from "@/components/ui/ProjectCard";
import { MapPin, CheckCircle, ArrowRight, Download, Share2, ChevronLeft } from "lucide-react";
import { getProjectById, projects } from "@/lib/projects";

export default function ProjectDetailPage() {
  const { id } = useParams();
  const project = getProjectById(id);

  if (!project) {
    return (
      <PublicLayout>
        <section className="min-h-[60vh] flex items-center pt-20">
          <div className="container-luxury text-center">
            <span className="text-xs uppercase tracking-[0.3em] text-primary mb-4 block">Projects</span>
            <h1 className="font-display text-4xl md:text-5xl font-bold mb-4">Project Not Found</h1>
            <p className="text-muted-foreground max-w-xl mx-auto mb-8">
              The project you requested is not available in this frontend demo. You can continue exploring the live project catalogue below.
            </p>
            <div className="flex flex-wrap justify-center gap-4">
              <Link to="/projects" className="luxury-btn-primary">
                Browse Projects <ArrowRight className="w-4 h-4" />
              </Link>
              <Link to="/contact" className="luxury-btn-outline">
                Contact Rockmould
              </Link>
            </div>
          </div>
        </section>
      </PublicLayout>
    );
  }

  const relatedProjects = projects.filter((entry) => entry.id !== project.id).slice(0, 3);

  return (
    <PublicLayout>
      <section className="relative min-h-[60vh] flex items-end overflow-hidden pt-20">
        <img src={project.image} alt={project.title} className="absolute inset-0 w-full h-full object-cover" />
        <div className="absolute inset-0 bg-gradient-to-t from-background via-background/40 to-transparent" />
        <div className="container-luxury relative z-10 pb-12">
          <Link
            to="/projects"
            className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors mb-6"
          >
            <ChevronLeft className="w-4 h-4" /> Back to Projects
          </Link>
          <div className="flex flex-wrap items-center gap-3 mb-3">
            <span className="text-[11px] uppercase tracking-[0.2em] text-primary">{project.category}</span>
            <span
              className={`status-badge ${
                project.status === "Completed" ? "status-completed" : project.status === "Ongoing" ? "status-active" : "status-pending"
              }`}
            >
              {project.status}
            </span>
          </div>
          <h1 className="font-display text-4xl md:text-5xl lg:text-6xl font-bold mb-4">{project.title}</h1>
          <div className="flex items-center gap-4 text-muted-foreground">
            <span className="flex items-center gap-1.5">
              <MapPin className="w-4 h-4" />
              {project.location}
            </span>
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-luxury">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div className="lg:col-span-2 space-y-16">
              <div>
                <h2 className="font-display text-2xl font-semibold mb-4">Project Overview</h2>
                <p className="text-muted-foreground leading-relaxed">{project.overview}</p>
              </div>

              <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                {project.highlights.map((highlight) => (
                  <div key={highlight.label} className="luxury-card text-center">
                    <p className="font-display text-2xl font-bold text-primary">{highlight.value}</p>
                    <p className="text-xs text-muted-foreground uppercase tracking-wider mt-1">
                      {highlight.label}
                    </p>
                  </div>
                ))}
              </div>

              <div>
                <h2 className="font-display text-2xl font-semibold mb-6">Features and Amenities</h2>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                  {project.features.map((feature) => (
                    <div key={feature} className="flex items-center gap-3">
                      <CheckCircle className="w-4 h-4 text-primary flex-shrink-0" />
                      <span className="text-sm text-muted-foreground">{feature}</span>
                    </div>
                  ))}
                </div>
              </div>

              <div>
                <h2 className="font-display text-2xl font-semibold mb-6">Construction Progress</h2>
                <div className="mb-6">
                  <div className="flex items-center justify-between mb-2">
                    <span className="text-sm text-muted-foreground">Overall Completion</span>
                    <span className="text-sm font-semibold text-primary">{project.progress}%</span>
                  </div>
                  <div className="h-2 bg-muted rounded-full overflow-hidden">
                    <motion.div
                      initial={{ width: 0 }}
                      whileInView={{ width: `${project.progress}%` }}
                      viewport={{ once: true }}
                      transition={{ duration: 1, ease: "easeOut" }}
                      className="h-full bg-primary rounded-full"
                    />
                  </div>
                </div>
                <div className="space-y-0">
                  {project.milestones.map((milestone, index) => (
                    <div key={milestone.title} className="flex gap-4 pb-6 relative">
                      <div className="flex flex-col items-center">
                        <div
                          className={`w-3 h-3 rounded-full flex-shrink-0 ${
                            milestone.done ? "bg-primary" : "bg-muted"
                          }`}
                        />
                        {index < project.milestones.length - 1 && <div className="w-px flex-1 bg-border" />}
                      </div>
                      <div className="-mt-1">
                        <h4
                          className={`text-sm font-medium ${
                            milestone.done ? "text-foreground" : "text-muted-foreground"
                          }`}
                        >
                          {milestone.title}
                        </h4>
                        <span className="text-xs text-muted-foreground">{milestone.date}</span>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              <div>
                <h2 className="font-display text-2xl font-semibold mb-6">Gallery</h2>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                  {project.gallery.map((image, index) => (
                    <div key={index} className="img-zoom rounded-md overflow-hidden aspect-square">
                      <img src={image} alt={`Gallery ${index + 1}`} loading="lazy" className="w-full h-full object-cover" />
                    </div>
                  ))}
                </div>
              </div>
            </div>

            <div className="space-y-6">
              <div className="luxury-card sticky top-24">
                <h3 className="font-display text-lg font-semibold mb-6">Project Summary</h3>
                <div className="space-y-4 mb-8">
                  <div className="flex justify-between text-sm">
                    <span className="text-muted-foreground">Location</span>
                    <span>{project.location}</span>
                  </div>
                  <div className="luxury-divider" />
                  <div className="flex justify-between text-sm">
                    <span className="text-muted-foreground">Category</span>
                    <span>{project.category}</span>
                  </div>
                  <div className="luxury-divider" />
                  <div className="flex justify-between text-sm">
                    <span className="text-muted-foreground">Status</span>
                    <span className="text-primary">{project.status}</span>
                  </div>
                  <div className="luxury-divider" />
                  <div className="flex justify-between text-sm">
                    <span className="text-muted-foreground">Progress</span>
                    <span>{project.progress}%</span>
                  </div>
                </div>
                <Link to="/contact" className="luxury-btn-primary w-full text-xs">
                  Inquire Now <ArrowRight className="w-4 h-4" />
                </Link>
                <div className="flex gap-3 mt-4">
                  <button className="luxury-btn-outline flex-1 text-xs py-2">
                    <Download className="w-3.5 h-3.5" /> Brochure
                  </button>
                  <button className="luxury-btn-outline flex-1 text-xs py-2">
                    <Share2 className="w-3.5 h-3.5" /> Share
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="section-padding bg-card">
        <div className="container-luxury">
          <h2 className="font-display text-2xl font-semibold mb-8">Related Projects</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {relatedProjects.map((entry, index) => (
              <ProjectCard key={entry.id} {...entry} index={index} />
            ))}
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
