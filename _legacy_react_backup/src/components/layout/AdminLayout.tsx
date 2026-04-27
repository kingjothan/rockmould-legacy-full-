import { ReactNode, useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import {
  LayoutDashboard,
  Users,
  ShieldCheck,
  FolderKanban,
  Receipt,
  FileText,
  Image,
  Clock,
  Globe,
  BookOpen,
  MessageSquare,
  HelpCircle,
  Briefcase,
  Bell,
  Settings,
  Lock,
  Activity,
  LogOut,
  ChevronLeft,
  Menu,
  X,
} from "lucide-react";
import { AnimatePresence, motion } from "framer-motion";

const adminNav = [
  { section: "Overview", items: [{ label: "Dashboard", path: "/admin", icon: LayoutDashboard }] },
  {
    section: "Management",
    items: [
      { label: "Clients", path: "/admin/clients", icon: Users },
      { label: "KYC Requests", path: "/admin/kyc", icon: ShieldCheck },
      { label: "Projects", path: "/admin/projects", icon: FolderKanban },
      { label: "Payments", path: "/admin/payments", icon: Receipt },
      { label: "Documents", path: "/admin/documents", icon: FileText },
      { label: "Media", path: "/admin/media", icon: Image },
      { label: "Updates", path: "/admin/updates", icon: Clock },
    ],
  },
  {
    section: "Website CMS",
    items: [
      { label: "Homepage", path: "/admin/cms/homepage", icon: Globe },
      { label: "Blog", path: "/admin/cms/blog", icon: BookOpen },
      { label: "Testimonials", path: "/admin/cms/testimonials", icon: MessageSquare },
      { label: "FAQ", path: "/admin/cms/faq", icon: HelpCircle },
      { label: "Careers", path: "/admin/cms/careers", icon: Briefcase },
    ],
  },
  {
    section: "System",
    items: [
      { label: "Form Submissions", path: "/admin/forms", icon: MessageSquare },
      { label: "Notifications", path: "/admin/notifications", icon: Bell },
      { label: "Settings", path: "/admin/settings", icon: Settings },
      { label: "Roles", path: "/admin/roles", icon: Lock },
      { label: "Activity Log", path: "/admin/activity", icon: Activity },
    ],
  },
];

export function AdminLayout({ children }: { children: ReactNode }) {
  const location = useLocation();
  const [collapsed, setCollapsed] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);

  useEffect(() => {
    setMobileOpen(false);
  }, [location.pathname]);

  return (
    <div className="min-h-screen bg-background flex">
      <aside
        className={`hidden md:flex flex-col border-r border-border bg-card transition-all duration-300 overflow-y-auto ${
          collapsed ? "w-16" : "w-64"
        }`}
      >
        <div className="p-4 border-b border-border flex items-center justify-between sticky top-0 bg-card z-10">
          {!collapsed && (
            <Link to="/admin" className="flex items-center gap-2">
              <div className="w-8 h-8 rounded-sm bg-primary flex items-center justify-center">
                <span className="font-display text-primary-foreground text-sm font-bold">R</span>
              </div>
              <span className="font-display text-sm font-semibold">Admin CMS</span>
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
        <nav className="flex-1 py-2">
          {adminNav.map((group) => (
            <div key={group.section} className="mb-2">
              {!collapsed && (
                <p className="px-4 py-2 text-[10px] uppercase tracking-[0.2em] text-muted-foreground">
                  {group.section}
                </p>
              )}
              {group.items.map((item) => (
                <Link
                  key={item.path}
                  to={item.path}
                  className={`flex items-center gap-3 px-4 py-2 text-sm transition-colors ${
                    location.pathname === item.path
                      ? "text-primary bg-primary/5 border-r-2 border-primary"
                      : "text-muted-foreground hover:text-foreground hover:bg-muted/50"
                  }`}
                >
                  <item.icon className="w-4 h-4 flex-shrink-0" />
                  {!collapsed && <span>{item.label}</span>}
                </Link>
              ))}
            </div>
          ))}
        </nav>
        <div className="p-4 border-t border-border sticky bottom-0 bg-card">
          <Link
            to="/"
            className={`flex items-center gap-3 text-sm text-muted-foreground hover:text-destructive ${
              collapsed ? "justify-center" : ""
            }`}
          >
            <LogOut className="w-4 h-4" />
            {!collapsed && <span>Logout</span>}
          </Link>
        </div>
      </aside>
      <div className="flex-1 flex flex-col min-w-0">
        <header className="h-14 border-b border-border bg-card/50 flex items-center px-6 gap-4 sticky top-0 z-10">
          <button
            className="md:hidden text-muted-foreground"
            onClick={() => setMobileOpen(true)}
            aria-label="Open admin navigation"
          >
            <Menu className="w-5 h-5" />
          </button>
          <h1 className="text-sm font-medium">Admin Dashboard</h1>
          <div className="ml-auto flex items-center gap-3">
            <button className="relative p-2 text-muted-foreground hover:text-foreground">
              <Bell className="w-4 h-4" />
              <span className="absolute top-1 right-1 w-2 h-2 bg-destructive rounded-full" />
            </button>
            <div className="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-primary-foreground">
              A
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
              className="fixed inset-y-0 left-0 z-50 w-72 overflow-y-auto border-r border-border bg-card md:hidden"
            >
              <div className="flex items-center justify-between p-4 border-b border-border sticky top-0 bg-card">
                <Link to="/admin" className="flex items-center gap-2">
                  <div className="w-8 h-8 rounded-sm bg-primary flex items-center justify-center">
                    <span className="font-display text-primary-foreground text-sm font-bold">R</span>
                  </div>
                  <span className="font-display text-sm font-semibold">Admin CMS</span>
                </Link>
                <button
                  onClick={() => setMobileOpen(false)}
                  aria-label="Close admin navigation"
                  className="text-muted-foreground"
                >
                  <X className="w-4 h-4" />
                </button>
              </div>
              <nav className="py-2">
                {adminNav.map((group) => (
                  <div key={group.section} className="mb-2">
                    <p className="px-4 py-2 text-[10px] uppercase tracking-[0.2em] text-muted-foreground">
                      {group.section}
                    </p>
                    {group.items.map((item) => (
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
                  </div>
                ))}
              </nav>
              <div className="p-4 border-t border-border sticky bottom-0 bg-card">
                <Link
                  to="/"
                  className="flex items-center gap-3 text-sm text-muted-foreground hover:text-destructive"
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
