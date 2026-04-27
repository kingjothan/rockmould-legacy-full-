import { useState, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";
import { Menu, X, ChevronDown } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

const navItems = [
  { label: "Home", path: "/" },
  { label: "About", path: "/about" },
  {
    label: "Services", path: "/services",
    children: [
      { label: "Real Estate Development", path: "/services#real-estate" },
      { label: "Construction", path: "/services#construction" },
      { label: "Property Advisory", path: "/services#advisory" },
      { label: "Investment Support", path: "/services#investment" },
      { label: "Project Management", path: "/services#management" },
    ],
  },
  { label: "Projects", path: "/projects" },
  { label: "Portfolio", path: "/portfolio" },
  { label: "Insights", path: "/blog" },
  { label: "Careers", path: "/careers" },
  { label: "Contact", path: "/contact" },
];

export function Header() {
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);
  const location = useLocation();

  const isPathActive = (path: string) => {
    const [pathname, hash] = path.split("#");
    const matchesPath =
      pathname === "/"
        ? location.pathname === "/"
        : location.pathname === pathname || location.pathname.startsWith(`${pathname}/`);

    if (!matchesPath) {
      return false;
    }

    return hash ? location.hash === `#${hash}` : true;
  };

  const isNavItemActive = (path: string, children?: { label: string; path: string }[]) =>
    isPathActive(path) || Boolean(children?.some((child) => isPathActive(child.path)));

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 50);
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    setMobileOpen(false);
    setActiveDropdown(null);
  }, [location]);

  return (
    <>
      <header
        className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
          scrolled
            ? "bg-background/95 backdrop-blur-md border-b border-border"
            : "bg-transparent"
        }`}
      >
        <div className="container-luxury flex items-center justify-between h-20">
          <Link to="/" className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-sm bg-primary flex items-center justify-center">
              <span className="font-display text-primary-foreground text-lg font-bold">R</span>
            </div>
            <div className="flex flex-col">
              <span className="font-display text-lg font-semibold tracking-wide text-foreground">
                Rockmould
              </span>
              <span className="text-[10px] uppercase tracking-[0.25em] text-muted-foreground -mt-0.5">
                Limited
              </span>
            </div>
          </Link>

          {/* Desktop nav */}
          <nav className="hidden lg:flex items-center gap-1">
            {navItems.map((item) => (
              <div
                key={item.label}
                className="relative"
                onMouseEnter={() => item.children && setActiveDropdown(item.label)}
                onMouseLeave={() => setActiveDropdown(null)}
              >
                <Link
                  to={item.path}
                  className={`px-4 py-2 text-sm tracking-wide transition-colors duration-300 flex items-center gap-1 ${
                    isNavItemActive(item.path, item.children)
                      ? "text-primary"
                      : "text-muted-foreground hover:text-foreground"
                  }`}
                >
                  {item.label}
                  {item.children && <ChevronDown className="w-3 h-3" />}
                </Link>

                <AnimatePresence>
                  {item.children && activeDropdown === item.label && (
                    <motion.div
                      initial={{ opacity: 0, y: 8 }}
                      animate={{ opacity: 1, y: 0 }}
                      exit={{ opacity: 0, y: 8 }}
                      transition={{ duration: 0.2 }}
                      className="absolute top-full left-0 mt-2 w-64 bg-card border border-border rounded-md shadow-lg overflow-hidden"
                    >
                      {item.children.map((child) => (
                        <Link
                          key={child.label}
                          to={child.path}
                          className="block px-5 py-3 text-sm text-muted-foreground hover:text-foreground hover:bg-muted/50 transition-colors"
                        >
                          {child.label}
                        </Link>
                      ))}
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>
            ))}
          </nav>

          <div className="hidden lg:flex items-center gap-4">
            <Link to="/portal" className="luxury-btn-outline text-xs py-2 px-5">
              Client Portal
            </Link>
            <Link to="/contact" className="luxury-btn-primary text-xs py-2 px-5">
              Get in Touch
            </Link>
          </div>

          <button
            type="button"
            className="lg:hidden text-foreground p-2"
            onClick={() => setMobileOpen(true)}
            aria-label="Open navigation"
            aria-expanded={mobileOpen}
          >
            <Menu className="w-6 h-6" />
          </button>
        </div>
      </header>

      {/* Mobile drawer */}
      <AnimatePresence>
        {mobileOpen && (
          <>
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              className="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm"
              onClick={() => setMobileOpen(false)}
            />
            <motion.div
              initial={{ x: "100%" }}
              animate={{ x: 0 }}
              exit={{ x: "100%" }}
              transition={{ type: "tween", duration: 0.3 }}
              className="fixed right-0 top-0 bottom-0 z-50 w-80 bg-card border-l border-border flex flex-col"
            >
              <div className="flex items-center justify-between p-6 border-b border-border">
                <span className="font-display text-lg font-semibold">Menu</span>
                <button type="button" onClick={() => setMobileOpen(false)} aria-label="Close navigation">
                  <X className="w-5 h-5 text-muted-foreground" />
                </button>
              </div>
              <nav className="flex-1 overflow-y-auto py-4">
                {navItems.map((item) => (
                  <div key={item.label}>
                    <Link
                      to={item.path}
                      className={`block px-6 py-3 text-sm tracking-wide ${
                        isNavItemActive(item.path, item.children)
                          ? "text-primary"
                          : "text-muted-foreground"
                      }`}
                    >
                      {item.label}
                    </Link>
                    {item.children?.map((child) => (
                      <Link
                        key={child.label}
                        to={child.path}
                        className="block px-10 py-2 text-xs text-muted-foreground"
                      >
                        {child.label}
                      </Link>
                    ))}
                  </div>
                ))}
              </nav>
              <div className="p-6 border-t border-border space-y-3">
                <Link to="/portal" className="luxury-btn-outline text-xs w-full">
                  Client Portal
                </Link>
                <Link to="/contact" className="luxury-btn-primary text-xs w-full">
                  Get in Touch
                </Link>
              </div>
            </motion.div>
          </>
        )}
      </AnimatePresence>
    </>
  );
}
