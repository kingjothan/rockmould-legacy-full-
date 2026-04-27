import { AdminLayout } from "@/components/layout/AdminLayout";
import { ShieldCheck, CheckCircle, XCircle, Eye, Clock } from "lucide-react";

const kycRequests = [
  { id: "1", name: "Emeka Obi", phone: "+234 805 678 9012", email: "emeka@example.com", date: "March 12, 2026", project: "Azure Towers", status: "Pending" },
  { id: "2", name: "Aisha Mohammed", phone: "+234 806 789 0123", email: "aisha@example.com", date: "March 11, 2026", project: "Crown Heights", status: "Pending" },
  { id: "3", name: "David Adekunle", phone: "+234 807 890 1234", email: "david@example.com", date: "March 10, 2026", project: "Meridian Plaza", status: "Pending" },
  { id: "4", name: "Grace Okonkwo", phone: "+234 808 901 2345", email: "grace@example.com", date: "March 8, 2026", project: "Azure Towers", status: "Approved" },
  { id: "5", name: "Yusuf Bello", phone: "+234 809 012 3456", email: "yusuf@example.com", date: "March 7, 2026", project: "Crown Heights", status: "Rejected" },
];

export default function AdminKYC() {
  return (
    <AdminLayout>
      <div className="max-w-7xl mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="font-display text-2xl font-semibold">KYC Requests</h1>
          <div className="flex items-center gap-2 text-sm text-muted-foreground">
            <span className="flex items-center gap-1"><Clock className="w-4 h-4 text-amber-400" />{kycRequests.filter(k => k.status === "Pending").length} pending</span>
          </div>
        </div>
        <div className="space-y-3">
          {kycRequests.map((k) => (
            <div key={k.id} className="luxury-card flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div className="flex items-center gap-4">
                <div className={`w-10 h-10 rounded-full flex items-center justify-center ${k.status === "Pending" ? "bg-amber-500/10" : k.status === "Approved" ? "bg-emerald-500/10" : "bg-destructive/10"}`}>
                  <ShieldCheck className={`w-4 h-4 ${k.status === "Pending" ? "text-amber-400" : k.status === "Approved" ? "text-emerald-400" : "text-destructive"}`} />
                </div>
                <div>
                  <p className="font-medium">{k.name}</p>
                  <p className="text-xs text-muted-foreground">{k.phone} • {k.email}</p>
                  <p className="text-xs text-muted-foreground mt-0.5">Interest: {k.project} • Submitted: {k.date}</p>
                </div>
              </div>
              <div className="flex items-center gap-2">
                <span className={`status-badge ${k.status === "Pending" ? "status-pending" : k.status === "Approved" ? "status-active" : "status-inactive"}`}>{k.status}</span>
                {k.status === "Pending" && (
                  <>
                    <button className="p-2 text-muted-foreground hover:text-foreground" title="View"><Eye className="w-4 h-4" /></button>
                    <button className="p-2 text-emerald-400 hover:text-emerald-300" title="Approve"><CheckCircle className="w-4 h-4" /></button>
                    <button className="p-2 text-destructive hover:text-destructive/80" title="Reject"><XCircle className="w-4 h-4" /></button>
                  </>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>
    </AdminLayout>
  );
}
