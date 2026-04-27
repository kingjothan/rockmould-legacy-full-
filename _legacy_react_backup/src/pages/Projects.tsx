import { Link, useSearchParams } from "react-router-dom";
import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { ProjectCard } from "@/components/ui/ProjectCard";
import { Search, LayoutGrid, List, SlidersHorizontal } from "lucide-react";
import { projects } from "@/lib/projects";

const filters = [
  { label: "All", value: "all" },
  { label: "Residential", value: "residential" },
  { label: "Commercial", value: "commercial" },
  { label: "Mixed-Use", value: "mixed-use" },
  { label: "Luxury Residential", value: "luxury-residential" },
];

const statusFilters = [
  { label: "All Status", value: "all" },
  { label: "Ongoing", value: "ongoing" },
  { label: "Completed", value: "completed" },
  { label: "Upcoming", value: "upcoming" },
];

function slugify(value: string) {
  return value
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

function matchesCategory(category: string, filter: string) {
  const normalizedCategory = slugify(category);

  if (filter === "all") {
    return true;
  }

  if (filter === "residential") {
    return normalizedCategory === "residential" || normalizedCategory === "luxury-residential";
  }

  return normalizedCategory === filter;
}

export default function ProjectsPage() {
  const [searchParams, setSearchParams] = useSearchParams();
  const activeFilter = searchParams.get("type") ?? "all";
  const activeStatus = searchParams.get("status") ?? "all";
  const search = searchParams.get("q") ?? "";
  const viewMode = searchParams.get("view") === "list" ? "list" : "grid";

  const updateParams = (updates: Record<string, string | null>) => {
    const next = new URLSearchParams(searchParams);

    Object.entries(updates).forEach(([key, value]) => {
      if (!value || value === "all") {
        next.delete(key);
      } else {
        next.set(key, value);
      }
    });

    setSearchParams(next, { replace: true });
  };

  const filtered = projects.filter((p) => {
    const matchCat = matchesCategory(p.category, activeFilter);
    const matchStatus = activeStatus === "all" || slugify(p.status) === activeStatus;
    const matchSearch = p.title.toLowerCase().includes(search.toLowerCase()) || p.location.toLowerCase().includes(search.toLowerCase());
    return matchCat && matchStatus && matchSearch;
  });

  return (
    <PublicLayout>
      <HeroSection title="Our Projects" subtitle="Premium Developments" description="Explore our portfolio of exceptional real estate developments across prime locations." height="medium" />

      <section className="section-padding">
        <div className="container-luxury">
          {/* Filters */}
          <div className="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-12">
            <div className="flex flex-wrap gap-2">
              {filters.map((filter) => (
                <button
                  key={filter.value}
                  onClick={() => updateParams({ type: filter.value })}
                  className={`px-4 py-2 text-xs uppercase tracking-[0.12em] rounded-sm border transition-all duration-300 ${
                    activeFilter === filter.value
                      ? "bg-primary text-primary-foreground border-primary"
                      : "border-border text-muted-foreground hover:border-primary/30"
                  }`}
                >
                  {filter.label}
                </button>
              ))}
            </div>
            <div className="flex items-center gap-3 w-full lg:w-auto">
              <div className="relative flex-1 lg:w-64">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <input
                  value={search}
                  onChange={(e) => updateParams({ q: e.target.value || null })}
                  placeholder="Search projects..."
                  className="w-full bg-card border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors"
                />
              </div>
              <select
                value={activeStatus}
                onChange={(e) => updateParams({ status: e.target.value })}
                className="bg-card border border-border rounded-sm px-3 py-2.5 text-sm text-muted-foreground focus:outline-none focus:border-primary/50"
              >
                {statusFilters.map((status) => (
                  <option key={status.value} value={status.value}>
                    {status.label}
                  </option>
                ))}
              </select>
              <div className="hidden md:flex border border-border rounded-sm overflow-hidden">
                <button onClick={() => updateParams({ view: null })} className={`p-2.5 ${viewMode === "grid" ? "bg-primary/10 text-primary" : "text-muted-foreground"}`}><LayoutGrid className="w-4 h-4" /></button>
                <button onClick={() => updateParams({ view: "list" })} className={`p-2.5 ${viewMode === "list" ? "bg-primary/10 text-primary" : "text-muted-foreground"}`}><List className="w-4 h-4" /></button>
              </div>
            </div>
          </div>

          {/* Results */}
          {filtered.length === 0 ? (
            <div className="text-center py-20">
              <SlidersHorizontal className="w-12 h-12 text-muted-foreground mx-auto mb-4" />
              <h3 className="font-display text-xl font-semibold mb-2">No Projects Found</h3>
              <p className="text-sm text-muted-foreground mb-6">Try adjusting your filters or search terms.</p>
              <button
                type="button"
                onClick={() => setSearchParams({}, { replace: true })}
                className="luxury-btn-outline text-xs"
              >
                Clear Filters
              </button>
            </div>
          ) : (
            <div className={viewMode === "grid" ? "grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" : "space-y-4"}>
              {filtered.map((p, i) => viewMode === "grid" ? (
                <ProjectCard key={p.id} {...p} index={i} />
              ) : (
                <motion.div key={p.id} initial={{ opacity: 0 }} whileInView={{ opacity: 1 }} viewport={{ once: true }}>
                  <Link to={`/projects/${p.id}`} className="group flex gap-6 luxury-card p-4">
                    <div className="w-32 h-24 rounded-sm overflow-hidden flex-shrink-0 img-zoom">
                      <img src={p.image} alt={p.title} className="w-full h-full object-cover" />
                    </div>
                    <div className="flex-1 min-w-0">
                      <div className="flex items-center gap-3 mb-1">
                        <span className="text-[11px] uppercase tracking-[0.15em] text-primary">{p.category}</span>
                        <span className={`text-[11px] ${p.status === "Completed" ? "text-primary" : p.status === "Ongoing" ? "text-emerald-400" : "text-amber-400"}`}>{p.status}</span>
                      </div>
                      <h3 className="font-display text-lg font-semibold group-hover:text-primary transition-colors truncate">{p.title}</h3>
                      <p className="text-sm text-muted-foreground">{p.location}</p>
                    </div>
                  </Link>
                </motion.div>
              ))}
            </div>
          )}
        </div>
      </section>
    </PublicLayout>
  );
}
