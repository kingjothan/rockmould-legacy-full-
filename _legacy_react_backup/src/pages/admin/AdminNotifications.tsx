import { AdminLayout } from "@/components/layout/AdminLayout";
import { Bell, Check, CheckCheck, ShieldCheck, MessageSquare, Receipt, FolderKanban, Clock, Upload } from "lucide-react";

const notifications = [
  { id: 1, type: "kyc", icon: ShieldCheck, title: "New KYC request from Emeka Nwankwo", time: "2 hours ago", read: false },
  { id: 2, type: "contact", icon: MessageSquare, title: "New contact form submission from Sarah Ibrahim", time: "4 hours ago", read: false },
  { id: 3, type: "payment", icon: Receipt, title: "Payment of ₦5,000,000 confirmed for Azure Towers", time: "6 hours ago", read: false },
  { id: 4, type: "project", icon: FolderKanban, title: "Crown Heights milestone updated to 12%", time: "1 day ago", read: true },
  { id: 5, type: "upload", icon: Upload, title: "3 new photos uploaded to Azure Towers gallery", time: "1 day ago", read: true },
  { id: 6, type: "payment", icon: Receipt, title: "Payment overdue for Chidi Okwuosa - Crown Heights", time: "2 days ago", read: true },
  { id: 7, type: "reminder", icon: Clock, title: "Project update reminder: Azure Towers is due for weekly update", time: "3 days ago", read: true },
];

const typeColor: Record<string, string> = {
  kyc: "text-purple-400 bg-purple-400/10",
  contact: "text-blue-400 bg-blue-400/10",
  payment: "text-emerald-400 bg-emerald-400/10",
  project: "text-primary bg-primary/10",
  upload: "text-amber-400 bg-amber-400/10",
  reminder: "text-muted-foreground bg-muted",
};

export default function AdminNotifications() {
  const unread = notifications.filter(n => !n.read).length;

  return (
    <AdminLayout>
      <div className="max-w-3xl mx-auto space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="font-display text-2xl font-semibold">Notifications</h1>
            <p className="text-sm text-muted-foreground mt-1">{unread} unread notification{unread !== 1 ? "s" : ""}</p>
          </div>
          <button className="luxury-btn-outline text-xs gap-2"><CheckCheck className="w-3.5 h-3.5" /> Mark All Read</button>
        </div>

        <div className="space-y-2">
          {notifications.map((n) => (
            <div key={n.id} className={`flex items-start gap-4 p-4 border rounded-sm transition-colors hover:bg-muted/30 ${n.read ? "border-border/30 opacity-70" : "border-border/50 bg-muted/10"}`}>
              <div className={`w-10 h-10 rounded-sm flex items-center justify-center flex-shrink-0 ${typeColor[n.type]}`}>
                <n.icon className="w-5 h-5" />
              </div>
              <div className="flex-1 min-w-0">
                <p className={`text-sm ${n.read ? "text-muted-foreground" : "font-medium"}`}>{n.title}</p>
                <p className="text-xs text-muted-foreground mt-1">{n.time}</p>
              </div>
              {!n.read && (
                <div className="flex items-center gap-2 flex-shrink-0">
                  <div className="w-2 h-2 bg-primary rounded-full" />
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Check className="w-3.5 h-3.5 text-muted-foreground" /></button>
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </AdminLayout>
  );
}
