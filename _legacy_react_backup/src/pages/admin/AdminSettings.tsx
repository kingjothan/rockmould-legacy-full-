import { toast } from "@/components/ui/sonner";
import { AdminLayout } from "@/components/layout/AdminLayout";
import { Settings, Globe, Phone, Mail, MapPin } from "lucide-react";

export default function AdminSettings() {
  const handleSave = (section: string) => (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    toast.success(`${section} settings saved.`);
  };

  return (
    <AdminLayout>
      <div className="max-w-3xl mx-auto space-y-8">
        <h1 className="font-display text-2xl font-semibold">Settings</h1>
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-6">Company Information</h2>
          <form className="space-y-4" onSubmit={handleSave("Company information")}>
            {[
              { label: "Company Name", value: "Rockmould Limited", icon: Globe },
              { label: "Phone", value: "+234 123 456 7890", icon: Phone },
              { label: "Email", value: "info@rockmould.com", icon: Mail },
              { label: "Address", value: "12 Admiralty Way, Victoria Island, Lagos", icon: MapPin },
            ].map((f) => (
              <div key={f.label}>
                <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">{f.label}</label>
                <input defaultValue={f.value} className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
              </div>
            ))}
            <button type="submit" className="luxury-btn-primary text-xs mt-4">Save Changes</button>
          </form>
        </div>
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-6">Social Links</h2>
          <form className="space-y-4" onSubmit={handleSave("Social links")}>
            {["Instagram", "LinkedIn", "Twitter/X", "Facebook"].map((s) => (
              <div key={s}><label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">{s}</label><input placeholder={`https://${s.toLowerCase()}.com/rockmould`} className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" /></div>
            ))}
            <button type="submit" className="luxury-btn-primary text-xs mt-4">Save Changes</button>
          </form>
        </div>
      </div>
    </AdminLayout>
  );
}
