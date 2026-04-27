export type ArticleRecord = {
  id: string;
  title: string;
  excerpt: string;
  category: string;
  date: string;
  readTime: string;
  author: string;
  featured?: boolean;
  intro: string;
  quote?: string;
  sections: {
    heading: string;
    paragraphs: string[];
  }[];
};

export const articles: ArticleRecord[] = [
  {
    id: "future-luxury",
    title: "The Future of Luxury Real Estate in Lagos",
    excerpt:
      "An in-depth look at trends shaping premium property markets in Nigeria's commercial capital.",
    category: "Market Insight",
    date: "March 15, 2026",
    readTime: "8 min",
    author: "Rockmould Editorial",
    featured: true,
    intro:
      "Lagos continues to evolve as a prime destination for luxury real estate investment. The confluence of urban growth, rising private wealth, and demand for curated living environments has created sustained momentum in the premium property market.",
    quote:
      "The Lagos luxury market is moving beyond pure square footage toward service, experience, and long-term lifestyle value.",
    sections: [
      {
        heading: "The Shifting Landscape",
        paragraphs: [
          "Prime districts such as Banana Island, Ikoyi, Victoria Island, and Lekki Phase 1 remain core anchors of demand, but buyer expectations have changed materially. Product quality, finishing consistency, service model, and neighborhood identity now influence value more than brand claims alone.",
          "Developers that combine disciplined delivery with strong post-handover management are increasingly winning the confidence of both occupiers and investors.",
        ],
      },
      {
        heading: "What Premium Buyers Now Expect",
        paragraphs: [
          "The strongest-performing developments are integrating smart-home readiness, amenity-led living, dependable utilities, wellness-focused design, and better community curation.",
          "In practical terms, premium is now measured by daily experience as much as by address. That shift favors developers who can manage design quality and execution discipline at the same time.",
        ],
      },
      {
        heading: "Investment Outlook",
        paragraphs: [
          "For investors, the premium Lagos segment still presents compelling opportunities when location quality, absorption velocity, and delivery credibility are properly underwritten.",
          "Rockmould's view is that long-term performance will come from developments that balance prestige with operational resilience, not from spectacle alone.",
        ],
      },
    ],
  },
  {
    id: "sustainable-construction",
    title: "Sustainable Construction: Building for Tomorrow",
    excerpt:
      "How Rockmould integrates sustainable practices into premium developments.",
    category: "Industry",
    date: "February 28, 2026",
    readTime: "6 min",
    author: "Rockmould Editorial",
    intro:
      "Sustainability in premium construction is no longer a side conversation. It is becoming central to asset performance, occupant comfort, and long-term operating cost control.",
    sections: [
      {
        heading: "Efficiency Starts in Early Planning",
        paragraphs: [
          "Sustainable performance is shaped in the design stage through orientation, material selection, envelope strategy, and building systems planning.",
          "When those decisions happen early, developers can reduce lifecycle inefficiencies without compromising architectural quality.",
        ],
      },
      {
        heading: "Luxury and Sustainability Can Coexist",
        paragraphs: [
          "Water management, resilient power strategies, passive cooling, and durable finishes all support a more refined ownership experience when executed well.",
          "The goal is not to make premium spaces feel technical. The goal is to make them feel effortless because the infrastructure is working intelligently in the background.",
        ],
      },
    ],
  },
  {
    id: "investment-guide",
    title: "Investment Guide: Premium Properties in Nigeria",
    excerpt:
      "A comprehensive guide for investors looking at Nigeria's high-value real estate market.",
    category: "Advisory",
    date: "January 20, 2026",
    readTime: "10 min",
    author: "Rockmould Advisory",
    intro:
      "Premium property investment demands more than confidence in the market. It requires strong judgment around delivery risk, product-market fit, and exit resilience.",
    sections: [
      {
        heading: "Start with Sponsor Quality",
        paragraphs: [
          "The developer's delivery history, governance approach, and communication culture matter as much as the site itself.",
          "A premium opportunity can be weakened quickly by poor execution discipline, weak reporting, or avoidable delays.",
        ],
      },
      {
        heading: "Assess the Full Value Story",
        paragraphs: [
          "Investors should review location fundamentals, pricing logic, competitive stock, infrastructure quality, and the long-term operating profile of the asset.",
          "The most durable investments tend to combine strong buyer demand with practical livability and manageable maintenance complexity.",
        ],
      },
    ],
  },
  {
    id: "smart-homes",
    title: "Smart Homes: The New Standard in Luxury Living",
    excerpt:
      "Exploring how technology is transforming premium residential developments.",
    category: "Industry",
    date: "January 5, 2026",
    readTime: "5 min",
    author: "Rockmould Editorial",
    intro:
      "Smart-home capability is becoming baseline infrastructure in luxury residential projects rather than an optional extra.",
    sections: [
      {
        heading: "Technology Must Feel Invisible",
        paragraphs: [
          "The best smart-home systems simplify comfort, lighting, access control, and energy visibility without making the home feel over-engineered.",
          "Buyers increasingly value technology when it supports convenience, security, and flexibility in a reliable way.",
        ],
      },
      {
        heading: "Integration Matters More Than Features",
        paragraphs: [
          "Technology packages create more value when they are planned into the home from the outset and aligned with the design intent.",
          "That means infrastructure, serviceability, and user experience should be considered together rather than added late in the process.",
        ],
      },
    ],
  },
  {
    id: "q4-update",
    title: "Rockmould Q4 2025: Year in Review",
    excerpt:
      "A look back at our achievements, milestones, and growth over the past year.",
    category: "Company News",
    date: "December 30, 2025",
    readTime: "7 min",
    author: "Rockmould Communications",
    intro:
      "Q4 closed a year defined by delivery discipline, stronger client reporting, and continued expansion across premium residential and commercial opportunities.",
    sections: [
      {
        heading: "What Moved the Business Forward",
        paragraphs: [
          "Rockmould strengthened project reporting rhythms, improved internal coordination across design and execution teams, and expanded its public-facing digital experience.",
          "Those operational improvements support a better experience for clients, investors, and internal staff alike.",
        ],
      },
      {
        heading: "Looking Ahead",
        paragraphs: [
          "The focus for the year ahead remains clear: deliver with consistency, invest in systems that improve trust, and keep pushing product quality upward.",
          "That combination will matter more than volume as the premium market becomes more discerning.",
        ],
      },
    ],
  },
  {
    id: "property-management",
    title: "Property Management Excellence: A Client's Guide",
    excerpt:
      "Understanding the value of professional property management for premium assets.",
    category: "Advisory",
    date: "December 15, 2025",
    readTime: "6 min",
    author: "Rockmould Advisory",
    intro:
      "Professional property management protects value after delivery by sustaining building quality, tenant confidence, and operational order.",
    sections: [
      {
        heading: "Operations Protect Asset Quality",
        paragraphs: [
          "Premium assets can lose their positioning quickly when operations, maintenance, and occupant communication are handled inconsistently.",
          "A disciplined management model keeps the physical asset aligned with the original promise made at sale or lease.",
        ],
      },
      {
        heading: "What Clients Should Expect",
        paragraphs: [
          "Strong reporting, responsive issue resolution, maintenance planning, and service consistency are hallmarks of effective property management.",
          "Owners benefit most when governance is clear and the management team understands the standards expected of premium stock.",
        ],
      },
    ],
  },
];

export const featuredArticle = articles.find((article) => article.featured) ?? articles[0];
export const previewArticles = articles.slice(0, 3);

export function getArticleById(id?: string) {
  return articles.find((article) => article.id === id);
}
