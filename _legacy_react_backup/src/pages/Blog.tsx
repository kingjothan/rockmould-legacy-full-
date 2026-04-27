import { useState } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { HeroSection } from "@/components/ui/HeroSection";
import { Search, ArrowRight, Clock } from "lucide-react";
import { articles, featuredArticle } from "@/lib/articles";
import { toast } from "@/components/ui/sonner";

export default function BlogPage() {
  const [activeCat, setActiveCat] = useState("All");
  const [search, setSearch] = useState("");
  const [email, setEmail] = useState("");
  const categories = ["All", ...new Set(articles.map((article) => article.category))];

  const filtered = articles.filter((a) => {
    const matchCat = activeCat === "All" || a.category === activeCat;
    const matchSearch = a.title.toLowerCase().includes(search.toLowerCase());
    return matchCat && matchSearch;
  });

  const handleSubscribe = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    if (!email.trim()) {
      toast.error("Enter an email address to subscribe.");
      return;
    }

    toast.success("You are subscribed to Rockmould insights.");
    setEmail("");
  };

  const featured = featuredArticle;

  return (
    <PublicLayout>
      <HeroSection title="Insights & Resources" subtitle="Blog" description="Expert perspectives on real estate, construction, and investment from the Rockmould team." height="medium" />

      <section className="section-padding">
        <div className="container-luxury">
          {/* Featured */}
          {featured && (
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="luxury-card p-0 overflow-hidden mb-16">
              <div className="grid grid-cols-1 lg:grid-cols-2">
                <div className="aspect-[16/10] bg-muted" />
                <div className="p-8 lg:p-12 flex flex-col justify-center">
                  <span className="text-xs uppercase tracking-[0.2em] text-primary mb-3">{featured.category}</span>
                  <h2 className="font-display text-2xl md:text-3xl font-semibold mb-4">{featured.title}</h2>
                  <p className="text-muted-foreground mb-6">{featured.excerpt}</p>
                  <div className="flex items-center gap-4 text-xs text-muted-foreground mb-6">
                    <span>{featured.date}</span>
                    <span className="flex items-center gap-1"><Clock className="w-3 h-3" />{featured.readTime}</span>
                  </div>
                  <Link to={`/blog/${featured.id}`} className="luxury-btn-primary w-fit text-xs">Read Article <ArrowRight className="w-4 h-4" /></Link>
                </div>
              </div>
            </motion.div>
          )}

          {/* Filters */}
          <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-10">
            <div className="flex flex-wrap gap-2">
              {categories.map((c) => (
                <button key={c} onClick={() => setActiveCat(c)} className={`px-4 py-2 text-xs uppercase tracking-[0.12em] rounded-sm border transition-all ${activeCat === c ? "bg-primary text-primary-foreground border-primary" : "border-border text-muted-foreground hover:border-primary/30"}`}>{c}</button>
              ))}
            </div>
            <div className="relative w-full md:w-64">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search articles..." className="w-full bg-card border border-border rounded-sm pl-10 pr-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors" />
            </div>
          </div>

          {/* Grid */}
          {filtered.length === 0 ? (
            <div className="luxury-card text-center py-16">
              <Search className="w-10 h-10 text-muted-foreground mx-auto mb-4" />
              <h3 className="font-display text-2xl font-semibold mb-2">No Articles Found</h3>
              <p className="text-muted-foreground max-w-md mx-auto">
                Try a different keyword or switch categories to explore more Rockmould insights.
              </p>
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {filtered.map((a, i) => (
                <motion.div key={a.id} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.05 }}>
                  <Link to={`/blog/${a.id}`} className="group block luxury-card p-0 overflow-hidden">
                    <div className="aspect-[16/9] bg-muted" />
                    <div className="p-6">
                      <div className="flex items-center gap-3 mb-3">
                        <span className="text-[11px] uppercase tracking-[0.15em] text-primary">{a.category}</span>
                        <span className="text-[11px] text-muted-foreground flex items-center gap-1"><Clock className="w-3 h-3" />{a.readTime}</span>
                      </div>
                      <h3 className="font-display text-lg font-semibold group-hover:text-primary transition-colors mb-2">{a.title}</h3>
                      <p className="text-sm text-muted-foreground line-clamp-2">{a.excerpt}</p>
                      <span className="text-xs text-muted-foreground mt-4 block">{a.date}</span>
                    </div>
                  </Link>
                </motion.div>
              ))}
            </div>
          )}

          {/* Newsletter */}
          <div className="luxury-card p-12 text-center mt-16">
            <h3 className="font-display text-2xl font-semibold mb-3">Subscribe to Our Insights</h3>
            <p className="text-muted-foreground mb-6 max-w-md mx-auto">Get exclusive market insights and project updates delivered to your inbox.</p>
            <form className="flex gap-2 max-w-md mx-auto" onSubmit={handleSubscribe}>
              <input value={email} onChange={(event) => setEmail(event.target.value)} type="email" placeholder="Your email address" className="flex-1 bg-muted border border-border rounded-sm px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50" />
              <button type="submit" className="luxury-btn-primary text-xs py-2.5">Subscribe</button>
            </form>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
