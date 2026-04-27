import { AdminLayout } from "@/components/layout/AdminLayout";
import { Briefcase, Plus, Edit, Trash2, Search, MapPin, Clock } from "lucide-react";
import { useState } from "react";

const jobs = [
  { id: 1, title: "Senior Project Manager", department: "Construction", location: "Lagos", type: "Full-time", status: "Active", applicants: 12, posted: "March 1, 2026" },
  { id: 2, title: "Architect", department: "Design", location: "Lagos", type: "Full-time", status: "Active", applicants: 8, posted: "Feb 20, 2026" },
  { id: 3, title: "Sales Executive", department: "Sales", location: "Lagos", type: "Full-time", status: "Active", applicants: 24, posted: "Feb 15, 2026" },
  { id: 4, title: "Site Engineer", department: "Construction", location: "Lagos", type: "Contract", status: "Closed", applicants: 15, posted: "Jan 10, 2026" },
  { id: 5, title: "Marketing Manager", department: "Marketing", location: "Remote", type: "Full-time", status: "Draft", applicants: 0, posted: "—" },
];

const statusColor: Record<string, string> = {
  Active: "text-emerald-400 bg-emerald-400/10",
  Closed: "text-muted-foreground bg-muted",
  Draft: "text-amber-400 bg-amber-400/10",
};

export default function AdminCMSCareers() {
  const [search, setSearch] = useState("");

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Careers CMS</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage job openings and applications</p>
          </div>
          <button className="luxury-btn-primary text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Post New Job</button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div className="luxury-card text-center"><p className="text-[10px] uppercase tracking-wider text-muted-foreground">Active Openings</p><p className="font-display text-2xl font-semibold mt-1">3</p></div>
          <div className="luxury-card text-center"><p className="text-[10px] uppercase tracking-wider text-muted-foreground">Total Applicants</p><p className="font-display text-2xl font-semibold mt-1">59</p></div>
          <div className="luxury-card text-center"><p className="text-[10px] uppercase tracking-wider text-muted-foreground">Positions Filled</p><p className="font-display text-2xl font-semibold mt-1">8</p></div>
        </div>

        <div className="luxury-card">
          <div className="relative mb-6">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search positions..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
          </div>

          <div className="space-y-3">
            {jobs.filter(j => j.title.toLowerCase().includes(search.toLowerCase())).map((job) => (
              <div key={job.id} className="flex items-center gap-4 p-4 border border-border/50 rounded-sm hover:bg-muted/30 transition-colors group">
                <div className="w-10 h-10 rounded-sm bg-primary/10 flex items-center justify-center flex-shrink-0">
                  <Briefcase className="w-5 h-5 text-primary" />
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium">{job.title}</p>
                  <div className="flex flex-wrap gap-3 text-xs text-muted-foreground mt-1">
                    <span>{job.department}</span>
                    <span className="flex items-center gap-1"><MapPin className="w-3 h-3" />{job.location}</span>
                    <span>{job.type}</span>
                    {job.posted !== "—" && <span className="flex items-center gap-1"><Clock className="w-3 h-3" />{job.posted}</span>}
                  </div>
                </div>
                <div className="text-right hidden sm:block">
                  <p className="text-sm font-semibold">{job.applicants}</p>
                  <p className="text-[10px] text-muted-foreground">applicants</p>
                </div>
                <span className={`text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm ${statusColor[job.status]}`}>{job.status}</span>
                <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Trash2 className="w-3.5 h-3.5 text-destructive" /></button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
