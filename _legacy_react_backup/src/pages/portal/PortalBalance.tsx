import { PortalLayout } from "@/components/layout/PortalLayout";
import { Receipt, ArrowDownCircle, ArrowUpCircle, Download } from "lucide-react";

const payments = [
  { date: "Mar 5, 2026", desc: "Installment Payment - Azure Towers", amount: "₦2,500,000", type: "credit" as const, project: "Azure Towers" },
  { date: "Feb 1, 2026", desc: "Installment Payment - Crown Heights", amount: "₦1,700,000", type: "credit" as const, project: "Crown Heights" },
  { date: "Jan 15, 2026", desc: "Final Payment - Meridian Plaza", amount: "₦5,000,000", type: "credit" as const, project: "Meridian Plaza" },
  { date: "Dec 1, 2025", desc: "Installment Payment - Azure Towers", amount: "₦2,500,000", type: "credit" as const, project: "Azure Towers" },
  { date: "Nov 1, 2025", desc: "Initial Deposit - Crown Heights", amount: "₦3,000,000", type: "credit" as const, project: "Crown Heights" },
];

export default function PortalBalance() {
  return (
    <PortalLayout>
      <div className="max-w-6xl mx-auto space-y-8">
        <h1 className="font-display text-2xl font-semibold">Balance Sheet</h1>

        {/* Summary */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div className="luxury-card"><p className="text-xs text-muted-foreground uppercase tracking-wider mb-1">Total Project Value</p><p className="font-display text-2xl font-bold">₦58,000,000</p></div>
          <div className="luxury-card"><p className="text-xs text-muted-foreground uppercase tracking-wider mb-1">Total Paid</p><p className="font-display text-2xl font-bold text-primary">₦45,200,000</p></div>
          <div className="luxury-card"><p className="text-xs text-muted-foreground uppercase tracking-wider mb-1">Outstanding Balance</p><p className="font-display text-2xl font-bold text-amber-400">₦12,800,000</p></div>
        </div>

        {/* Payment History */}
        <div className="luxury-card">
          <div className="flex items-center justify-between mb-6">
            <h2 className="font-display text-lg font-semibold">Payment History</h2>
            <button className="luxury-btn-outline text-xs py-2"><Download className="w-3 h-3" /> Download Statement</button>
          </div>
          <div className="space-y-0">
            {payments.map((p, i) => (
              <div key={i} className={`flex items-center justify-between py-4 ${i > 0 ? "border-t border-border" : ""}`}>
                <div className="flex items-center gap-4">
                  <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                    <ArrowDownCircle className="w-4 h-4 text-primary" />
                  </div>
                  <div>
                    <p className="text-sm font-medium">{p.desc}</p>
                    <p className="text-xs text-muted-foreground">{p.date} • {p.project}</p>
                  </div>
                </div>
                <span className="font-semibold text-sm text-primary">{p.amount}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </PortalLayout>
  );
}
