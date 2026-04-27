import { AdminLayout } from "@/components/layout/AdminLayout";
import { BookOpen, Plus, Search, Edit, Trash2, Eye, Star, Filter } from "lucide-react";
import { useState } from "react";

const posts = [
  { id: 1, title: "The Future of Luxury Real Estate in Lagos", category: "Market Insights", author: "Admin", status: "Published", date: "March 10, 2026", featured: true },
  { id: 2, title: "5 Things to Look for in a Premium Development", category: "Investment", author: "Admin", status: "Published", date: "March 5, 2026", featured: false },
  { id: 3, title: "Construction Update: Azure Towers Progress", category: "Company News", author: "Admin", status: "Draft", date: "March 1, 2026", featured: false },
  { id: 4, title: "Why Victoria Island Remains Nigeria's Prime Location", category: "Market Insights", author: "Admin", status: "Published", date: "Feb 20, 2026", featured: true },
  { id: 5, title: "Sustainable Building Practices at Rockmould", category: "Thought Leadership", author: "Admin", status: "Draft", date: "Feb 15, 2026", featured: false },
];

const statusColor: Record<string, string> = {
  Published: "text-emerald-400 bg-emerald-400/10",
  Draft: "text-amber-400 bg-amber-400/10",
};

export default function AdminCMSBlog() {
  const [search, setSearch] = useState("");

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 className="font-display text-2xl font-semibold">Blog CMS</h1>
            <p className="text-sm text-muted-foreground mt-1">Create and manage blog articles</p>
          </div>
          <button className="luxury-btn-primary text-xs gap-2"><Plus className="w-3.5 h-3.5" /> New Article</button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div className="luxury-card text-center"><p className="text-[10px] uppercase tracking-wider text-muted-foreground">Total Posts</p><p className="font-display text-2xl font-semibold mt-1">24</p></div>
          <div className="luxury-card text-center"><p className="text-[10px] uppercase tracking-wider text-muted-foreground">Published</p><p className="font-display text-2xl font-semibold mt-1">18</p></div>
          <div className="luxury-card text-center"><p className="text-[10px] uppercase tracking-wider text-muted-foreground">Drafts</p><p className="font-display text-2xl font-semibold mt-1">6</p></div>
        </div>

        <div className="luxury-card">
          <div className="flex flex-col sm:flex-row gap-3 mb-6">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search articles..." className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-primary/50" />
            </div>
            <button className="luxury-btn-outline text-xs gap-2"><Filter className="w-3.5 h-3.5" /> Filter</button>
          </div>

          <div className="space-y-3">
            {posts.filter(p => p.title.toLowerCase().includes(search.toLowerCase())).map((post) => (
              <div key={post.id} className="flex items-center gap-4 p-4 border border-border/50 rounded-sm hover:bg-muted/30 transition-colors group">
                <div className="w-16 h-12 bg-muted rounded-sm flex-shrink-0" />
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2">
                    <p className="text-sm font-medium truncate">{post.title}</p>
                    {post.featured && <Star className="w-3.5 h-3.5 text-primary fill-primary flex-shrink-0" />}
                  </div>
                  <p className="text-xs text-muted-foreground">{post.category} · {post.author} · {post.date}</p>
                </div>
                <span className={`text-[10px] uppercase tracking-wider px-2 py-1 rounded-sm ${statusColor[post.status]}`}>{post.status}</span>
                <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button className="p-1.5 hover:bg-muted rounded-sm"><Edit className="w-3.5 h-3.5 text-muted-foreground" /></button>
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
