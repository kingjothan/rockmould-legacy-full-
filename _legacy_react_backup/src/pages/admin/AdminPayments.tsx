import { AdminLayout } from "@/components/layout/AdminLayout";
import { Receipt, Search, Filter, Download, Plus, ChevronDown } from "lucide-react";
import { useState } from "react";

const payments = [
  { id: "PAY-001", client: "Adebayo Ogunlesi", project: "Azure Towers - Unit 14B", amount: "₦5,000,000", date: "March 10, 2026", method: "Bank Transfer", status: "Confirmed" },
  { id: "PAY-002", client: "Folake Adeyemi", project: "Meridian Plaza - Office 302", amount: "₦3,200,000", date: "March 8, 2026", method: "Bank Transfer", status: "Confirmed" },
  { id: "PAY-003", client: "Chidi Okwuosa", project: "Crown Heights - Unit 8A", amount: "₦2,500,000", date: "March 5, 2026", method: "Wire Transfer", status: "Pending" },
  { id: "PAY-004", client: "Ngozi Eze", project: "Azure Towers - Unit 7C", amount: "₦4,700,000", date: "March 1, 2026", method: "Bank Transfer", status: "Confirmed" },
  { id: "PAY-005", client: "Adebayo Ogunlesi", project: "Azure Towers - Unit 14B", amount: "₦6,000,000", date: "Feb 20, 2026", method: "Bank Transfer", status: "Confirmed" },
];

const statusColor: Record<string, string> = {
  Confirmed: "text-emerald-400 bg-emerald-400/10",
  Pending: "text-amber-400 bg-amber-400/10",
  Failed: "text-destructive bg-destructive/10",
};

export default function AdminPayments() {
  const [search, setSearch] = useState("");

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Payments</h1>
            <p className="text-sm text-muted-foreground mt-1">Track and manage all client payments</p>
          </div>
          <div className="flex gap-2">
            <button className="luxury-btn-outline text-xs gap-2"><Download className="w-3.5 h-3.5" /> Export</button>
            <button className="luxury-btn-primary text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Record Payment</button>
          </div>
        </div>

        {/* Summary Cards */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {[
            { label: "Total Received", value: "₦245,800,000", sub: "All time" },
            { label: "This Month", value: "₦15,400,000", sub: "March 2026" },
            { label: "Pending", value: "₦2,500,000", sub: "1 transaction" },
            { label: "Outstanding", value: "₦38,200,000", sub: "Across 12 clients" },
          ].map((c) => (
            <div key={c.label} className="luxury-card">
              <p className="text-[10px] uppercase tracking-wider text-muted-foreground">{c.label}</p>
              <p className="font-display text-xl font-semibold mt-1">{c.value}</p>
              <p className="text-xs text-muted-foreground mt-1">{c.sub}</p>
            </div>
          ))}
        </div>

        {/* Filters */}
        <div className="luxury-card">
          <div className="flex flex-col sm:flex-row gap-3 mb-6">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search payments..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
            </div>
            <button className="luxury-btn-outline text-xs gap-2"><Filter className="w-3.5 h-3.5" /> Filter <ChevronDown className="w-3 h-3" /></button>
          </div>

          {/* Table */}
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border text-left">
                  <th className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium">ID</th>
                  <th className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium">Client</th>
                  <th className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium">Project</th>
                  <th className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium">Amount</th>
                  <th className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium">Date</th>
                  <th className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium">Method</th>
                  <th className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium">Status</th>
                </tr>
              </thead>
              <tbody>
                {payments.filter(p => p.client.toLowerCase().includes(search.toLowerCase()) || p.project.toLowerCase().includes(search.toLowerCase())).map((p) => (
                  <tr key={p.id} className="border-b border-border/50 hover:bg-muted/30 transition-colors">
                    <td className="py-3 font-mono text-xs text-muted-foreground">{p.id}</td>
                    <td className="py-3 font-medium">{p.client}</td>
                    <td className="py-3 text-muted-foreground">{p.project}</td>
                    <td className="py-3 font-semibold">{p.amount}</td>
                    <td className="py-3 text-muted-foreground">{p.date}</td>
                    <td className="py-3 text-muted-foreground">{p.method}</td>
                    <td className="py-3"><span className={`text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm ${statusColor[p.status]}`}>{p.status}</span></td>
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
