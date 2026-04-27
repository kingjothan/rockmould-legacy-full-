import { AdminLayout } from "@/components/layout/AdminLayout";
import { Clock, Plus, Search, Edit, Trash2, Eye, EyeOff, Image } from "lucide-react";
import { useState } from "react";

const updates = [
  { id: 1, project: "Azure Towers", title: "Foundation work completed for Block C", date: "March 10, 2026", progress: 48, visible: true, hasMedia: true },
  { id: 2, project: "Crown Heights", title: "Site clearing and grading in progress", date: "March 8, 2026", progress: 12, visible: true, hasMedia: true },
  { id: 3, project: "Azure Towers", title: "Structural framework for floors 1-5 complete", date: "March 3, 2026", progress: 45, visible: true, hasMedia: false },
  { id: 4, project: "Meridian Plaza", title: "Final handover inspection completed", date: "Feb 28, 2026", progress: 100, visible: false, hasMedia: true },
  { id: 5, project: "Crown Heights", title: "Architectural drawings approved", date: "Feb 20, 2026", progress: 10, visible: true, hasMedia: false },
];

export default function AdminUpdates() {
  const [search, setSearch] = useState("");

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Timeline & Updates</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage project progress updates visible to clients</p>
          </div>
          <button className="luxury-btn-primary text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Add Update</button>
        </div>

        <div className="luxury-card">
          <div className="flex flex-col sm:flex-row gap-3 mb-6">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search updates..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
            </div>
          </div>

          <div className="space-y-4">
            {updates.filter(u => u.title.toLowerCase().includes(search.toLowerCase()) || u.project.toLowerCase().includes(search.toLowerCase())).map((u) => (
              <div key={u.id} className="border border-border/50 rounded-sm p-4 hover:bg-muted/20 transition-colors">
                <div className="flex items-start justify-between gap-4">
                  <div className="flex-1">
                    <div className="flex items-center gap-2 mb-1">
                      <span className="text-[10px] uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded-sm">{u.project}</span>
                      {u.hasMedia && <Image className="w-3.5 h-3.5 text-muted-foreground" />}
                      {!u.visible && <EyeOff className="w-3.5 h-3.5 text-amber-400" />}
                    </div>
                    <h3 className="text-sm font-medium">{u.title}</h3>
                    <div className="flex items-center gap-4 mt-2">
                      <span className="text-xs text-muted-foreground flex items-center gap-1"><Clock className="w-3 h-3" />{u.date}</span>
                      <span className="text-xs text-muted-foreground">Progress: {u.progress}%</span>
                    </div>
                    <div className="h-1 bg-muted rounded-full mt-2 w-48">
                      <div className="h-full bg-primary rounded-full" style={{ width: `${u.progress}%` }} />
                    </div>
                  </div>
                  <div className="flex gap-1">
                    <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
                    <button className="p-1.5 hover:bg-muted rounded-sm">{u.visible ? <Eye className="w-3.5 h-3.5 text-muted-foreground" /> : <EyeOff className="w-3.5 h-3.5 text-amber-400" />}</button>
                    <button className="p-1.5 hover:bg-muted rounded-sm"><Trash2 className="w-3.5 h-3.5 text-destructive" /></button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
