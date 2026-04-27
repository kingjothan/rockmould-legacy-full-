import { useState } from "react";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { Play, Image as ImageIcon, X } from "lucide-react";
import project1 from "@/assets/project-1.jpg";
import project2 from "@/assets/project-2.jpg";
import project3 from "@/assets/project-3.jpg";
import project4 from "@/assets/project-4.jpg";
import heroImage from "@/assets/hero-main.jpg";
import aboutHero from "@/assets/about-hero.jpg";

const media = [
  { type: "image" as const, src: project1, title: "Azure Towers Exterior", category: "Projects" },
  { type: "image" as const, src: project2, title: "Meridian Plaza", category: "Projects" },
  { type: "video" as const, src: "", title: "Construction Progress Q1", category: "Construction" },
  { type: "image" as const, src: project3, title: "Crown Heights Render", category: "Projects" },
  { type: "image" as const, src: project4, title: "Penthouse Interior", category: "Interiors" },
  { type: "image" as const, src: heroImage, title: "Rockmould Skyline", category: "Aerial" },
  { type: "video" as const, src: "", title: "Project Walkthrough", category: "Projects" },
  { type: "image" as const, src: aboutHero, title: "Site Development", category: "Construction" },
];

const categories = ["All", "Projects", "Construction", "Interiors", "Aerial"];

export default function GalleryPage() {
  const [activeFilter, setActiveFilter] = useState("All");
  const [lightbox, setLightbox] = useState<number | null>(null);

  const filtered = activeFilter === "All" ? media : media.filter((m) => m.category === activeFilter);

  return (
    <PublicLayout>
      <HeroSection title="Gallery & Media" subtitle="Visual Stories" description="Explore our projects, construction progress, and developments through stunning visuals." height="medium" />

      <section className="section-padding">
        <div className="container-luxury">
          <div className="flex flex-wrap gap-2 mb-10">
            {categories.map((c) => (
              <button key={c} onClick={() => setActiveFilter(c)} className={`px-4 py-2 text-xs uppercase tracking-[0.12em] rounded-sm border transition-all ${activeFilter === c ? "bg-primary text-primary-foreground border-primary" : "border-border text-muted-foreground hover:border-primary/30"}`}>{c}</button>
            ))}
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            {filtered.map((m, i) => (
              <button key={i} onClick={() => m.type === "image" && setLightbox(i)} className="group relative aspect-square overflow-hidden rounded-md bg-muted">
                {m.type === "image" ? (
                  <>
                    <img src={m.src} alt={m.title} loading="lazy" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                    <div className="absolute inset-0 bg-background/0 group-hover:bg-background/40 transition-colors duration-300 flex items-center justify-center">
                      <ImageIcon className="w-6 h-6 text-foreground opacity-0 group-hover:opacity-100 transition-opacity" />
                    </div>
                  </>
                ) : (
                  <div className="w-full h-full flex items-center justify-center">
                    <div className="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center">
                      <Play className="w-6 h-6 text-primary" />
                    </div>
                  </div>
                )}
                <div className="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-background/80 to-transparent">
                  <p className="text-xs text-foreground truncate">{m.title}</p>
                </div>
              </button>
            ))}
          </div>
        </div>
      </section>

      {/* Lightbox */}
      {lightbox !== null && (
        <div className="fixed inset-0 z-50 bg-background/95 flex items-center justify-center p-4" onClick={() => setLightbox(null)}>
          <button className="absolute top-6 right-6 text-muted-foreground hover:text-foreground"><X className="w-6 h-6" /></button>
          <img src={filtered[lightbox]?.src} alt="" className="max-w-full max-h-[85vh] object-contain rounded-md" />
        </div>
      )}
    </PublicLayout>
  );
}
