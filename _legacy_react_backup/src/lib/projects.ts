import project1 from "@/assets/project-1.jpg";
import project2 from "@/assets/project-2.jpg";
import project3 from "@/assets/project-3.jpg";
import project4 from "@/assets/project-4.jpg";

export type ProjectStatus = "Ongoing" | "Completed" | "Upcoming";

export type ProjectRecord = {
  id: string;
  title: string;
  location: string;
  category: string;
  status: ProjectStatus;
  image: string;
  overview: string;
  features: string[];
  highlights: { label: string; value: string }[];
  milestones: { title: string; date: string; done: boolean }[];
  gallery: string[];
  progress: number;
};

export const projects: ProjectRecord[] = [
  {
    id: "azure-towers",
    title: "Azure Towers",
    location: "Victoria Island, Lagos",
    category: "Residential",
    status: "Ongoing",
    image: project1,
    overview:
      "Azure Towers is a landmark twin-tower residential development designed for panoramic waterfront living, premium amenities, and investor-grade long-term value in Victoria Island.",
    features: [
      "24/7 concierge service",
      "Infinity pool and wellness deck",
      "Private cinema and residents' lounge",
      "Smart home systems",
      "Underground parking",
      "Landscaped podium gardens",
      "Fitness center",
      "Children's play area",
    ],
    highlights: [
      { label: "Units", value: "120" },
      { label: "Floors", value: "20" },
      { label: "Completion", value: "Q4 2027" },
      { label: "Type", value: "Luxury Apartments" },
    ],
    milestones: [
      { title: "Foundation Complete", date: "March 2025", done: true },
      { title: "Structure Complete", date: "December 2025", done: true },
      { title: "Interior Works", date: "June 2026", done: false },
      { title: "Final Handover", date: "December 2027", done: false },
    ],
    gallery: [project1, project2, project3, project4],
    progress: 45,
  },
  {
    id: "meridian-plaza",
    title: "Meridian Plaza",
    location: "Ikoyi, Lagos",
    category: "Commercial",
    status: "Completed",
    image: project2,
    overview:
      "Meridian Plaza delivers premium office and retail space with efficient floorplates, a polished arrival experience, and infrastructure tailored for blue-chip tenants.",
    features: [
      "Grade-A office floors",
      "Dedicated executive parking",
      "Retail activation at ground level",
      "High-speed vertical transportation",
      "Backup power and plant redundancy",
      "Professional facilities management",
    ],
    highlights: [
      { label: "Suites", value: "48" },
      { label: "Floors", value: "12" },
      { label: "Completion", value: "Delivered" },
      { label: "Type", value: "Commercial" },
    ],
    milestones: [
      { title: "Design Approval", date: "February 2022", done: true },
      { title: "Construction Delivery", date: "November 2024", done: true },
      { title: "Tenant Fit-Out", date: "January 2025", done: true },
      { title: "Operational Launch", date: "March 2025", done: true },
    ],
    gallery: [project2, project3, project4, project1],
    progress: 100,
  },
  {
    id: "crown-heights",
    title: "Crown Heights",
    location: "Lekki Phase 1, Lagos",
    category: "Mixed-Use",
    status: "Upcoming",
    image: project3,
    overview:
      "Crown Heights is a mixed-use address combining residential comfort, flexible retail frontage, and lifestyle amenities in one tightly curated urban destination.",
    features: [
      "Mixed-use planning model",
      "Lifestyle retail frontage",
      "Secure residential access",
      "Shared leisure amenities",
      "Flexible unit mix",
      "Premium finishing palette",
    ],
    highlights: [
      { label: "Blocks", value: "4" },
      { label: "Launch", value: "Q3 2026" },
      { label: "Completion", value: "Q1 2029" },
      { label: "Type", value: "Mixed-Use" },
    ],
    milestones: [
      { title: "Concept Design", date: "January 2026", done: true },
      { title: "Planning Approval", date: "July 2026", done: false },
      { title: "Groundbreaking", date: "October 2026", done: false },
      { title: "Handover", date: "March 2029", done: false },
    ],
    gallery: [project3, project4, project1, project2],
    progress: 10,
  },
  {
    id: "the-penthouse",
    title: "The Penthouse Collection",
    location: "Banana Island, Lagos",
    category: "Luxury Residential",
    status: "Completed",
    image: project4,
    overview:
      "The Penthouse Collection is a boutique offering of ultra-premium residences with private lift access, expansive terraces, and bespoke interior finishes.",
    features: [
      "Private lift access",
      "Roof terraces with skyline views",
      "Chef-grade kitchen specification",
      "Custom joinery and stone finishes",
      "Resident wellness suite",
      "Discrete security design",
    ],
    highlights: [
      { label: "Residences", value: "16" },
      { label: "Floors", value: "8" },
      { label: "Completion", value: "Delivered" },
      { label: "Type", value: "Ultra Luxury" },
    ],
    milestones: [
      { title: "Land Acquisition", date: "May 2021", done: true },
      { title: "Structure Delivery", date: "August 2023", done: true },
      { title: "Interior Completion", date: "April 2024", done: true },
      { title: "Client Handover", date: "July 2024", done: true },
    ],
    gallery: [project4, project1, project2, project3],
    progress: 100,
  },
  {
    id: "harbor-view",
    title: "Harbor View Estate",
    location: "Lekki, Lagos",
    category: "Residential",
    status: "Ongoing",
    image: project1,
    overview:
      "Harbor View Estate is a family-focused residential community with strong site infrastructure, landscaped streetscapes, and phased delivery for steady investor absorption.",
    features: [
      "Gated estate planning",
      "Clubhouse and family recreation",
      "Reliable utility infrastructure",
      "Phased development rollout",
      "Pedestrian-friendly streets",
      "Secure neighborhood access",
    ],
    highlights: [
      { label: "Homes", value: "84" },
      { label: "Phase", value: "Phase 2" },
      { label: "Completion", value: "Q2 2027" },
      { label: "Type", value: "Estate Housing" },
    ],
    milestones: [
      { title: "Site Infrastructure", date: "September 2024", done: true },
      { title: "Phase 1 Delivery", date: "February 2026", done: true },
      { title: "Phase 2 Build-Out", date: "October 2026", done: false },
      { title: "Estate Completion", date: "June 2027", done: false },
    ],
    gallery: [project1, project3, project2, project4],
    progress: 65,
  },
  {
    id: "commerce-tower",
    title: "Commerce Tower",
    location: "Victoria Island, Lagos",
    category: "Commercial",
    status: "Ongoing",
    image: project3,
    overview:
      "Commerce Tower is a high-visibility commercial project engineered for finance, professional services, and enterprise tenants seeking prestige and efficiency.",
    features: [
      "Enterprise-ready office floorplates",
      "Conference and meeting suites",
      "Smart building management systems",
      "High-capacity parking provision",
      "Executive drop-off sequence",
      "Tenant-ready connectivity backbone",
    ],
    highlights: [
      { label: "Floors", value: "15" },
      { label: "Leasing", value: "62%" },
      { label: "Completion", value: "Q1 2028" },
      { label: "Type", value: "Commercial" },
    ],
    milestones: [
      { title: "Core Structure", date: "December 2025", done: true },
      { title: "Facade Package", date: "May 2026", done: false },
      { title: "MEP Commissioning", date: "November 2027", done: false },
      { title: "Tenant Occupancy", date: "March 2028", done: false },
    ],
    gallery: [project3, project2, project1, project4],
    progress: 52,
  },
  {
    id: "sunrise-villas",
    title: "Sunrise Villas",
    location: "Ajah, Lagos",
    category: "Residential",
    status: "Upcoming",
    image: project2,
    overview:
      "Sunrise Villas is an upcoming villa enclave focused on spacious layouts, calm residential living, and efficient infrastructure for growing families and long-stay residents.",
    features: [
      "Detached and semi-detached homes",
      "Private garden setbacks",
      "Estate-wide drainage strategy",
      "Family-centered amenity cluster",
      "Flexible purchase structure",
      "Secure neighborhood planning",
    ],
    highlights: [
      { label: "Homes", value: "36" },
      { label: "Launch", value: "Q4 2026" },
      { label: "Completion", value: "Q4 2028" },
      { label: "Type", value: "Villa Estate" },
    ],
    milestones: [
      { title: "Masterplan Finalization", date: "April 2026", done: true },
      { title: "Sales Launch", date: "October 2026", done: false },
      { title: "Civil Works", date: "January 2027", done: false },
      { title: "Estate Delivery", date: "December 2028", done: false },
    ],
    gallery: [project2, project1, project4, project3],
    progress: 5,
  },
  {
    id: "royal-courts",
    title: "Royal Courts",
    location: "Ikoyi, Lagos",
    category: "Luxury Residential",
    status: "Completed",
    image: project4,
    overview:
      "Royal Courts is a completed premium residential address tailored for private owners who value understated luxury, calm interiors, and high-trust property delivery.",
    features: [
      "Private residence lobby",
      "Refined interior palette",
      "Backup utilities and services",
      "Secure basement parking",
      "Resident lounge and gym",
      "Low-density planning",
    ],
    highlights: [
      { label: "Residences", value: "24" },
      { label: "Floors", value: "10" },
      { label: "Completion", value: "Delivered" },
      { label: "Type", value: "Luxury Residential" },
    ],
    milestones: [
      { title: "Construction Start", date: "March 2021", done: true },
      { title: "Envelope Completion", date: "December 2022", done: true },
      { title: "Finishing Works", date: "July 2023", done: true },
      { title: "Owner Handover", date: "October 2023", done: true },
    ],
    gallery: [project4, project3, project2, project1],
    progress: 100,
  },
];

export const featuredProjects = projects.slice(0, 3);

export function getProjectById(id?: string) {
  return projects.find((project) => project.id === id);
}
