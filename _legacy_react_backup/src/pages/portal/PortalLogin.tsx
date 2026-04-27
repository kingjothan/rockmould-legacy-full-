import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import { Phone, ArrowRight, Shield, FileText, BarChart3, Clock } from "lucide-react";

export default function PortalLogin() {
  const [phone, setPhone] = useState("");
  const [state, setState] = useState<"login" | "unrecognized" | "kyc" | "pending">("login");
  const navigate = useNavigate();

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault();
    const normalizedPhone = phone.replace(/\D/g, "");

    if (["08012345678", "2348012345678", "8012345678"].includes(normalizedPhone)) {
      navigate("/portal/dashboard");
      return;
    }

    setState("unrecognized");
  };

  return (
    <div className="min-h-screen bg-background flex">
      <div className="hidden lg:flex lg:w-1/2 bg-card items-center justify-center p-12 border-r border-border">
        <div className="max-w-md">
          <Link to="/" className="flex items-center gap-3 mb-12">
            <div className="w-10 h-10 rounded-sm bg-primary flex items-center justify-center"><span className="font-display text-primary-foreground text-lg font-bold">R</span></div>
            <div><span className="font-display text-lg font-semibold text-foreground">Rockmould</span><span className="text-[10px] uppercase tracking-[0.25em] text-muted-foreground block -mt-0.5">Client Portal</span></div>
          </Link>
          <h2 className="font-display text-3xl font-semibold mb-6">Your Projects, <span className="text-gold-gradient">At Your Fingertips</span></h2>
          <div className="space-y-4">
            {[{ icon: BarChart3, text: "Track project progress in real-time" }, { icon: FileText, text: "Access documents and statements" }, { icon: Clock, text: "View payment history and balances" }, { icon: Shield, text: "Secure and private portal access" }].map((f) => (
              <div key={f.text} className="flex items-center gap-3 text-sm text-muted-foreground"><f.icon className="w-4 h-4 text-primary" />{f.text}</div>
            ))}
          </div>
        </div>
      </div>
      <div className="flex-1 flex items-center justify-center p-6">
        <div className="w-full max-w-md">
          {state === "login" && (
            <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
              <h1 className="font-display text-3xl font-semibold mb-2">Welcome Back</h1>
              <p className="text-muted-foreground mb-8">Enter your registered phone number to access your portal.</p>
              <form onSubmit={handleLogin} className="space-y-6">
                <div>
                  <label className="text-xs uppercase tracking-wider text-muted-foreground mb-2 block">Phone Number</label>
                  <div className="relative">
                    <Phone className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <input value={phone} onChange={(e) => setPhone(e.target.value)} placeholder="+234 xxx xxx xxxx" className="w-full bg-card border border-border rounded-sm pl-10 pr-4 py-3 text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50 transition-colors" />
                  </div>
                </div>
                <button type="submit" className="luxury-btn-primary w-full">Access Portal <ArrowRight className="w-4 h-4" /></button>
              </form>
              <p className="text-xs text-muted-foreground mt-6 text-center">Don't have an account? <button onClick={() => setState("kyc")} className="text-primary hover:underline">Request Access</button></p>
              <p className="text-xs text-muted-foreground mt-4 text-center">Need help? <Link to="/contact" className="text-primary hover:underline">Contact Support</Link></p>
            </motion.div>
          )}
          {state === "unrecognized" && (
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="text-center">
              <div className="w-16 h-16 rounded-full bg-destructive/10 flex items-center justify-center mx-auto mb-4"><Phone className="w-7 h-7 text-destructive" /></div>
              <h2 className="font-display text-2xl font-semibold mb-2">Phone Not Recognized</h2>
              <p className="text-muted-foreground mb-8">This number isn't registered. Submit a KYC request to get started.</p>
              <button onClick={() => setState("kyc")} className="luxury-btn-primary">Submit KYC Request <ArrowRight className="w-4 h-4" /></button>
              <button onClick={() => setState("login")} className="block mx-auto mt-4 text-sm text-muted-foreground hover:text-primary">Try another number</button>
            </motion.div>
          )}
          {state === "kyc" && (
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
              <h2 className="font-display text-2xl font-semibold mb-2">KYC Request</h2>
              <p className="text-muted-foreground mb-6 text-sm">Fill in your details. Our team will review and set up your account.</p>
              <form onSubmit={(e) => { e.preventDefault(); setState("pending"); }} className="space-y-4">
                {[{ label: "Full Name", type: "text", placeholder: "John Doe" }, { label: "Phone Number", type: "tel", placeholder: "+234..." }, { label: "Email", type: "email", placeholder: "john@example.com" }, { label: "Address", type: "text", placeholder: "Your residential address" }].map((f) => (
                  <div key={f.label}><label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">{f.label} *</label><input required type={f.type} placeholder={f.placeholder} className="w-full bg-card border border-border rounded-sm px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50" /></div>
                ))}
                <div><label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">ID Document *</label><div className="border-2 border-dashed border-border rounded-sm p-6 text-center"><p className="text-sm text-muted-foreground">Click to upload identification document</p></div></div>
                <div><label className="text-xs uppercase tracking-wider text-muted-foreground mb-1 block">Project of Interest</label><input placeholder="e.g. Azure Towers" className="w-full bg-card border border-border rounded-sm px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:border-primary/50" /></div>
                <button type="submit" className="luxury-btn-primary w-full mt-2">Submit Request <ArrowRight className="w-4 h-4" /></button>
              </form>
            </motion.div>
          )}
          {state === "pending" && (
            <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="text-center">
              <div className="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4"><Clock className="w-7 h-7 text-primary" /></div>
              <h2 className="font-display text-2xl font-semibold mb-2">Request Submitted</h2>
              <p className="text-muted-foreground mb-2">Your KYC request is under review.</p>
              <p className="text-sm text-muted-foreground mb-8">Our team will verify your details and set up your account. This usually takes 1-2 business days.</p>
              <Link to="/" className="luxury-btn-outline">Return to Website</Link>
            </motion.div>
          )}
        </div>
      </div>
    </div>
  );
}
