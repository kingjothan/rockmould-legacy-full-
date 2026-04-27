import { AdminLayout } from "@/components/layout/AdminLayout";
import { MessageSquare, Plus, Edit, Trash2, Eye, EyeOff, Star } from "lucide-react";

const testimonials = [
  { id: 1, client: "Chief Adebayo Ogunlesi", role: "Investor", quote: "Rockmould delivered beyond our expectations. The quality of finish and attention to detail is world-class.", project: "Azure Towers", visible: true, featured: true },
  { id: 2, client: "Folake Adeyemi", role: "Homeowner", quote: "From the first consultation to key handover, every step was handled with professionalism and elegance.", project: "Meridian Plaza", visible: true, featured: true },
  { id: 3, client: "Engr. Chidi Okwuosa", role: "Developer Partner", quote: "Their construction expertise and project management capabilities are unmatched in the Nigerian market.", project: "Crown Heights", visible: true, featured: false },
  { id: 4, client: "Ngozi Eze", role: "Property Buyer", quote: "The transparency of their client portal made tracking my property investment effortless.", project: "Azure Towers", visible: false, featured: false },
];

export default function AdminCMSTestimonials() {
  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Testimonials CMS</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage client testimonials and reviews</p>
          </div>
          <button className="luxury-btn-primary text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Add Testimonial</button>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {testimonials.map((t) => (
            <div key={t.id} className={`luxury-card ${!t.visible ? "opacity-60" : ""}`}>
              <div className="flex items-start justify-between mb-3">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full bg-muted flex items-center justify-center text-xs font-bold">{t.client.split(' ').pop()?.[0]}</div>
                  <div>
                    <p className="text-sm font-medium">{t.client}</p>
                    <p className="text-xs text-muted-foreground">{t.role} · {t.project}</p>
                  </div>
                </div>
                <div className="flex gap-1">
                  {t.featured && <Star className="w-4 h-4 text-primary fill-primary" />}
                </div>
              </div>
              <blockquote className="text-sm text-muted-foreground italic border-l-2 border-primary/30 pl-3 mb-4">"{t.quote}"</blockquote>
              <div className="flex items-center justify-between">
                <span className={`text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm ${t.visible ? "text-emerald-400 bg-emerald-400/10" : "text-amber-400 bg-amber-400/10"}`}>
                  {t.visible ? "Visible" : "Hidden"}
                </span>
                <div className="flex gap-1">
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
                  <button className="p-1.5 hover:bg-muted rounded-sm">{t.visible ? <Eye className="w-3.5 h-3.5 text-muted-foreground" /> : <EyeOff className="w-3.5 h-3.5 text-amber-400" />}</button>
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Trash2 className="w-3.5 h-3.5 text-destructive" /></button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </AdminLayout>
  );
}
