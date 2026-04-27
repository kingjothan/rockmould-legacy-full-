import { AdminLayout } from "@/components/layout/AdminLayout";
import { Image, Video, Upload, Search, Filter, Star, Trash2, Eye, Grid, List } from "lucide-react";
import { useState } from "react";

const mediaItems = [
  { id: 1, name: "Azure Towers Aerial View", type: "image", project: "Azure Towers", date: "March 10, 2026", featured: true },
  { id: 2, name: "Construction Progress March", type: "image", project: "Crown Heights", date: "March 8, 2026", featured: false },
  { id: 3, name: "Meridian Plaza Walkthrough", type: "video", project: "Meridian Plaza", date: "March 5, 2026", featured: true },
  { id: 4, name: "Interior Finishing Phase 2", type: "image", project: "Azure Towers", date: "March 3, 2026", featured: false },
  { id: 5, name: "Site Foundation Laying", type: "image", project: "Crown Heights", date: "Feb 28, 2026", featured: false },
  { id: 6, name: "Client Handover Ceremony", type: "video", project: "Meridian Plaza", date: "Feb 20, 2026", featured: true },
  { id: 7, name: "Structural Completion", type: "image", project: "Meridian Plaza", date: "Feb 15, 2026", featured: false },
  { id: 8, name: "Azure Towers Night View", type: "image", project: "Azure Towers", date: "Feb 10, 2026", featured: false },
];

export default function AdminMedia() {
  const [search, setSearch] = useState("");
  const [view, setView] = useState<"grid" | "list">("grid");
  const [filter, setFilter] = useState<"all" | "image" | "video">("all");

  const filtered = mediaItems.filter(m =>
    (filter === "all" || m.type === filter) &&
    m.name.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Media Library</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage project photos and videos</p>
          </div>
          <button className="luxury-btn-primary text-xs gap-2"><Upload className="w-3.5 h-3.5" /> Upload Media</button>
        </div>

        <div className="luxury-card">
          <div className="flex flex-col sm:flex-row gap-3 mb-6">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search media..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
            </div>
            <div className="flex gap-2">
              {(["all", "image", "video"] as const).map((f) => (
                <button key={f} onClick={() => setFilter(f)} className={`text-xs px-3 py-2 rounded-sm border transition-colors ${filter === f ? "border-primary text-primary bg-primary/5" : "border-border text-muted-foreground hover:text-foreground"}`}>
                  {f === "all" ? "All" : f === "image" ? "Photos" : "Videos"}
                </button>
              ))}
            </div>
            <div className="flex gap-1 border border-border rounded-sm">
              <button onClick={() => setView("grid")} className={`p-2 ${view === "grid" ? "text-primary bg-primary/5" : "text-muted-foreground"}`}><Grid className="w-4 h-4" /></button>
              <button onClick={() => setView("list")} className={`p-2 ${view === "list" ? "text-primary bg-primary/5" : "text-muted-foreground"}`}><List className="w-4 h-4" /></button>
            </div>
          </div>

          {view === "grid" ? (
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
              {filtered.map((item) => (
                <div key={item.id} className="group relative border border-border/50 rounded-sm overflow-hidden hover:border-primary/30 transition-colors">
                  <div className="aspect-square bg-muted flex items-center justify-center">
                    {item.type === "video" ? <Video className="w-8 h-8 text-muted-foreground" /> : <Image className="w-8 h-8 text-muted-foreground" />}
                  </div>
                  {item.featured && (
                    <div className="absolute top-2 right-2"><Star className="w-4 h-4 text-primary fill-primary" /></div>
                  )}
                  <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <button className="p-2 bg-white/10 rounded-sm hover:bg-white/20"><Eye className="w-4 h-4 text-white" /></button>
                    <button className="p-2 bg-white/10 rounded-sm hover:bg-white/20"><Trash2 className="w-4 h-4 text-white" /></button>
                  </div>
                  <div className="p-3">
                    <p className="text-xs font-medium truncate">{item.name}</p>
                    <p className="text-[10px] text-muted-foreground">{item.project}</p>
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="space-y-2">
              {filtered.map((item) => (
                <div key={item.id} className="flex items-center gap-4 p-3 border border-border/50 rounded-sm hover:bg-muted/30 transition-colors group">
                  <div className="w-10 h-10 bg-muted rounded-sm flex items-center justify-center flex-shrink-0">
                    {item.type === "video" ? <Video className="w-5 h-5 text-muted-foreground" /> : <Image className="w-5 h-5 text-muted-foreground" />}
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="text-sm font-medium truncate">{item.name}</p>
                    <p className="text-xs text-muted-foreground">{item.project} · {item.date}</p>
                  </div>
                  {item.featured && <Star className="w-4 h-4 text-primary fill-primary flex-shrink-0" />}
                  <span className={`text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm ${item.type === "video" ? "text-blue-400 bg-blue-400/10" : "text-emerald-400 bg-emerald-400/10"}`}>{item.type}</span>
                  <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button className="p-1.5 hover:bg-muted rounded-sm"><Eye className="w-3.5 h-3.5" /></button>
                    <button className="p-1.5 hover:bg-muted rounded-sm"><Trash2 className="w-3.5 h-3.5 text-destructive" /></button>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </AdminLayout>
  );
}
