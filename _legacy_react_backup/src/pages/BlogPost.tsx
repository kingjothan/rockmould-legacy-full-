import { Link, useParams } from "react-router-dom";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { Clock, ArrowLeft, Share2, ChevronRight } from "lucide-react";
import { getArticleById } from "@/lib/articles";

export default function BlogPostPage() {
  const { id } = useParams();
  const article = getArticleById(id);

  if (!article) {
    return (
      <PublicLayout>
        <section className="min-h-[60vh] flex items-center pt-20">
          <div className="container-luxury text-center">
            <span className="text-xs uppercase tracking-[0.3em] text-primary mb-4 block">Insights</span>
            <h1 className="font-display text-4xl md:text-5xl font-bold mb-4">Article Not Found</h1>
            <p className="text-muted-foreground max-w-xl mx-auto mb-8">
              The insight you requested is not available right now. Continue exploring the Rockmould knowledge hub.
            </p>
            <div className="flex flex-wrap justify-center gap-4">
              <Link to="/blog" className="luxury-btn-primary">
                Back to Insights <ChevronRight className="w-4 h-4" />
              </Link>
              <Link to="/contact" className="luxury-btn-outline">
                Speak with Rockmould
              </Link>
            </div>
          </div>
        </section>
      </PublicLayout>
    );
  }

  return (
    <PublicLayout>
      <div className="pt-20" />
      <section className="section-padding">
        <div className="container-luxury max-w-4xl">
          <Link
            to="/blog"
            className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors mb-8"
          >
            <ArrowLeft className="w-4 h-4" /> Back to Insights
          </Link>
          <span className="text-xs uppercase tracking-[0.2em] text-primary mb-4 block">
            {article.category}
          </span>
          <h1 className="font-display text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-6">
            {article.title}
          </h1>
          <div className="flex items-center gap-6 text-sm text-muted-foreground mb-8">
            <span>{article.date}</span>
            <span className="flex items-center gap-1">
              <Clock className="w-3.5 h-3.5" />
              {article.readTime} read
            </span>
            <span>By {article.author}</span>
          </div>
          <div className="aspect-[16/9] bg-muted rounded-md mb-12" />

          <div className="prose-luxury space-y-6 text-muted-foreground leading-relaxed">
            <p className="text-lg">{article.intro}</p>
            {article.sections.map((section) => (
              <div key={section.heading}>
                <h2 className="font-display text-2xl font-semibold text-foreground mt-10">
                  {section.heading}
                </h2>
                {section.paragraphs.map((paragraph) => (
                  <p key={paragraph}>{paragraph}</p>
                ))}
              </div>
            ))}
            {article.quote && (
              <blockquote className="border-l-2 border-primary pl-6 italic my-8 text-foreground">
                "{article.quote}"
              </blockquote>
            )}
          </div>

          <div className="luxury-divider my-12" />
          <div className="flex items-center justify-between">
            <button className="luxury-btn-ghost text-xs">
              <Share2 className="w-4 h-4" /> Share Article
            </button>
            <Link to="/blog" className="luxury-btn-outline text-xs">
              More Insights <ChevronRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
