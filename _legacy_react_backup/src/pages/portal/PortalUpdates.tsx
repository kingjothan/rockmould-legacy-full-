import { PortalLayout } from "@/components/layout/PortalLayout";
import { Clock, Image, CheckCircle, Circle, MapPin } from "lucide-react";

const updates = [
  { id: 1, project: "Azure Towers - Unit 14B", date: "March 10, 2026", title: "Foundation work completed for Block C", description: "The foundation for Block C has been successfully completed. Structural work will begin next week.", progress: 48, hasMedia: true, milestone: true },
  { id: 2, project: "Azure Towers - Unit 14B", date: "March 3, 2026", title: "Structural framework for floors 1-5 complete", description: "Steel and concrete framework for the first five floors is now in place. Electrical conduit installation has started.", progress: 45, hasMedia: true, milestone: false },
  { id: 3, project: "Crown Heights - Unit 8A", date: "March 8, 2026", title: "Site clearing and grading in progress", description: "Heavy machinery has been deployed for land clearing. Grading is expected to complete within two weeks.", progress: 12, hasMedia: false, milestone: true },
  { id: 4, project: "Azure Towers - Unit 14B", date: "Feb 25, 2026", title: "Building permits approved", description: "All necessary permits have been approved by the Lagos State Building Control Authority.", progress: 40, hasMedia: false, milestone: true },
  { id: 5, project: "Meridian Plaza - Office 302", date: "Jan 15, 2026", title: "Final handover completed", description: "Keys and all documentation have been handed over. Congratulations on your new property!", progress: 100, hasMedia: true, milestone: true },
  { id: 6, project: "Crown Heights - Unit 8A", date: "Feb 20, 2026", title: "Architectural drawings approved", description: "Final architectural plans approved by the client and regulatory authorities.", progress: 10, hasMedia: false, milestone: false },
];

export default function PortalUpdates() {
  return (
    <PortalLayout>
      <div className="max-w-3xl mx-auto space-y-6">
        <h1 className="font-display text-2xl font-semibold">Project Updates</h1>
        <p className="text-sm text-muted-foreground">Chronological updates across all your projects</p>

        <div className="relative">
          <div className="absolute left-5 top-0 bottom-0 w-px bg-border" />

          <div className="space-y-6">
            {updates.map((u) => (
              <div key={u.id} className="relative flex gap-4">
                <div className={`w-10 h-10 rounded-full border-2 border-background flex items-center justify-center z-10 flex-shrink-0 ${u.milestone ? "bg-primary/10" : "bg-card"}`}>
                  {u.progress === 100 ? (
                    <CheckCircle className="w-5 h-5 text-primary" />
                  ) : u.milestone ? (
                    <Clock className="w-4 h-4 text-primary" />
                  ) : (
                    <Circle className="w-3 h-3 text-muted-foreground" />
                  )}
                </div>
                <div className="flex-1 luxury-card">
                  <div className="flex items-start justify-between gap-2 mb-2">
                    <span className="text-[10px] uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded-sm">{u.project}</span>
                    <span className="text-xs text-muted-foreground flex-shrink-0">{u.date}</span>
                  </div>
                  <h3 className="text-sm font-semibold mb-1">{u.title}</h3>
                  <p className="text-xs text-muted-foreground leading-relaxed">{u.description}</p>
                  <div className="flex items-center gap-4 mt-3">
                    <div className="flex items-center gap-2 flex-1">
                      <div className="h-1.5 bg-muted rounded-full flex-1">
                        <div className="h-full bg-primary rounded-full" style={{ width: `${u.progress}%` }} />
                      </div>
                      <span className="text-xs text-muted-foreground">{u.progress}%</span>
                    </div>
                    {u.hasMedia && (
                      <span className="text-[10px] text-muted-foreground flex items-center gap-1"><Image className="w-3 h-3" /> Photos</span>
                    )}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </PortalLayout>
  );
}
