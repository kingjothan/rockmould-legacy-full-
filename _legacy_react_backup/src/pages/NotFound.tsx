import { Link, useLocation } from "react-router-dom";
import { useEffect } from "react";
import { PublicLayout } from "@/components/layout/PublicLayout";
import { ArrowRight } from "lucide-react";

const NotFound = () => {
  const location = useLocation();

  useEffect(() => {
    console.error("404 Error: User attempted to access non-existent route:", location.pathname);
  }, [location.pathname]);

  return (
    <PublicLayout>
      <section className="min-h-[70vh] flex items-center pt-20">
        <div className="container-luxury text-center">
          <span className="text-xs uppercase tracking-[0.3em] text-primary mb-4 block">404 Error</span>
          <h1 className="font-display text-5xl md:text-6xl font-bold mb-4">Page Not Found</h1>
          <p className="text-muted-foreground max-w-2xl mx-auto mb-8">
            We could not find <span className="text-foreground">{location.pathname}</span>. The page may have moved, or the link may be outdated.
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <Link to="/" className="luxury-btn-primary">
              Return Home <ArrowRight className="w-4 h-4" />
            </Link>
            <Link to="/projects" className="luxury-btn-outline">
              Explore Projects
            </Link>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
};

export default NotFound;
