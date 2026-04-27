import { ReactNode, useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import {
  LayoutDashboard,
  FolderKanban,
  FileText,
  Image,
  Receipt,
  Clock,
  User,
  HelpCircle,
  LogOut,
  ChevronLeft,
  Menu,
  X,
} from "lucide-react";
import { AnimatePresence, motion } from "framer-motion";

const portalNav = [
  { label: "Dashboard", path: "/portal/dashboard", icon: LayoutDashboard },
  { label: "My Projects", path: "/portal/projects", icon: FolderKanban },
  { label: "Documents", path: "/portal/documents", icon: FileText },
  { label: "Media", path: "/portal/media", icon: Image },
  { label: "Balance Sheet", path: "/portal/balance", icon: Receipt },
  { label: "Updates", path: "/portal/updates", icon: Clock },
  { label: "Profile", path: "/portal/profile", icon: User },
  { label: "Support", path: "/portal/support", icon: HelpCircle },
];

export function PortalLayout({ children }: { children: ReactNode }) {
  const location = useLocation();
  const [collapsed, setCollapsed] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);

  useEffect(() => {
    setMobileOpen(false);
  }, [location.pathname]);

  return (
    <div className="min-h-screen bg-background flex">
      <aside
        className={`hidden md:flex flex-col border-r border-border bg-card transition-all duration-300 ${
          collapsed ? "w-16" : "w-64"
        }`}
      >
        <div className="p-4 border-b border-border flex items-center justify-between">
          {!collapsed && (
            <Link to="/portal/dashboard" className="flex items-center gap-2">
              <div className="w-8 h-8 rounded-sm bg-primary flex items-center justify-center">
                <span className="font-display text-primary-foreground text-sm font-bold">R</span>
              </div>
              <span className="font-display text-sm font-semibold">Client Portal</span>
            </Link>
          )}
          <button
            onClick={() => setCollapsed(!collapsed)}
            className="p-1 text-muted-foreground hover:text-foreground"
          >
            <ChevronLeft
              className={`w-4 h-4 transition-transform ${collapsed ? "rotate-180" : ""}`}
            />
          </button>
        </div>
        <nav className="flex-1 py-4 space-y-1">
          {portalNav.map((item) => (
            <Link
              key={item.path}
              to={item.path}
              className={`flex items-center gap-3 px-4 py-2.5 text-sm transition-colors ${
                location.pathname === item.path
                  ? "text-primary bg-primary/5 border-r-2 border-primary"
                  : "text-muted-foreground hover:text-foreground hover:bg-muted/50"
              }`}
            >
              <item.icon className="w-4 h-4 flex-shrink-0" />
              {!collapsed && <span>{item.label}</span>}
            </Link>
          ))}
        </nav>
        <div className="p-4 border-t border-border">
          <Link
            to="/portal"
            className={`flex items-center gap-3 text-sm text-muted-foreground hover:text-destructive transition-colors ${
              collapsed ? "justify-center" : ""
            }`}
          >
            <LogOut className="w-4 h-4" />
            {!collapsed && <span>Logout</span>}
          </Link>
        </div>
      </aside>

      <div className="flex-1 flex flex-col min-w-0">
        <header className="h-14 border-b border-border bg-card/50 flex items-center px-6 gap-4">
          <button
            className="md:hidden text-muted-foreground"
            onClick={() => setMobileOpen(true)}
            aria-label="Open portal navigation"
          >
            <Menu className="w-5 h-5" />
          </button>
          <h1 className="text-sm font-medium text-foreground">Welcome, Adebayo O.</h1>
          <div className="ml-auto flex items-center gap-3">
            <div className="w-8 h-8 rounded-full bg-muted flex items-center justify-center text-xs font-medium">
              AO
            </div>
          </div>
        </header>
        <main className="flex-1 overflow-y-auto p-6">{children}</main>
      </div>

      <AnimatePresence>
        {mobileOpen && (
          <>
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              className="fixed inset-0 z-40 bg-background/80 backdrop-blur-sm md:hidden"
              onClick={() => setMobileOpen(false)}
            />
            <motion.aside
              initial={{ x: "-100%" }}
              animate={{ x: 0 }}
              exit={{ x: "-100%" }}
              transition={{ type: "tween", duration: 0.25 }}
              className="fixed inset-y-0 left-0 z-50 w-72 border-r border-border bg-card md:hidden"
            >
              <div className="flex items-center justify-between p-4 border-b border-border">
                <Link to="/portal/dashboard" className="flex items-center gap-2">
                  <div className="w-8 h-8 rounded-sm bg-primary flex items-center justify-center">
                    <span className="font-display text-primary-foreground text-sm font-bold">R</span>
                  </div>
                  <span className="font-display text-sm font-semibold">Client Portal</span>
                </Link>
                <button
                  onClick={() => setMobileOpen(false)}
                  aria-label="Close portal navigation"
                  className="text-muted-foreground"
                >
                  <X className="w-4 h-4" />
                </button>
              </div>
              <nav className="py-4 space-y-1">
                {portalNav.map((item) => (
                  <Link
                    key={item.path}
                    to={item.path}
                    className={`flex items-center gap-3 px-4 py-3 text-sm transition-colors ${
                      location.pathname === item.path
                        ? "text-primary bg-primary/5 border-r-2 border-primary"
                        : "text-muted-foreground hover:text-foreground hover:bg-muted/50"
                    }`}
                  >
                    <item.icon className="w-4 h-4 flex-shrink-0" />
                    <span>{item.label}</span>
                  </Link>
                ))}
              </nav>
              <div className="p-4 border-t border-border">
                <Link
                  to="/portal"
                  className="flex items-center gap-3 text-sm text-muted-foreground hover:text-destructive transition-colors"
                >
                  <LogOut className="w-4 h-4" />
                  <span>Logout</span>
                </Link>
              </div>
            </motion.aside>
          </>
        )}
      </AnimatePresence>
    </div>
  );
}
