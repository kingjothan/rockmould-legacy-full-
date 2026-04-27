import { AdminLayout } from "@/components/layout/AdminLayout";
import { Activity, Search, Filter, User, FolderKanban, Receipt, FileText, Settings, ShieldCheck } from "lucide-react";
import { useState } from "react";

const logs = [
  { id: 1, user: "Adebayo Admin", action: "Approved KYC request", target: "Emeka Nwankwo", icon: ShieldCheck, time: "2 hours ago", color: "text-emerald-400" },
  { id: 2, user: "Folake Manager", action: "Updated project progress", target: "Azure Towers (48%)", icon: FolderKanban, time: "4 hours ago", color: "text-primary" },
  { id: 3, user: "Adebayo Admin", action: "Recorded payment", target: "₦5,000,000 - Adebayo Ogunlesi", icon: Receipt, time: "6 hours ago", color: "text-blue-400" },
  { id: 4, user: "Chidi PM", action: "Uploaded 5 photos", target: "Azure Towers Gallery", icon: FileText, time: "8 hours ago", color: "text-amber-400" },
  { id: 5, user: "Folake Manager", action: "Created new client", target: "Ngozi Eze", icon: User, time: "1 day ago", color: "text-purple-400" },
  { id: 6, user: "Adebayo Admin", action: "Updated company settings", target: "Contact Information", icon: Settings, time: "1 day ago", color: "text-muted-foreground" },
  { id: 7, user: "Chidi PM", action: "Added timeline update", target: "Crown Heights - Site clearing", icon: FolderKanban, time: "2 days ago", color: "text-primary" },
  { id: 8, user: "Folake Manager", action: "Published blog post", target: "Future of Luxury Real Estate in Lagos", icon: FileText, time: "2 days ago", color: "text-blue-400" },
  { id: 9, user: "Adebayo Admin", action: "Rejected KYC request", target: "Invalid ID - John Smith", icon: ShieldCheck, time: "3 days ago", color: "text-destructive" },
  { id: 10, user: "Ngozi Finance", action: "Generated statement", target: "Adebayo Ogunlesi - Azure Towers", icon: Receipt, time: "3 days ago", color: "text-emerald-400" },
];

export default function AdminActivity() {
  const [search, setSearch] = useState("");

  return (
    <AdminLayout>
      <div className="max-w-4xl mx-auto space-y-6">
        <div>
          <h1 className="font-display text-2xl font-semibold">Activity Log</h1>
          <p className="text-sm text-muted-foreground mt-1">Track all actions performed in the admin system</p>
        </div>

        <div className="flex flex-col sm:flex-row gap-3">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search activity..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
          </div>
          <button className="luxury-btn-outline text-xs gap-2"><Filter className="w-3.5 h-3.5" /> Filter</button>
        </div>

        <div className="relative">
          {/* Timeline line */}
          <div className="absolute left-5 top-0 bottom-0 w-px bg-border" />

          <div className="space-y-1">
            {logs.filter(l => l.action.toLowerCase().includes(search.toLowerCase()) || l.user.toLowerCase().includes(search.toLowerCase()) || l.target.toLowerCase().includes(search.toLowerCase())).map((log) => (
              <div key={log.id} className="relative flex items-start gap-4 p-4 hover:bg-muted/20 rounded-sm transition-colors">
                <div className={`w-10 h-10 rounded-full border-2 border-background bg-card flex items-center justify-center z-10 flex-shrink-0 ${log.color}`}>
                  <log.icon className="w-4 h-4" />
                </div>
                <div className="flex-1 min-w-0 pt-1">
                  <p className="text-sm">
                    <span className="font-medium">{log.user}</span>{" "}
                    <span className="text-muted-foreground">{log.action}</span>{" "}
                    <span className="font-medium">{log.target}</span>
                  </p>
                  <p className="text-xs text-muted-foreground mt-0.5">{log.time}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
