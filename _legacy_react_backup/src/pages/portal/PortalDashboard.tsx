import { PortalLayout } from "@/components/layout/PortalLayout";
import { motion } from "framer-motion";
import { FolderKanban, FileText, Receipt, Clock, ArrowRight, TrendingUp } from "lucide-react";
import { Link } from "react-router-dom";

export default function PortalDashboard() {
  return (
    <PortalLayout>
      <div className="max-w-6xl mx-auto space-y-8">
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
          <h1 className="font-display text-2xl font-semibold mb-1">Welcome Back, Adebayo</h1>
          <p className="text-sm text-muted-foreground">Here's an overview of your projects and activity.</p>
        </motion.div>

        {/* Stats */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          {[
            { icon: FolderKanban, label: "Active Projects", value: "3", color: "text-emerald-400" },
            { icon: Receipt, label: "Amount Paid", value: "₦45.2M", color: "text-primary" },
            { icon: TrendingUp, label: "Outstanding", value: "₦12.8M", color: "text-amber-400" },
            { icon: FileText, label: "Documents", value: "24", color: "text-blue-400" },
          ].map((s, i) => (
            <motion.div key={s.label} initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.1 }} className="luxury-card">
              <s.icon className={`w-5 h-5 ${s.color} mb-3`} />
              <p className="font-display text-2xl font-bold">{s.value}</p>
              <p className="text-xs text-muted-foreground mt-1">{s.label}</p>
            </motion.div>
          ))}
        </div>

        {/* Projects */}
        <div>
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-display text-lg font-semibold">My Projects</h2>
            <Link to="/portal/projects" className="text-xs text-primary hover:underline flex items-center gap-1">View All <ArrowRight className="w-3 h-3" /></Link>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {[
              { name: "Azure Towers - Unit 14B", status: "Ongoing", progress: 45, paid: "₦18.5M", outstanding: "₦6.5M" },
              { name: "Meridian Plaza - Office 302", status: "Completed", progress: 100, paid: "₦22.0M", outstanding: "₦0" },
              { name: "Crown Heights - Unit 8A", status: "Upcoming", progress: 10, paid: "₦4.7M", outstanding: "₦6.3M" },
            ].map((p) => (
              <Link key={p.name} to="/portal/projects" className="luxury-card group">
                <div className="flex items-center justify-between mb-3">
                  <span className={`text-[10px] uppercase tracking-wider ${p.status === "Completed" ? "text-primary" : p.status === "Ongoing" ? "text-emerald-400" : "text-amber-400"}`}>{p.status}</span>
                  <span className="text-xs text-muted-foreground">{p.progress}%</span>
                </div>
                <h3 className="font-semibold text-sm mb-3 group-hover:text-primary transition-colors">{p.name}</h3>
                <div className="h-1.5 bg-muted rounded-full overflow-hidden mb-4">
                  <div className="h-full bg-primary rounded-full" style={{ width: `${p.progress}%` }} />
                </div>
                <div className="flex justify-between text-xs text-muted-foreground">
                  <span>Paid: {p.paid}</span>
                  <span>Due: {p.outstanding}</span>
                </div>
              </Link>
            ))}
          </div>
        </div>

        {/* Recent */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div className="luxury-card">
            <h3 className="font-display text-lg font-semibold mb-4">Recent Updates</h3>
            <div className="space-y-4">
              {[
                { text: "Azure Towers: Foundation work 100% complete", date: "March 10, 2026" },
                { text: "New document uploaded: Offer Letter - Crown Heights", date: "March 8, 2026" },
                { text: "Payment received: ₦2.5M for Azure Towers", date: "March 5, 2026" },
              ].map((u) => (
                <div key={u.text} className="flex items-start gap-3">
                  <Clock className="w-4 h-4 text-primary mt-0.5 flex-shrink-0" />
                  <div>
                    <p className="text-sm">{u.text}</p>
                    <p className="text-xs text-muted-foreground">{u.date}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
          <div className="luxury-card">
            <h3 className="font-display text-lg font-semibold mb-4">Recent Documents</h3>
            <div className="space-y-3">
              {["Offer Letter - Azure Towers", "Receipt - March 2026 Payment", "Agreement - Crown Heights"].map((d) => (
                <div key={d} className="flex items-center justify-between p-3 bg-muted/30 rounded-sm">
                  <div className="flex items-center gap-3"><FileText className="w-4 h-4 text-primary" /><span className="text-sm">{d}</span></div>
                  <button className="text-xs text-primary hover:underline">Download</button>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </PortalLayout>
  );
}
