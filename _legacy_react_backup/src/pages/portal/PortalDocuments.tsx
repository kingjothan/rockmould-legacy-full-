import { PortalLayout } from "@/components/layout/PortalLayout";
import { FileText, Download, Search, Eye } from "lucide-react";
import { useState } from "react";

const docs = [
  { name: "Offer Letter - Azure Towers Unit 14B", type: "Offer Letter", project: "Azure Towers", date: "Jan 15, 2025", size: "245 KB" },
  { name: "Payment Receipt - March 2026", type: "Receipt", project: "Azure Towers", date: "Mar 5, 2026", size: "120 KB" },
  { name: "Agreement - Crown Heights Unit 8A", type: "Agreement", project: "Crown Heights", date: "Feb 20, 2026", size: "1.2 MB" },
  { name: "Invoice - Q1 2026 Installment", type: "Invoice", project: "Azure Towers", date: "Mar 1, 2026", size: "98 KB" },
  { name: "Completion Certificate - Meridian Plaza", type: "Certificate", project: "Meridian Plaza", date: "Jan 10, 2026", size: "340 KB" },
  { name: "Statement of Account - Feb 2026", type: "Statement", project: "All Projects", date: "Feb 28, 2026", size: "180 KB" },
];

export default function PortalDocuments() {
  const [search, setSearch] = useState("");
  const filtered = docs.filter((d) => d.name.toLowerCase().includes(search.toLowerCase()));

  return (
    <PortalLayout>
      <div className="max-w-6xl mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="font-display text-2xl font-semibold">Documents</h1>
          <div className="relative w-64">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search documents..." className="w-full bg-card border border-border rounded-sm pl-10 pr-4 py-2 text-sm focus:outline-none focus:border-primary/50" />
          </div>
        </div>

        {filtered.length === 0 ? (
          <div className="luxury-card text-center py-16"><FileText className="w-10 h-10 text-muted-foreground mx-auto mb-3" /><p className="text-muted-foreground">No documents found.</p></div>
        ) : (
          <div className="space-y-2">
            {filtered.map((d) => (
              <div key={d.name} className="luxury-card flex items-center justify-between py-4">
                <div className="flex items-center gap-4">
                  <div className="w-10 h-10 rounded-md bg-primary/10 flex items-center justify-center"><FileText className="w-4 h-4 text-primary" /></div>
                  <div>
                    <p className="text-sm font-medium">{d.name}</p>
                    <div className="flex items-center gap-3 text-xs text-muted-foreground mt-0.5">
                      <span>{d.type}</span><span>•</span><span>{d.project}</span><span>•</span><span>{d.date}</span><span>•</span><span>{d.size}</span>
                    </div>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <button className="p-2 text-muted-foreground hover:text-primary transition-colors"><Eye className="w-4 h-4" /></button>
                  <button className="p-2 text-muted-foreground hover:text-primary transition-colors"><Download className="w-4 h-4" /></button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </PortalLayout>
  );
}
