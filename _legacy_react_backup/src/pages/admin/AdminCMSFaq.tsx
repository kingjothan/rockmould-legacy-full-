import { AdminLayout } from "@/components/layout/AdminLayout";
import { HelpCircle, Plus, Edit, Trash2, GripVertical, Eye, EyeOff, ChevronDown, ChevronRight } from "lucide-react";
import { useState } from "react";

const categories = [
  {
    id: 1, name: "General", open: true,
    items: [
      { id: 1, q: "What does Rockmould Limited do?", a: "Rockmould is a premium real estate development and construction company...", visible: true },
      { id: 2, q: "Where are your projects located?", a: "Our projects are primarily located in Lagos...", visible: true },
    ]
  },
  {
    id: 2, name: "Investment & Payments", open: false,
    items: [
      { id: 3, q: "What payment plans are available?", a: "We offer flexible installment plans...", visible: true },
      { id: 4, q: "Is my investment secure?", a: "Absolutely. All properties come with proper documentation...", visible: true },
      { id: 5, q: "Can I get a refund?", a: "Refund policies vary by project phase...", visible: false },
    ]
  },
  {
    id: 3, name: "Client Portal", open: false,
    items: [
      { id: 6, q: "How do I access the client portal?", a: "You can access the portal using your registered phone number...", visible: true },
    ]
  },
];

export default function AdminCMSFaq() {
  const [cats, setCats] = useState(categories);
  const toggleCat = (id: number) => setCats(cats.map(c => c.id === id ? { ...c, open: !c.open } : c));

  return (
    <AdminLayout>
      <div className="max-w-4xl mx-auto space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">FAQ CMS</h1>
            <p className="text-sm text-muted-foreground mt-1">Manage FAQ categories and questions</p>
          </div>
          <div className="flex gap-2">
            <button className="luxury-btn-outline text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Add Category</button>
            <button className="luxury-btn-primary text-xs gap-2"><Plus className="w-3.5 h-3.5" /> Add Question</button>
          </div>
        </div>

        {cats.map((cat) => (
          <div key={cat.id} className="luxury-card">
            <div className="flex items-center justify-between w-full mb-4">
              <div className="flex items-center gap-2">
                <button onClick={() => toggleCat(cat.id)} className="flex items-center gap-2 text-left">
                  {cat.open ? <ChevronDown className="w-4 h-4 text-muted-foreground" /> : <ChevronRight className="w-4 h-4 text-muted-foreground" />}
                  <h2 className="font-display text-lg font-semibold">{cat.name}</h2>
                  <span className="text-xs text-muted-foreground">({cat.items.length} questions)</span>
                </button>
              </div>
              <div className="flex gap-1">
                <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
              </div>
            </div>
            {cat.open && (
              <div className="space-y-2">
                {cat.items.map((item) => (
                  <div key={item.id} className={`flex items-start gap-3 p-3 border border-border/50 rounded-sm ${!item.visible ? "opacity-50" : ""}`}>
                    <GripVertical className="w-4 h-4 text-muted-foreground mt-0.5 cursor-grab flex-shrink-0" />
                    <HelpCircle className="w-4 h-4 text-primary mt-0.5 flex-shrink-0" />
                    <div className="flex-1 min-w-0">
                      <p className="text-sm font-medium">{item.q}</p>
                      <p className="text-xs text-muted-foreground mt-1 truncate">{item.a}</p>
                    </div>
                    <div className="flex gap-1 flex-shrink-0">
                      <button className="p-1.5 hover:bg-muted rounded-sm">{item.visible ? <Eye className="w-3.5 h-3.5 text-muted-foreground" /> : <EyeOff className="w-3.5 h-3.5 text-amber-400" />}</button>
                      <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
                      <button className="p-1.5 hover:bg-muted rounded-sm"><Trash2 className="w-3.5 h-3.5 text-destructive" /></button>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        ))}
      </div>
    </AdminLayout>
  );
}
