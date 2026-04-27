import { AdminLayout } from "@/components/layout/AdminLayout";
import { motion } from "framer-motion";
import { Users, FolderKanban, ShieldCheck, Receipt, FileText, Clock, ArrowRight, AlertTriangle } from "lucide-react";
import { Link } from "react-router-dom";

const stats = [
  { icon: Users, label: "Total Clients", value: "142", change: "+5 this month" },
  { icon: FolderKanban, label: "Active Projects", value: "18", change: "3 upcoming" },
  { icon: ShieldCheck, label: "Pending KYC", value: "7", change: "Needs review", alert: true },
  { icon: Receipt, label: "Outstanding Payments", value: "₦234M", change: "12 clients" },
];

const recentActivity = [
  { text: "New KYC request from John Doe", time: "10 minutes ago", type: "kyc" },
  { text: "Payment of ₦5M received - Azure Towers", time: "1 hour ago", type: "payment" },
  { text: "Document uploaded: Offer Letter - Crown Heights", time: "2 hours ago", type: "document" },
  { text: "Project update: Meridian Plaza at 100%", time: "3 hours ago", type: "project" },
  { text: "New contact form submission", time: "5 hours ago", type: "form" },
];

export default function AdminDashboard() {
  return (
    <AdminLayout>
      <div className="max-w-7xl mx-auto space-y-8">
        <div>
          <h1 className="font-display text-2xl font-semibold mb-1">Dashboard Overview</h1>
          <p className="text-sm text-muted-foreground">Welcome back. Here's what's happening today.</p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {stats.map((s, i) => (
            <motion.div key={s.label} initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.1 }} className="luxury-card">
              <div className="flex items-center justify-between mb-3">
                <s.icon className="w-5 h-5 text-primary" />
                {s.alert && <AlertTriangle className="w-4 h-4 text-amber-400" />}
              </div>
              <p className="font-display text-2xl font-bold">{s.value}</p>
              <p className="text-xs text-muted-foreground mt-1">{s.label}</p>
              <p className={`text-xs mt-2 ${s.alert ? "text-amber-400" : "text-primary"}`}>{s.change}</p>
            </motion.div>
          ))}
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div className="lg:col-span-2 luxury-card">
            <div className="flex items-center justify-between mb-6">
              <h2 className="font-display text-lg font-semibold">Recent Activity</h2>
              <Link to="/admin/activity" className="text-xs text-primary hover:underline">View All</Link>
            </div>
            <div className="space-y-0">
              {recentActivity.map((a, i) => (
                <div key={i} className={`flex items-start gap-3 py-3 ${i > 0 ? "border-t border-border" : ""}`}>
                  <Clock className="w-4 h-4 text-muted-foreground mt-0.5 flex-shrink-0" />
                  <div><p className="text-sm">{a.text}</p><p className="text-xs text-muted-foreground">{a.time}</p></div>
                </div>
              ))}
            </div>
          </div>

          <div className="luxury-card">
            <h2 className="font-display text-lg font-semibold mb-6">Quick Actions</h2>
            <div className="space-y-2">
              {[
                { label: "Review KYC Requests", path: "/admin/kyc", icon: ShieldCheck },
                { label: "Add New Client", path: "/admin/clients", icon: Users },
                { label: "Create Project", path: "/admin/projects", icon: FolderKanban },
                { label: "Upload Documents", path: "/admin/documents", icon: FileText },
                { label: "Record Payment", path: "/admin/payments", icon: Receipt },
              ].map((a) => (
                <Link key={a.label} to={a.path} className="flex items-center gap-3 p-3 rounded-sm text-sm text-muted-foreground hover:text-foreground hover:bg-muted/50 transition-colors">
                  <a.icon className="w-4 h-4 text-primary" />{a.label}<ArrowRight className="w-3 h-3 ml-auto" />
                </Link>
              ))}
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
