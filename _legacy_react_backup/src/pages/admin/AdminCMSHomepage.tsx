import { AdminLayout } from "@/components/layout/AdminLayout";
import { Globe, Save, Eye, GripVertical, EyeOff, Image, Type, BarChart3 } from "lucide-react";
import { useState } from "react";

const sections = [
  { id: "hero", label: "Hero Section", icon: Image, enabled: true, desc: "Main headline, subtext, CTA buttons, background media" },
  { id: "featured", label: "Featured Developments", icon: Globe, enabled: true, desc: "Showcase 3-6 featured projects" },
  { id: "services", label: "Services Overview", icon: Type, enabled: true, desc: "Core service offerings preview" },
  { id: "value-prop", label: "Value Proposition", icon: Type, enabled: true, desc: "Why choose Rockmould section" },
  { id: "metrics", label: "Achievements & Numbers", icon: BarChart3, enabled: true, desc: "Counters: projects, clients, sqft" },
  { id: "process", label: "Project Lifecycle", icon: Globe, enabled: true, desc: "Process steps from idea to delivery" },
  { id: "testimonials", label: "Testimonials", icon: Type, enabled: true, desc: "Client testimonials preview" },
  { id: "blog", label: "Latest Insights", icon: Type, enabled: false, desc: "Blog post previews" },
  { id: "portal-cta", label: "Client Portal CTA", icon: Globe, enabled: true, desc: "Portal access teaser" },
  { id: "contact-cta", label: "Contact Banner", icon: Type, enabled: true, desc: "Consultation call-to-action" },
];

export default function AdminCMSHomepage() {
  const [items, setItems] = useState(sections);

  const toggleSection = (id: string) => {
    setItems(items.map(s => s.id === id ? { ...s, enabled: !s.enabled } : s));
  };

  return (
    <AdminLayout>
      <div className="max-w-4xl mx-auto space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="font-display text-2xl font-semibold">Homepage CMS</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage homepage content and section order</p>
          </div>
          <div className="flex gap-2">
            <button className="luxury-btn-outline text-xs gap-2"><Eye className="w-3.5 h-3.5" /> Preview</button>
            <button className="luxury-btn-primary text-xs gap-2"><Save className="w-3.5 h-3.5" /> Save Changes</button>
          </div>
        </div>

        {/* Hero Editor */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Hero Content</h2>
          <div className="space-y-4">
            <div>
              <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Headline</label>
              <input defaultValue="Building Tomorrow's Landmarks Today" className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
            </div>
            <div>
              <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Subtext</label>
              <textarea defaultValue="Premium real estate development and construction excellence across Nigeria's most prestigious locations." rows={3} className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50 resize-none" />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Primary CTA Text</label>
                <input defaultValue="Explore Projects" className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
              </div>
              <div>
                <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Secondary CTA Text</label>
                <input defaultValue="Book Consultation" className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
              </div>
            </div>
            <div>
              <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Hero Background</label>
              <div className="w-full h-40 bg-muted border-2 border-dashed border-border rounded-sm flex items-center justify-center cursor-pointer hover:border-primary/30 transition-colors">
                <div className="text-center">
                  <Image className="w-8 h-8 text-muted-foreground mx-auto mb-2" />
                  <p className="text-xs text-muted-foreground">Click to upload image or video</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Section Manager */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Section Order & Visibility</h2>
          <div className="space-y-2">
            {items.map((section) => (
              <div key={section.id} className={`flex items-center gap-3 p-3 border rounded-sm transition-colors ${section.enabled ? "border-border/50 bg-transparent" : "border-border/30 bg-muted/30 opacity-60"}`}>
                <GripVertical className="w-4 h-4 text-muted-foreground cursor-grab flex-shrink-0" />
                <section.icon className="w-4 h-4 text-muted-foreground flex-shrink-0" />
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium">{section.label}</p>
                  <p className="text-xs text-muted-foreground">{section.desc}</p>
                </div>
                <button onClick={() => toggleSection(section.id)} className="p-1.5 hover:bg-muted rounded-sm">
                  {section.enabled ? <Eye className="w-4 h-4 text-primary" /> : <EyeOff className="w-4 h-4 text-muted-foreground" />}
                </button>
              </div>
            ))}
          </div>
        </div>

        {/* Stats Editor */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Achievement Numbers</h2>
          <div className="grid grid-cols-2 gap-4">
            {[
              { label: "Projects Completed", value: "150+" },
              { label: "Happy Clients", value: "200+" },
              { label: "Years of Excellence", value: "18+" },
              { label: "Sq Ft Developed", value: "2M+" },
            ].map((stat) => (
              <div key={stat.label} className="space-y-2">
                <label className="text-xs uppercase tracking-wider text-muted-foreground block">{stat.label}</label>
                <input defaultValue={stat.value} className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
              </div>
            ))}
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
