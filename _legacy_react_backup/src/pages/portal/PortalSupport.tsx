import { PortalLayout } from "@/components/layout/PortalLayout";
import { HelpCircle, Phone, Mail, MessageSquare, Clock, ChevronDown, Send } from "lucide-react";
import { useState } from "react";
import { toast } from "@/components/ui/sonner";

const faqs = [
  { q: "How do I check my project progress?", a: "Navigate to 'My Projects' from the sidebar, select your project, and view the progress bar and timeline updates." },
  { q: "How can I download my documents?", a: "Go to the 'Documents' page, find the file you need, and click the download icon." },
  { q: "How are payments tracked?", a: "Visit the 'Balance Sheet' page to see all payments, outstanding amounts, and installment history." },
  { q: "What if I notice incorrect information?", a: "Contact your assigned account manager or submit a message through this support page." },
];

export default function PortalSupport() {
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const [subject, setSubject] = useState("General Inquiry");
  const [message, setMessage] = useState("");

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    if (!message.trim()) {
      toast.error("Add a message before sending your request.");
      return;
    }

    toast.success("Your support message has been sent.");
    setMessage("");
    setSubject("General Inquiry");
  };

  return (
    <PortalLayout>
      <div className="max-w-3xl mx-auto space-y-8">
        <div>
          <h1 className="font-display text-2xl font-semibold">Support</h1>
          <p className="text-sm text-muted-foreground mt-1">Get help with your portal, projects, or account</p>
        </div>

        {/* Account Manager */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Your Account Manager</h2>
          <div className="flex items-center gap-4">
            <div className="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-lg font-bold text-primary">FA</div>
            <div>
              <p className="font-medium">Folake Adeyemi</p>
              <p className="text-xs text-muted-foreground">Senior Client Relations Manager</p>
              <div className="flex gap-4 mt-2">
                <a href="tel:+2348012345678" className="text-xs text-primary flex items-center gap-1 hover:underline"><Phone className="w-3 h-3" /> +234 801 234 5678</a>
                <a href="mailto:folake@rockmould.com" className="text-xs text-primary flex items-center gap-1 hover:underline"><Mail className="w-3 h-3" /> folake@rockmould.com</a>
              </div>
            </div>
          </div>
        </div>

        {/* Contact Options */}
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          {[
            { icon: Phone, label: "Call Us", value: "+234 123 456 7890", sub: "Mon-Fri, 9am-5pm" },
            { icon: Mail, label: "Email", value: "support@rockmould.com", sub: "24hr response time" },
            { icon: Clock, label: "Office Hours", value: "9:00 AM - 5:00 PM", sub: "Monday to Friday" },
          ].map((c) => (
            <div key={c.label} className="luxury-card text-center">
              <c.icon className="w-6 h-6 text-primary mx-auto mb-2" />
              <p className="text-xs uppercase tracking-wider text-muted-foreground">{c.label}</p>
              <p className="text-sm font-medium mt-1">{c.value}</p>
              <p className="text-xs text-muted-foreground">{c.sub}</p>
            </div>
          ))}
        </div>

        {/* Message Form */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Send a Message</h2>
          <form className="space-y-4" onSubmit={handleSubmit}>
            <div>
              <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Subject</label>
              <select value={subject} onChange={(event) => setSubject(event.target.value)} className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50">
                <option>General Inquiry</option>
                <option>Payment Question</option>
                <option>Project Update Request</option>
                <option>Document Request</option>
                <option>Technical Issue</option>
              </select>
            </div>
            <div>
              <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Message</label>
              <textarea value={message} onChange={(e) => setMessage(e.target.value)} rows={4} placeholder="Describe your question or issue..." className="w-full bg-muted border border-border rounded-sm px-4 py-2.5 text-sm focus:outline-none focus:border-primary/50 resize-none" />
            </div>
            <button type="submit" className="luxury-btn-primary text-xs gap-2"><Send className="w-3.5 h-3.5" /> Send Message</button>
          </form>
        </div>

        {/* FAQ */}
        <div className="luxury-card">
          <h2 className="font-display text-lg font-semibold mb-4">Frequently Asked Questions</h2>
          <div className="space-y-2">
            {faqs.map((faq, i) => (
              <div key={i} className="border border-border/50 rounded-sm">
                <button onClick={() => setOpenFaq(openFaq === i ? null : i)} className="w-full flex items-center justify-between p-3 text-left">
                  <span className="text-sm font-medium flex items-center gap-2"><HelpCircle className="w-4 h-4 text-primary flex-shrink-0" />{faq.q}</span>
                  <ChevronDown className={`w-4 h-4 text-muted-foreground transition-transform ${openFaq === i ? "rotate-180" : ""}`} />
                </button>
                {openFaq === i && (
                  <div className="px-3 pb-3 text-xs text-muted-foreground leading-relaxed border-t border-border/50 pt-3 ml-6">{faq.a}</div>
                )}
              </div>
            ))}
          </div>
        </div>
      </div>
    </PortalLayout>
  );
}
