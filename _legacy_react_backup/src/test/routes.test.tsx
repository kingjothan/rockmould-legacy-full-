import { afterEach, describe, expect, it } from "vitest";
import { cleanup, render, screen, waitFor } from "@testing-library/react";
import App from "@/App";

const routeCases = [
  { path: "/", title: "Rockmould Limited", text: /Premium Real Estate & Construction/i },
  {
    path: "/about",
    title: "About Rockmould Limited | Legacy, Vision, and Leadership",
    text: /Building Tomorrow's Landmarks, Today/i,
  },
  {
    path: "/services",
    title: "Services | Rockmould Limited",
    text: /Comprehensive Solutions for Premium Properties/i,
  },
  { path: "/projects", title: "Projects | Rockmould Limited", text: /Our Projects/i },
  {
    path: "/projects/azure-towers",
    title: "Azure Towers | Rockmould Limited",
    text: /Azure Towers/i,
  },
  { path: "/portfolio", title: "Portfolio | Rockmould Limited", text: /Our Portfolio/i },
  { path: "/blog", title: "Insights | Rockmould Limited", text: /Insights & Resources/i },
  {
    path: "/blog/future-luxury",
    title: "The Future of Luxury Real Estate in Lagos | Rockmould Limited",
    text: /The Future of Luxury Real Estate in Lagos/i,
  },
  {
    path: "/contact",
    title: "Contact Rockmould Limited",
    text: /Get in Touch/i,
  },
  { path: "/faq", title: "FAQ | Rockmould Limited", text: /Frequently Asked Questions/i },
  {
    path: "/careers",
    title: "Careers | Rockmould Limited",
    text: /Build Your Career with Rockmould/i,
  },
  {
    path: "/partnerships",
    title: "Partnerships and Investment | Rockmould Limited",
    text: /Invest & Partner with Confidence/i,
  },
  {
    path: "/testimonials",
    title: "Client Trust | Rockmould Limited",
    text: /Voices of Trust/i,
  },
  { path: "/gallery", title: "Gallery | Rockmould Limited", text: /Gallery & Media/i },
  {
    path: "/privacy",
    title: "Privacy Policy | Rockmould Limited",
    text: /Privacy Policy/i,
  },
  {
    path: "/terms",
    title: "Terms of Service | Rockmould Limited",
    text: /Terms of Service/i,
  },
  {
    path: "/portal",
    title: "Client Portal | Rockmould Limited",
    text: /Welcome Back/i,
  },
  {
    path: "/portal/dashboard",
    title: "Portal Dashboard | Rockmould Limited",
    text: /Welcome Back, Adebayo/i,
  },
  {
    path: "/portal/projects",
    title: "My Projects | Rockmould Limited",
    text: /My Projects/i,
  },
  {
    path: "/portal/documents",
    title: "Portal Documents | Rockmould Limited",
    text: /Documents/i,
  },
  {
    path: "/portal/balance",
    title: "Balance Sheet | Rockmould Limited",
    text: /Balance Sheet/i,
  },
  {
    path: "/portal/profile",
    title: "Profile | Rockmould Limited",
    text: /My Profile/i,
  },
  {
    path: "/portal/media",
    title: "Portal Media | Rockmould Limited",
    text: /Media Gallery/i,
  },
  {
    path: "/portal/updates",
    title: "Portal Updates | Rockmould Limited",
    text: /Project Updates/i,
  },
  {
    path: "/portal/support",
    title: "Portal Support | Rockmould Limited",
    text: /Support/i,
  },
  {
    path: "/admin/login",
    title: "Admin Login | Rockmould Limited",
    text: /Admin Login/i,
  },
  {
    path: "/admin",
    title: "Admin Dashboard | Rockmould Limited",
    text: /Dashboard Overview/i,
  },
  {
    path: "/admin/clients",
    title: "Client Management | Rockmould Limited",
    text: /Client Management/i,
  },
  {
    path: "/admin/kyc",
    title: "KYC Requests | Rockmould Limited",
    text: /KYC Requests/i,
  },
  {
    path: "/admin/projects",
    title: "Project Management | Rockmould Limited",
    text: /Project Management/i,
  },
  {
    path: "/admin/payments",
    title: "Payments | Rockmould Limited",
    text: /Payments/i,
  },
  {
    path: "/admin/documents",
    title: "Documents | Rockmould Limited",
    text: /Documents/i,
  },
  {
    path: "/admin/media",
    title: "Media Management | Rockmould Limited",
    text: /Media Library/i,
  },
  {
    path: "/admin/updates",
    title: "Timeline Updates | Rockmould Limited",
    text: /Timeline & Updates/i,
  },
  {
    path: "/admin/settings",
    title: "Settings | Rockmould Limited",
    text: /Settings/i,
  },
  {
    path: "/admin/cms/homepage",
    title: "Homepage CMS | Rockmould Limited",
    text: /Homepage CMS/i,
  },
  {
    path: "/admin/cms/blog",
    title: "Blog CMS | Rockmould Limited",
    text: /Blog CMS/i,
  },
  {
    path: "/admin/cms/testimonials",
    title: "Testimonials CMS | Rockmould Limited",
    text: /Testimonials CMS/i,
  },
  {
    path: "/admin/cms/faq",
    title: "FAQ CMS | Rockmould Limited",
    text: /FAQ CMS/i,
  },
  {
    path: "/admin/cms/careers",
    title: "Careers CMS | Rockmould Limited",
    text: /Careers CMS/i,
  },
  {
    path: "/admin/forms",
    title: "Form Submissions | Rockmould Limited",
    text: /Form Submissions/i,
  },
  {
    path: "/admin/notifications",
    title: "Notifications | Rockmould Limited",
    text: /Notifications/i,
  },
  {
    path: "/admin/roles",
    title: "Roles and Permissions | Rockmould Limited",
    text: /Roles & Permissions/i,
  },
  {
    path: "/admin/activity",
    title: "Activity Log | Rockmould Limited",
    text: /Activity Log/i,
  },
  {
    path: "/does-not-exist",
    title: "Page Not Found | Rockmould Limited",
    text: /Page Not Found/i,
  },
];

afterEach(() => {
  cleanup();
});

describe("application routes", () => {
  for (const routeCase of routeCases) {
    it(`renders ${routeCase.path}`, async () => {
      window.history.pushState({}, "", routeCase.path);

      render(<App />);

      const matches = await screen.findAllByText(routeCase.text);
      expect(matches.length).toBeGreaterThan(0);

      await waitFor(() => {
        expect(document.title).toBe(routeCase.title);
      });
    });
  }
});
