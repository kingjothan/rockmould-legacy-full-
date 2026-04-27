import { AdminLayout } from "@/components/layout/AdminLayout";
import { useState } from "react";
import { Search, Plus, MoreHorizontal, Eye, Edit, Trash2 } from "lucide-react";

const clients = [
  { id: "1", name: "Adebayo Ogunleye", phone: "+234 801 234 5678", email: "adebayo@example.com", status: "Active", projects: 3, paid: "₦45.2M", outstanding: "₦12.8M" },
  { id: "2", name: "Chioma Nwankwo", phone: "+234 802 345 6789", email: "chioma@example.com", status: "Active", projects: 2, paid: "₦28.0M", outstanding: "₦5.0M" },
  { id: "3", name: "Ibrahim Musa", phone: "+234 803 456 7890", email: "ibrahim@example.com", status: "Active", projects: 1, paid: "₦15.5M", outstanding: "₦9.5M" },
  { id: "4", name: "Folake Bakare", phone: "+234 804 567 8901", email: "folake@example.com", status: "Inactive", projects: 1, paid: "₦22.0M", outstanding: "₦0" },
  { id: "5", name: "Emeka Obi", phone: "+234 805 678 9012", email: "emeka@example.com", status: "Pending", projects: 0, paid: "₦0", outstanding: "₦0" },
];

export default function AdminClients() {
  const [search, setSearch] = useState("");
  const filtered = clients.filter((c) => c.name.toLowerCase().includes(search.toLowerCase()) || c.email.toLowerCase().includes(search.toLowerCase()));

  return (
    <AdminLayout>
      <div className="max-w-7xl mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="font-display text-2xl font-semibold">Client Management</h1>
          <button className="luxury-btn-primary text-xs py-2"><Plus className="w-4 h-4" /> Add Client</button>
        </div>
        <div className="luxury-card p-0 overflow-hidden">
          <div className="p-4 border-b border-border">
            <div className="relative w-full max-w-sm">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search clients..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary/50" />
            </div>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead><tr className="border-b border-border text-xs uppercase tracking-wider text-muted-foreground">
                <th className="text-left px-4 py-3">Name</th><th className="text-left px-4 py-3">Phone</th><th className="text-left px-4 py-3">Status</th><th className="text-left px-4 py-3">Projects</th><th className="text-right px-4 py-3">Paid</th><th className="text-right px-4 py-3">Outstanding</th><th className="text-right px-4 py-3">Actions</th>
              </tr></thead>
              <tbody>
                {filtered.map((c) => (
                  <tr key={c.id} className="border-b border-border hover:bg-muted/30 transition-colors">
                    <td className="px-4 py-3"><p className="font-medium">{c.name}</p><p className="text-xs text-muted-foreground">{c.email}</p></td>
                    <td className="px-4 py-3 text-muted-foreground">{c.phone}</td>
                    <td className="px-4 py-3"><span className={`status-badge ${c.status === "Active" ? "status-active" : c.status === "Pending" ? "status-pending" : "status-inactive"}`}>{c.status}</span></td>
                    <td className="px-4 py-3">{c.projects}</td>
                    <td className="px-4 py-3 text-right text-primary">{c.paid}</td>
                    <td className="px-4 py-3 text-right">{c.outstanding}</td>
                    <td className="px-4 py-3 text-right">
                      <div className="flex items-center justify-end gap-1">
                        <button className="p-1.5 text-muted-foreground hover:text-foreground"><Eye className="w-4 h-4" /></button>
                        <button className="p-1.5 text-muted-foreground hover:text-foreground"><Edit className="w-4 h-4" /></button>
                      </div>
                    </td>
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
