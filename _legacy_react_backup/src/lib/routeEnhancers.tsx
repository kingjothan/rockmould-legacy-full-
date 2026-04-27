import { useEffect } from "react";
import { useLocation } from "react-router-dom";
import { getArticleById } from "@/lib/articles";
import { getProjectById } from "@/lib/projects";

const SITE_NAME = "Rockmould Limited";
const DEFAULT_DESCRIPTION =
  "Premium real estate development, construction delivery, client portal access, and enterprise-grade project oversight from Rockmould Limited.";

type RouteMeta = {
  title: string;
  description: string;
};

function updateMetaTag(
  selector: string,
  attribute: "name" | "property",
  key: string,
  value: string,
) {
  let tag = document.querySelector<HTMLMetaElement>(selector);

  if (!tag) {
    tag = document.createElement("meta");
    tag.setAttribute(attribute, key);
    document.head.appendChild(tag);
  }

  tag.setAttribute("content", value);
}

function resolveMeta(pathname: string): RouteMeta {
  if (pathname.startsWith("/projects/")) {
    const project = getProjectById(pathname.split("/")[2]);

    return {
      title: project ? `${project.title} | ${SITE_NAME}` : `Project Details | ${SITE_NAME}`,
      description: project
        ? `${project.title} in ${project.location}. Review project highlights, progress milestones, amenities, and inquiry options.`
        : "Review Rockmould development details, construction progress, and investment information.",
    };
  }

  if (pathname.startsWith("/blog/")) {
    const article = getArticleById(pathname.split("/")[2]);

    return {
      title: article ? `${article.title} | ${SITE_NAME}` : `Insight Article | ${SITE_NAME}`,
      description: article
        ? article.excerpt
        : "Read Rockmould insight articles on real estate, construction, and investment strategy.",
    };
  }

  const routeMeta: Record<string, RouteMeta> = {
    "/": {
      title: SITE_NAME,
      description:
        "Discover Rockmould Limited's premium real estate developments, construction expertise, client portal, and investor-ready project experience.",
    },
    "/about": {
      title: `About ${SITE_NAME} | Legacy, Vision, and Leadership`,
      description:
        "Learn about Rockmould Limited's story, values, leadership team, and long-term vision for premium real estate and construction.",
    },
    "/services": {
      title: `Services | ${SITE_NAME}`,
      description:
        "Explore Rockmould's end-to-end services across real estate development, construction execution, project management, and advisory support.",
    },
    "/projects": {
      title: `Projects | ${SITE_NAME}`,
      description:
        "Browse Rockmould developments by location, category, and status across premium residential, commercial, and mixed-use projects.",
    },
    "/portfolio": {
      title: `Portfolio | ${SITE_NAME}`,
      description:
        "See Rockmould's portfolio of completed developments, construction case studies, and project impact highlights.",
    },
    "/blog": {
      title: `Insights | ${SITE_NAME}`,
      description:
        "Read Rockmould insights on real estate markets, investment strategy, company updates, and construction excellence.",
    },
    "/contact": {
      title: `Contact ${SITE_NAME}`,
      description:
        "Reach Rockmould Limited for consultations, project inquiries, partnerships, investor conversations, and client support.",
    },
    "/faq": {
      title: `FAQ | ${SITE_NAME}`,
      description:
        "Find answers about Rockmould projects, investment process, services, and client portal access.",
    },
    "/careers": {
      title: `Careers | ${SITE_NAME}`,
      description:
        "Explore open roles, company culture, and career opportunities at Rockmould Limited.",
    },
    "/partnerships": {
      title: `Partnerships and Investment | ${SITE_NAME}`,
      description:
        "Discover Rockmould's investor and partnership offering, governance approach, and private consultation pathway.",
    },
    "/testimonials": {
      title: `Client Trust | ${SITE_NAME}`,
      description:
        "Review Rockmould client testimonials, success stories, and trust indicators across delivered developments.",
    },
    "/gallery": {
      title: `Gallery | ${SITE_NAME}`,
      description:
        "View Rockmould project visuals, construction media, and curated development imagery.",
    },
    "/portal": {
      title: `Client Portal | ${SITE_NAME}`,
      description:
        "Secure client access for project updates, balances, documents, media, and support.",
    },
    "/portal/dashboard": {
      title: `Portal Dashboard | ${SITE_NAME}`,
      description:
        "Track active projects, balances, updates, and key client records inside the Rockmould portal.",
    },
    "/portal/projects": {
      title: `My Projects | ${SITE_NAME}`,
      description:
        "Review assigned Rockmould projects, progress, balances, and recent activity.",
    },
    "/portal/documents": {
      title: `Portal Documents | ${SITE_NAME}`,
      description:
        "Access Rockmould project documents, statements, receipts, agreements, and downloadable records.",
    },
    "/portal/balance": {
      title: `Balance Sheet | ${SITE_NAME}`,
      description:
        "View payment history, outstanding balances, project totals, and installment records in the client portal.",
    },
    "/portal/profile": {
      title: `Profile | ${SITE_NAME}`,
      description:
        "Review client profile details, linked projects, and verification status.",
    },
    "/portal/media": {
      title: `Portal Media | ${SITE_NAME}`,
      description:
        "Browse project media, construction photography, and client-facing visual updates.",
    },
    "/portal/updates": {
      title: `Portal Updates | ${SITE_NAME}`,
      description:
        "Follow project milestones, admin updates, and construction timeline entries.",
    },
    "/portal/support": {
      title: `Portal Support | ${SITE_NAME}`,
      description:
        "Contact your Rockmould support team and submit portal-related questions.",
    },
    "/admin/login": {
      title: `Admin Login | ${SITE_NAME}`,
      description:
        "Administrative access for Rockmould's internal project, client, content, and operations platform.",
    },
    "/admin": {
      title: `Admin Dashboard | ${SITE_NAME}`,
      description:
        "Monitor Rockmould operations, clients, projects, KYC requests, and content workflows from the admin dashboard.",
    },
    "/admin/clients": {
      title: `Client Management | ${SITE_NAME}`,
      description:
        "Manage Rockmould client profiles, linked projects, balances, and activity records.",
    },
    "/admin/kyc": {
      title: `KYC Requests | ${SITE_NAME}`,
      description:
        "Review and action pending KYC submissions for new client portal access.",
    },
    "/admin/projects": {
      title: `Project Management | ${SITE_NAME}`,
      description:
        "Manage project records, assignments, delivery status, budgets, and progress updates.",
    },
    "/admin/payments": {
      title: `Payments | ${SITE_NAME}`,
      description:
        "Track payment records, outstanding balances, confirmations, and financial summaries.",
    },
    "/admin/documents": {
      title: `Documents | ${SITE_NAME}`,
      description:
        "Organize client and project documents, agreements, statements, and receipts.",
    },
    "/admin/media": {
      title: `Media Management | ${SITE_NAME}`,
      description:
        "Upload, tag, feature, and manage project photos and videos in the Rockmould admin system.",
    },
    "/admin/updates": {
      title: `Timeline Updates | ${SITE_NAME}`,
      description:
        "Create and manage project timeline updates, progress reports, and client-visible notes.",
    },
    "/admin/settings": {
      title: `Settings | ${SITE_NAME}`,
      description:
        "Manage company details, portal settings, brand assets, and global website configuration.",
    },
    "/admin/cms/homepage": {
      title: `Homepage CMS | ${SITE_NAME}`,
      description:
        "Manage homepage hero content, section ordering, featured projects, and visual highlights.",
    },
    "/admin/cms/blog": {
      title: `Blog CMS | ${SITE_NAME}`,
      description:
        "Create, edit, publish, and manage Rockmould insight articles and categories.",
    },
    "/admin/cms/testimonials": {
      title: `Testimonials CMS | ${SITE_NAME}`,
      description:
        "Manage public trust content, testimonials, and visibility settings.",
    },
    "/admin/cms/faq": {
      title: `FAQ CMS | ${SITE_NAME}`,
      description:
        "Edit support categories, questions, answers, and publication order.",
    },
    "/admin/cms/careers": {
      title: `Careers CMS | ${SITE_NAME}`,
      description:
        "Manage job postings, departments, hiring states, and role visibility.",
    },
    "/admin/forms": {
      title: `Form Submissions | ${SITE_NAME}`,
      description:
        "Review incoming consultation requests, contact forms, newsletter leads, and support messages.",
    },
    "/admin/notifications": {
      title: `Notifications | ${SITE_NAME}`,
      description:
        "Track internal alerts for KYC, payments, uploads, and website activity.",
    },
    "/admin/roles": {
      title: `Roles and Permissions | ${SITE_NAME}`,
      description:
        "Manage internal roles, access scope, and permission structures.",
    },
    "/admin/activity": {
      title: `Activity Log | ${SITE_NAME}`,
      description:
        "Review a readable audit trail of updates made across the Rockmould admin platform.",
    },
    "/privacy": {
      title: `Privacy Policy | ${SITE_NAME}`,
      description:
        "Read Rockmould Limited's privacy policy for website visitors, clients, and portal users.",
    },
    "/terms": {
      title: `Terms of Service | ${SITE_NAME}`,
      description:
        "Review Rockmould Limited's website and portal terms of service.",
    },
  };

  return (
    routeMeta[pathname] ?? {
      title: `Page Not Found | ${SITE_NAME}`,
      description: DEFAULT_DESCRIPTION,
    }
  );
}

export function RouteMetadata() {
  const location = useLocation();

  useEffect(() => {
    const meta = resolveMeta(location.pathname);

    document.title = meta.title;

    updateMetaTag('meta[name="description"]', "name", "description", meta.description);
    updateMetaTag('meta[property="og:title"]', "property", "og:title", meta.title);
    updateMetaTag(
      'meta[property="og:description"]',
      "property",
      "og:description",
      meta.description,
    );
    updateMetaTag('meta[name="twitter:title"]', "name", "twitter:title", meta.title);
    updateMetaTag(
      'meta[name="twitter:description"]',
      "name",
      "twitter:description",
      meta.description,
    );
  }, [location.pathname]);

  return null;
}

export function ScrollManager() {
  const location = useLocation();

  useEffect(() => {
    const timer = window.setTimeout(() => {
      if (location.hash) {
        const target = document.getElementById(decodeURIComponent(location.hash.slice(1)));

        if (target) {
          const top = target.getBoundingClientRect().top + window.scrollY - 112;
          window.scrollTo({ top, behavior: "smooth" });
          return;
        }
      }

      window.scrollTo({ top: 0, left: 0, behavior: "auto" });
    }, 0);

    return () => window.clearTimeout(timer);
  }, [location.pathname, location.hash]);

  return null;
}
