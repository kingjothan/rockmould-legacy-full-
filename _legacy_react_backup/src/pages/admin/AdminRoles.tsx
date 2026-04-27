import { AdminLayout } from "@/components/layout/AdminLayout";
import { Lock, Plus, Shield, Edit, Trash2, Check, X } from "lucide-react";

const roles = [
  { id: 1, name: "Super Admin", users: 1, color: "text-destructive bg-destructive/10", permissions: ["All Access"] },
  { id: 2, name: "Admin", users: 2, color: "text-primary bg-primary/10", permissions: ["Clients", "Projects", "Payments", "Documents", "Media", "Updates", "CMS", "Forms", "Notifications"] },
  { id: 3, name: "Project Manager", users: 3, color: "text-blue-400 bg-blue-400/10", permissions: ["Projects", "Documents", "Media", "Updates"] },
  { id: 4, name: "Finance", users: 1, color: "text-emerald-400 bg-emerald-400/10", permissions: ["Payments", "Documents"] },
  { id: 5, name: "Content Manager", users: 2, color: "text-amber-400 bg-amber-400/10", permissions: ["CMS", "Media"] },
];

const allPermissions = ["Clients", "KYC", "Projects", "Payments", "Documents", "Media", "Updates", "CMS", "Forms", "Notifications", "Settings", "Roles", "Activity Log"];

const admins = [
  { id: 1, name: "Adebayo Admin", email: "admin@rockmould.com", role: "Super Admin", lastActive: "2 min ago" },
  { id: 2, name: "Folake Manager", email: "folake@rockmould.com", role: "Admin", lastActive: "1 hour ago" },
  { id: 3, name: "Chidi PM", email: "chidi@rockmould.com", role: "Project Manager", lastActive: "3 hours ago" },
  { id: 4, name: "Ngozi Finance", email: "ngozi@rockmould.com", role: "Finance", lastActive: "1 day ago" },
];

export default function AdminRoles() {
  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Roles & Permissions</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage team access and permissions</p>
          </div>
          <div className="flex gap-2">
            <button className="luxury-btn-outline text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Add Role</button>
            <button className="luxury-btn-primary text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Add User</button>
          </div>
        </div>

        {/* Roles */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {roles.map((role) => (
            <div key={role.id} className="luxury-card">
              <div className="flex items-center justify-between mb-3">
                <div className="flex items-center gap-2">
                  <Shield className={`w-4 h-4 ${role.color.split(" ")[0]}`} />
                  <span className="text-sm font-semibold">{role.name}</span>
                </div>
                <span className="text-xs text-muted-foreground">{role.users} user{role.users !== 1 ? "s" : ""}</span>
              </div>
              <div className="flex flex-wrap gap-1.5 mb-3">
                {role.permissions.map((p) => (
                  <span key={p} className={`text-[10px] px-2 py-0.5 rounded-sm ${role.color}`}>{p}</span>
                ))}
              </div>
              <div className="flex gap-1">
                <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
                {role.name !== "Super Admin" && <button className="p-1.5 hover:bg-muted rounded-sm"><Trash2 className="w-3.5 h-3.5 text-destructive" /></button>}
              </div>
            </div>
          ))}
        </div>

        {/* Permission Matrix */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Access Matrix</h2>
          <div className="overflow-x-auto">
            <table className="w-full text-xs">
              <thead>
                <tr className="border-b border-border">
                  <th className="text-left pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium pr-4">Permission</th>
                  {roles.map(r => <th key={r.id} className="pb-3 text-[10px] uppercase tracking-wider text-muted-foreground font-medium text-center px-2">{r.name}</th>)}
                </tr>
              </thead>
              <tbody>
                {allPermissions.map((perm) => (
                  <tr key={perm} className="border-b border-border/30">
                    <td className="py-2.5 text-sm pr-4">{perm}</td>
                    {roles.map(r => (
                      <td key={r.id} className="py-2.5 text-center">
                        {r.permissions.includes("All Access") || r.permissions.includes(perm) ? (
                          <Check className="w-4 h-4 text-emerald-400 mx-auto" />
                        ) : (
                          <X className="w-4 h-4 text-muted-foreground/30 mx-auto" />
                        )}
                      </td>
                    ))}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Team Members */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Team Members</h2>
          <div className="space-y-3">
            {admins.map((a) => (
              <div key={a.id} className="flex items-center gap-4 p-3 border border-border/50 rounded-sm hover:bg-muted/30 transition-colors">
                <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-xs font-bold text-primary">{a.name[0]}</div>
                <div className="flex-1">
                  <p className="text-sm font-medium">{a.name}</p>
                  <p className="text-xs text-muted-foreground">{a.email}</p>
                </div>
                <span className="text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm text-primary bg-primary/10">{a.role}</span>
                <span className="text-xs text-muted-foreground hidden sm:inline">{a.lastActive}</span>
                <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
              </div>
            ))}
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
