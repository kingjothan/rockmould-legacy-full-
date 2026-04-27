import { AdminLayout } from "@/components/layout/AdminLayout";
import { Plus, Search, Eye, Edit, MapPin } from "lucide-react";
import { useState } from "react";

const projects = [
  { id: "1", name: "Azure Towers", location: "Victoria Island", status: "Ongoing", clients: 45, progress: 45, budget: "₦2.5B" },
  { id: "2", name: "Meridian Plaza", location: "Ikoyi", status: "Completed", clients: 30, progress: 100, budget: "₦1.8B" },
  { id: "3", name: "Crown Heights", location: "Lekki Phase 1", status: "Upcoming", clients: 12, progress: 10, budget: "₦3.2B" },
  { id: "4", name: "Harbor View Estate", location: "Lekki", status: "Ongoing", clients: 28, progress: 65, budget: "₦1.5B" },
  { id: "5", name: "Royal Courts", location: "Ikoyi", status: "Completed", clients: 12, progress: 100, budget: "₦800M" },
];

export default function AdminProjects() {
  const [search, setSearch] = useState("");
  const filtered = projects.filter((p) => p.name.toLowerCase().includes(search.toLowerCase()));

  return (
    <AdminLayout>
      <div className="max-w-7xl mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="font-display text-2xl font-semibold">Project Management</h1>
          <button className="luxury-btn-primary text-xs py-2"><Plus className="w-4 h-4" /> Create Project</button>
        </div>
        <div className="luxury-card p-0 overflow-hidden">
          <div className="p-4 border-b border-border">
            <div className="relative w-full max-w-sm">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search projects..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary/50" />
            </div>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead><tr className="border-b border-border text-xs uppercase tracking-wider text-muted-foreground">
                <th className="text-left px-4 py-3">Project</th><th className="text-left px-4 py-3">Status</th><th className="text-left px-4 py-3">Clients</th><th className="text-left px-4 py-3">Progress</th><th className="text-right px-4 py-3">Budget</th><th className="text-right px-4 py-3">Actions</th>
              </tr></thead>
              <tbody>
                {filtered.map((p) => (
                  <tr key={p.id} className="border-b border-border hover:bg-muted/30 transition-colors">
                    <td className="px-4 py-3"><p className="font-medium">{p.name}</p><p className="text-xs text-muted-foreground flex items-center gap-1"><MapPin className="w-3 h-3" />{p.location}</p></td>
                    <td className="px-4 py-3"><span className={`status-badge ${p.status === "Completed" ? "status-completed" : p.status === "Ongoing" ? "status-active" : "status-pending"}`}>{p.status}</span></td>
                    <td className="px-4 py-3">{p.clients}</td>
                    <td className="px-4 py-3"><div className="flex items-center gap-2"><div className="w-20 h-1.5 bg-muted rounded-full overflow-hidden"><div className="h-full bg-primary rounded-full" style={{ width: `${p.progress}%` }} /></div><span className="text-xs">{p.progress}%</span></div></td>
                    <td className="px-4 py-3 text-right">{p.budget}</td>
                    <td className="px-4 py-3 text-right"><div className="flex items-center justify-end gap-1"><button className="p-1.5 text-muted-foreground hover:text-foreground"><Eye className="w-4 h-4" /></button><button className="p-1.5 text-muted-foreground hover:text-foreground"><Edit className="w-4 h-4" /></button></div></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
