import { PortalLayout } from "@/components/layout/PortalLayout";
import { Link } from "react-router-dom";
import { MapPin, ArrowRight } from "lucide-react";

const projects = [
  { id: "1", name: "Azure Towers - Unit 14B", location: "Victoria Island, Lagos", status: "Ongoing", progress: 45, paid: "₦18.5M", outstanding: "₦6.5M", lastUpdate: "March 10, 2026" },
  { id: "2", name: "Meridian Plaza - Office 302", location: "Ikoyi, Lagos", status: "Completed", progress: 100, paid: "₦22.0M", outstanding: "₦0", lastUpdate: "January 15, 2026" },
  { id: "3", name: "Crown Heights - Unit 8A", location: "Lekki Phase 1, Lagos", status: "Upcoming", progress: 10, paid: "₦4.7M", outstanding: "₦6.3M", lastUpdate: "March 8, 2026" },
];

export default function PortalProjects() {
  return (
    <PortalLayout>
      <div className="max-w-6xl mx-auto">
        <h1 className="font-display text-2xl font-semibold mb-6">My Projects</h1>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {projects.map((p) => (
            <div key={p.id} className="luxury-card group">
              <div className="aspect-[16/9] bg-muted rounded-sm mb-4" />
              <span className={`text-[10px] uppercase tracking-wider ${p.status === "Completed" ? "text-primary" : p.status === "Ongoing" ? "text-emerald-400" : "text-amber-400"}`}>{p.status}</span>
              <h3 className="font-display text-lg font-semibold mt-1 mb-2">{p.name}</h3>
              <p className="text-xs text-muted-foreground flex items-center gap-1 mb-4"><MapPin className="w-3 h-3" />{p.location}</p>
              <div className="h-1.5 bg-muted rounded-full overflow-hidden mb-3">
                <div className="h-full bg-primary rounded-full transition-all" style={{ width: `${p.progress}%` }} />
              </div>
              <div className="flex justify-between text-xs text-muted-foreground mb-4">
                <span>{p.progress}% complete</span>
                <span>Updated: {p.lastUpdate}</span>
              </div>
              <div className="luxury-divider mb-4" />
              <div className="flex justify-between text-xs mb-4">
                <div><span className="text-muted-foreground">Paid</span><p className="font-semibold">{p.paid}</p></div>
                <div className="text-right"><span className="text-muted-foreground">Outstanding</span><p className="font-semibold text-amber-400">{p.outstanding}</p></div>
              </div>
              <button className="luxury-btn-outline w-full text-xs py-2">View Details <ArrowRight className="w-3 h-3" /></button>
            </div>
          ))}
        </div>
      </div>
    </PortalLayout>
  );
}
