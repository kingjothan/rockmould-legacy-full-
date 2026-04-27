import { PortalLayout } from "@/components/layout/PortalLayout";
import { User, Phone, Mail, MapPin, Shield, FileText } from "lucide-react";

export default function PortalProfile() {
  return (
    <PortalLayout>
      <div className="max-w-3xl mx-auto space-y-8">
        <h1 className="font-display text-2xl font-semibold">My Profile</h1>
        <div className="luxury-card">
          <div className="flex items-center gap-6 mb-8">
            <div className="w-20 h-20 rounded-full bg-muted flex items-center justify-center text-2xl font-display font-bold text-muted-foreground">AO</div>
            <div>
              <h2 className="font-display text-xl font-semibold">Adebayo Ogunleye</h2>
              <div className="flex items-center gap-1 mt-1"><span className="status-completed">Verified</span></div>
            </div>
          </div>
          <div className="space-y-4">
            {[
              { icon: Phone, label: "Phone", value: "+234 801 234 5678" },
              { icon: Mail, label: "Email", value: "adebayo@example.com" },
              { icon: MapPin, label: "Address", value: "12 Alexander Avenue, Ikoyi, Lagos" },
              { icon: FileText, label: "Linked Projects", value: "Azure Towers, Meridian Plaza, Crown Heights" },
              { icon: Shield, label: "Verification Status", value: "KYC Verified" },
            ].map((item) => (
              <div key={item.label} className="flex items-start gap-4 py-3 border-b border-border last:border-0">
                <item.icon className="w-4 h-4 text-primary mt-0.5" />
                <div>
                  <p className="text-xs text-muted-foreground uppercase tracking-wider">{item.label}</p>
                  <p className="text-sm mt-0.5">{item.value}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
        <div className="luxury-card">
          <h3 className="font-display text-lg font-semibold mb-3">Support Contact</h3>
          <p className="text-sm text-muted-foreground">Your Account Manager: <span className="text-foreground font-medium">Mrs. Folake Adeyemi</span></p>
          <p className="text-sm text-muted-foreground mt-1">Email: <a href="mailto:folake@rockmould.com" className="text-primary">folake@rockmould.com</a></p>
          <p className="text-sm text-muted-foreground mt-1">Phone: <a href="tel:+2341234567891" className="text-primary">+234 123 456 7891</a></p>
        </div>
      </div>
    </PortalLayout>
  );
}
