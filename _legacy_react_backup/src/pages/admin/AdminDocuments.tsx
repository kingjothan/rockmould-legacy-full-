import { AdminLayout } from "@/components/layout/AdminLayout";
import { FileText, Search, Upload, Filter, Download, Eye, Trash2, MoreVertical } from "lucide-react";
import { useState } from "react";

const documents = [
  { id: 1, name: "Offer Letter - Azure Towers 14B", type: "Offer Letter", client: "Adebayo Ogunlesi", project: "Azure Towers", date: "March 10, 2026", size: "2.4 MB" },
  { id: 2, name: "Contract Agreement - Meridian Plaza", type: "Contract", client: "Folake Adeyemi", project: "Meridian Plaza", date: "March 8, 2026", size: "5.1 MB" },
  { id: 3, name: "Payment Receipt - PAY-003", type: "Receipt", client: "Chidi Okwuosa", project: "Crown Heights", date: "March 5, 2026", size: "0.8 MB" },
  { id: 4, name: "Invoice - Crown Heights Unit 8A", type: "Invoice", client: "Chidi Okwuosa", project: "Crown Heights", date: "March 1, 2026", size: "1.2 MB" },
  { id: 5, name: "Completion Certificate - Meridian", type: "Certificate", client: "Folake Adeyemi", project: "Meridian Plaza", date: "Feb 15, 2026", size: "3.3 MB" },
];

const typeColor: Record<string, string> = {
  "Offer Letter": "text-blue-400 bg-blue-400/10",
  Contract: "text-primary bg-primary/10",
  Receipt: "text-emerald-400 bg-emerald-400/10",
  Invoice: "text-amber-400 bg-amber-400/10",
  Certificate: "text-purple-400 bg-purple-400/10",
};

export default function AdminDocuments() {
  const [search, setSearch] = useState("");

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Documents</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage client documents, contracts, and certificates</p>
          </div>
          <button className="luxury-btn-primary text-xs gap-2"><Upload className="w-3.5 h-3.5" /> Upload Document</button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
          {[
            { label: "Total Documents", value: "142" },
            { label: "Offer Letters", value: "28" },
            { label: "Contracts", value: "35" },
            { label: "Receipts", value: "79" },
          ].map((c) => (
            <div key={c.label} className="luxury-card text-center">
              <p className="text-[10px] uppercase tracking-wider text-muted-foreground">{c.label}</p>
              <p className="font-display text-2xl font-semibold mt-1">{c.value}</p>
            </div>
          ))}
        </div>

        <div className="luxury-card">
          <div className="flex flex-col sm:flex-row gap-3 mb-6">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search documents..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
            </div>
            <button className="luxury-btn-outline text-xs gap-2"><Filter className="w-3.5 h-3.5" /> Filter</button>
          </div>

          <div className="space-y-3">
            {documents.filter(d => d.name.toLowerCase().includes(search.toLowerCase())).map((doc) => (
              <div key={doc.id} className="flex items-center gap-4 p-4 border border-border/50 rounded-sm hover:bg-muted/30 transition-colors group">
                <div className="w-10 h-10 rounded-sm bg-muted flex items-center justify-center flex-shrink-0">
                  <FileText className="w-5 h-5 text-muted-foreground" />
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium truncate">{doc.name}</p>
                  <p className="text-xs text-muted-foreground">{doc.client} · {doc.project} · {doc.size}</p>
                </div>
                <span className={`text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm hidden sm:inline ${typeColor[doc.type] || "text-muted-foreground bg-muted"}`}>{doc.type}</span>
                <span className="text-xs text-muted-foreground hidden md:inline">{doc.date}</span>
                <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Eye className="w-3.5 h-3.5 text-muted-foreground" /></button>
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Download className="w-3.5 h-3.5 text-muted-foreground" /></button>
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
