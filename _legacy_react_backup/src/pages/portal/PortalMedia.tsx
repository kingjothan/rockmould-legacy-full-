import { useEffect, useMemo, useState } from "react";
import { Download, Eye, Image, Video, X } from "lucide-react";
import { PortalLayout } from "@/components/layout/PortalLayout";
import { toast } from "@/components/ui/sonner";

const media = [
  { id: 1, name: "Aerial View - Azure Towers", type: "image", project: "Azure Towers", date: "March 10, 2026" },
  { id: 2, name: "Construction Progress March", type: "image", project: "Azure Towers", date: "March 8, 2026" },
  { id: 3, name: "Foundation Completion", type: "image", project: "Crown Heights", date: "March 5, 2026" },
  { id: 4, name: "Site Walkthrough Video", type: "video", project: "Azure Towers", date: "March 3, 2026" },
  { id: 5, name: "Structural Framework", type: "image", project: "Azure Towers", date: "Feb 28, 2026" },
  { id: 6, name: "Handover Ceremony", type: "video", project: "Meridian Plaza", date: "Feb 20, 2026" },
  { id: 7, name: "Interior Finishing", type: "image", project: "Meridian Plaza", date: "Feb 15, 2026" },
  { id: 8, name: "Night Render", type: "image", project: "Crown Heights", date: "Feb 10, 2026" },
];

export default function PortalMedia() {
  const [filter, setFilter] = useState<"all" | "image" | "video">("all");
  const [projectFilter, setProjectFilter] = useState("all");
  const [lightbox, setLightbox] = useState<number | null>(null);

  const filtered = media.filter(
    (item) =>
      (filter === "all" || item.type === filter) &&
      (projectFilter === "all" || item.project === projectFilter),
  );

  const projects = [...new Set(media.map((item) => item.project))];
  const activeMedia = useMemo(
    () => media.find((item) => item.id === lightbox) ?? null,
    [lightbox],
  );

  useEffect(() => {
    if (!lightbox) {
      return;
    }

    const handleKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        setLightbox(null);
      }
    };

    document.body.style.overflow = "hidden";
    window.addEventListener("keydown", handleKeyDown);

    return () => {
      document.body.style.overflow = "";
      window.removeEventListener("keydown", handleKeyDown);
    };
  }, [lightbox]);

  return (
    <PortalLayout>
      <div className="max-w-6xl mx-auto space-y-6">
        <h1 className="font-display text-2xl font-semibold">Media Gallery</h1>

        <div className="flex flex-col sm:flex-row gap-3">
          <div className="flex gap-2">
            {(["all", "image", "video"] as const).map((value) => (
              <button
                key={value}
                type="button"
                onClick={() => setFilter(value)}
                className={`text-xs px-3 py-2 rounded-sm border transition-colors ${
                  filter === value
                    ? "border-primary text-primary bg-primary/5"
                    : "border-border text-muted-foreground hover:text-foreground"
                }`}
              >
                {value === "all" ? "All" : value === "image" ? "Photos" : "Videos"}
              </button>
            ))}
          </div>
          <select
            value={projectFilter}
            onChange={(event) => setProjectFilter(event.target.value)}
            className="bg-muted border border-border rounded-sm px-3 py-2 text-xs focus:outline-none focus:border-primary/50"
          >
            <option value="all">All Projects</option>
            {projects.map((project) => (
              <option key={project} value={project}>
                {project}
              </option>
            ))}
          </select>
        </div>

        {filtered.length === 0 ? (
          <div className="luxury-card text-center py-16">
            <Image className="w-12 h-12 text-muted-foreground mx-auto mb-4" />
            <h3 className="font-display text-lg font-semibold mb-2">No Media Found</h3>
            <p className="text-sm text-muted-foreground">
              No photos or videos match your current filters.
            </p>
          </div>
        ) : (
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            {filtered.map((item) => (
              <div
                key={item.id}
                onClick={() => setLightbox(item.id)}
                className="group cursor-pointer border border-border/50 rounded-sm overflow-hidden hover:border-primary/30 transition-colors"
              >
                <div className="aspect-square bg-muted flex items-center justify-center relative">
                  {item.type === "video" ? (
                    <Video className="w-10 h-10 text-muted-foreground" />
                  ) : (
                    <Image className="w-10 h-10 text-muted-foreground" />
                  )}
                  <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <Eye className="w-6 h-6 text-white" />
                  </div>
                </div>
                <div className="p-3">
                  <p className="text-xs font-medium truncate">{item.name}</p>
                  <p className="text-[10px] text-muted-foreground">
                    {item.project} | {item.date}
                  </p>
                </div>
              </div>
            ))}
          </div>
        )}

        {lightbox && activeMedia && (
          <div
            className="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
            onClick={() => setLightbox(null)}
          >
            <div
              className="max-w-3xl w-full bg-card border border-border rounded-sm p-6"
              onClick={(event) => event.stopPropagation()}
            >
              <div className="flex items-center justify-between mb-4">
                <p className="text-sm font-medium">{activeMedia.name}</p>
                <button
                  type="button"
                  onClick={() => setLightbox(null)}
                  className="p-1 hover:bg-muted rounded-sm"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>
              <div className="aspect-video bg-muted rounded-sm flex items-center justify-center mb-4">
                {activeMedia.type === "video" ? (
                  <Video className="w-16 h-16 text-muted-foreground" />
                ) : (
                  <Image className="w-16 h-16 text-muted-foreground" />
                )}
              </div>
              <div className="flex justify-between items-center">
                <p className="text-xs text-muted-foreground">
                  {activeMedia.project} | {activeMedia.date}
                </p>
                <button
                  type="button"
                  onClick={() => toast.success("Media download prepared.")}
                  className="luxury-btn-outline text-xs gap-2"
                >
                  <Download className="w-3.5 h-3.5" /> Download
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </PortalLayout>
  );
}
