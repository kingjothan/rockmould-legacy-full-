import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { Toaster } from "@/components/ui/toaster";
import { TooltipProvider } from "@/components/ui/tooltip";

import Index from "./pages/Index";
import About from "./pages/About";
import Services from "./pages/Services";
import Projects from "./pages/Projects";
import ProjectDetail from "./pages/ProjectDetail";
import Portfolio from "./pages/Portfolio";
import Blog from "./pages/Blog";
import BlogPost from "./pages/BlogPost";
import Contact from "./pages/Contact";
import FAQ from "./pages/FAQ";
import Careers from "./pages/Careers";
import Partnerships from "./pages/Partnerships";
import Testimonials from "./pages/Testimonials";
import Gallery from "./pages/Gallery";
import NotFound from "./pages/NotFound";
import PrivacyPolicy from "./pages/PrivacyPolicy";
import TermsOfService from "./pages/TermsOfService";

import PortalLogin from "./pages/portal/PortalLogin";
import PortalDashboard from "./pages/portal/PortalDashboard";
import PortalProjects from "./pages/portal/PortalProjects";
import PortalDocuments from "./pages/portal/PortalDocuments";
import PortalBalance from "./pages/portal/PortalBalance";
import PortalProfile from "./pages/portal/PortalProfile";
import PortalMedia from "./pages/portal/PortalMedia";
import PortalUpdates from "./pages/portal/PortalUpdates";
import PortalSupport from "./pages/portal/PortalSupport";

import AdminLogin from "./pages/admin/AdminLogin";
import AdminDashboard from "./pages/admin/AdminDashboard";
import AdminClients from "./pages/admin/AdminClients";
import AdminKYC from "./pages/admin/AdminKYC";
import AdminProjects from "./pages/admin/AdminProjects";
import AdminPayments from "./pages/admin/AdminPayments";
import AdminDocuments from "./pages/admin/AdminDocuments";
import AdminMedia from "./pages/admin/AdminMedia";
import AdminUpdates from "./pages/admin/AdminUpdates";
import AdminSettings from "./pages/admin/AdminSettings";
import AdminCMSHomepage from "./pages/admin/AdminCMSHomepage";
import AdminCMSBlog from "./pages/admin/AdminCMSBlog";
import AdminCMSTestimonials from "./pages/admin/AdminCMSTestimonials";
import AdminCMSFaq from "./pages/admin/AdminCMSFaq";
import AdminCMSCareers from "./pages/admin/AdminCMSCareers";
import AdminForms from "./pages/admin/AdminForms";
import AdminNotifications from "./pages/admin/AdminNotifications";
import AdminRoles from "./pages/admin/AdminRoles";
import AdminActivity from "./pages/admin/AdminActivity";
import { RouteMetadata, ScrollManager } from "./lib/routeEnhancers";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <RouteMetadata />
        <ScrollManager />
        <Routes>
          {/* Public */}
          <Route path="/" element={<Index />} />
          <Route path="/about" element={<About />} />
          <Route path="/services" element={<Services />} />
          <Route path="/projects" element={<Projects />} />
          <Route path="/projects/:id" element={<ProjectDetail />} />
          <Route path="/portfolio" element={<Portfolio />} />
          <Route path="/blog" element={<Blog />} />
          <Route path="/blog/:id" element={<BlogPost />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/faq" element={<FAQ />} />
          <Route path="/careers" element={<Careers />} />
          <Route path="/partnerships" element={<Partnerships />} />
          <Route path="/testimonials" element={<Testimonials />} />
          <Route path="/gallery" element={<Gallery />} />
          <Route path="/privacy" element={<PrivacyPolicy />} />
          <Route path="/terms" element={<TermsOfService />} />

          {/* Client Portal */}
          <Route path="/portal" element={<PortalLogin />} />
          <Route path="/portal/dashboard" element={<PortalDashboard />} />
          <Route path="/portal/projects" element={<PortalProjects />} />
          <Route path="/portal/documents" element={<PortalDocuments />} />
          <Route path="/portal/balance" element={<PortalBalance />} />
          <Route path="/portal/profile" element={<PortalProfile />} />
          <Route path="/portal/media" element={<PortalMedia />} />
          <Route path="/portal/updates" element={<PortalUpdates />} />
          <Route path="/portal/support" element={<PortalSupport />} />

          {/* Admin */}
          <Route path="/admin/login" element={<AdminLogin />} />
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/clients" element={<AdminClients />} />
          <Route path="/admin/kyc" element={<AdminKYC />} />
          <Route path="/admin/projects" element={<AdminProjects />} />
          <Route path="/admin/payments" element={<AdminPayments />} />
          <Route path="/admin/documents" element={<AdminDocuments />} />
          <Route path="/admin/media" element={<AdminMedia />} />
          <Route path="/admin/updates" element={<AdminUpdates />} />
          <Route path="/admin/settings" element={<AdminSettings />} />
          <Route path="/admin/cms/homepage" element={<AdminCMSHomepage />} />
          <Route path="/admin/cms/blog" element={<AdminCMSBlog />} />
          <Route path="/admin/cms/testimonials" element={<AdminCMSTestimonials />} />
          <Route path="/admin/cms/faq" element={<AdminCMSFaq />} />
          <Route path="/admin/cms/careers" element={<AdminCMSCareers />} />
          <Route path="/admin/forms" element={<AdminForms />} />
          <Route path="/admin/notifications" element={<AdminNotifications />} />
          <Route path="/admin/roles" element={<AdminRoles />} />
          <Route path="/admin/activity" element={<AdminActivity />} />

          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
