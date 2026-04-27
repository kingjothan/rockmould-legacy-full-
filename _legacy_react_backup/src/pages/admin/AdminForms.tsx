import { AdminLayout } from "@/components/layout/AdminLayout";
import { MessageSquare, Search, Filter, Mail, Phone, Eye, Archive, CheckCircle, Clock } from "lucide-react";
import { useState } from "react";

const submissions = [
  { id: 1, type: "Contact", name: "John Doe", email: "john@email.com", phone: "+234 801 234 5678", subject: "Project inquiry for Victoria Island", date: "March 10, 2026", status: "New" },
  { id: 2, type: "Consultation", name: "Sarah Ibrahim", email: "sarah@email.com", phone: "+234 802 345 6789", subject: "Investment opportunities in Lekki", date: "March 9, 2026", status: "Responded" },
  { id: 3, type: "Partnership", name: "Alpha Construction Ltd", email: "info@alpha.ng", phone: "+234 803 456 7890", subject: "Joint venture proposal", date: "March 8, 2026", status: "New" },
  { id: 4, type: "Support", name: "Adebayo Ogunlesi", email: "adebayo@email.com", phone: "+234 804 567 8901", subject: "Portal access issue", date: "March 7, 2026", status: "Resolved" },
  { id: 5, type: "Newsletter", name: "Grace Okafor", email: "grace@email.com", phone: "—", subject: "Newsletter subscription", date: "March 6, 2026", status: "New" },
];

const statusColor: Record<string, string> = {
  New: "text-blue-400 bg-blue-400/10",
  Responded: "text-amber-400 bg-amber-400/10",
  Resolved: "text-emerald-400 bg-emerald-400/10",
};

const typeColor: Record<string, string> = {
  Contact: "text-primary bg-primary/10",
  Consultation: "text-blue-400 bg-blue-400/10",
  Partnership: "text-purple-400 bg-purple-400/10",
  Support: "text-amber-400 bg-amber-400/10",
  Newsletter: "text-emerald-400 bg-emerald-400/10",
};

export default function AdminForms() {
  const [search, setSearch] = useState("");

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Form Submissions</h1>
            <p className="text-sm text-muted-foreground mt-1">Contact forms, consultations, partnerships, and support inquiries</p>
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
          {[
            { label: "Total", value: "156", icon: MessageSquare },
            { label: "New", value: "12", icon: Clock },
            { label: "Responded", value: "89", icon: CheckCircle },
            { label: "This Month", value: "23", icon: Mail },
          ].map((c) => (
            <div key={c.label} className="luxury-card flex items-center gap-3">
              <div className="w-10 h-10 rounded-sm bg-primary/10 flex items-center justify-center"><c.icon className="w-5 h-5 text-primary" /></div>
              <div><p className="text-[10px] uppercase tracking-wider text-muted-foreground">{c.label}</p><p className="font-display text-xl font-semibold">{c.value}</p></div>
            </div>
          ))}
        </div>

        <div className="luxury-card">
          <div className="flex flex-col sm:flex-row gap-3 mb-6">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search submissions..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
            </div>
            <button className="luxury-btn-outline text-xs gap-2"><Filter className="w-3.5 h-3.5" /> Filter</button>
          </div>

          <div className="space-y-3">
            {submissions.filter(s => s.name.toLowerCase().includes(search.toLowerCase()) || s.subject.toLowerCase().includes(search.toLowerCase())).map((s) => (
              <div key={s.id} className="flex items-center gap-4 p-4 border border-border/50 rounded-sm hover:bg-muted/30 transition-colors group">
                <div className="w-10 h-10 rounded-full bg-muted flex items-center justify-center text-xs font-bold flex-shrink-0">{s.name[0]}</div>
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2">
                    <p className="text-sm font-medium">{s.name}</p>
                    <span className={`text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-sm ${typeColor[s.type]}`}>{s.type}</span>
                  </div>
                  <p className="text-xs text-muted-foreground mt-0.5 truncate">{s.subject}</p>
                  <div className="flex gap-3 text-xs text-muted-foreground mt-1">
                    <span className="flex items-center gap-1"><Mail className="w-3 h-3" />{s.email}</span>
                    {s.phone !== "—" && <span className="flex items-center gap-1"><Phone className="w-3 h-3" />{s.phone}</span>}
                  </div>
                </div>
                <span className="text-xs text-muted-foreground hidden sm:inline">{s.date}</span>
                <span className={`text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm ${statusColor[s.status]}`}>{s.status}</span>
                <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Eye className="w-3.5 h-3.5 text-muted-foreground" /></button>
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Archive className="w-3.5 h-3.5 text-muted-foreground" /></button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
