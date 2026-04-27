import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Lock, Mail, Eye, EyeOff } from "lucide-react";
import { toast } from "@/components/ui/sonner";

export default function AdminLogin() {
  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const navigate = useNavigate();

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    if (!email.trim() || !password.trim()) {
      toast.error("Enter both your email address and password.");
      return;
    }

    toast.success("Admin access granted.");
    navigate("/admin");
  };

  return (
    <div className="min-h-screen bg-background flex">
      {/* Left - Branding */}
      <div className="hidden lg:flex flex-1 items-center justify-center bg-card border-r border-border">
        <div className="max-w-md text-center px-8">
          <div className="w-16 h-16 rounded-sm bg-primary flex items-center justify-center mx-auto mb-8">
            <span className="font-display text-primary-foreground text-2xl font-bold">R</span>
          </div>
          <h1 className="font-display text-3xl font-bold mb-4">Rockmould Admin</h1>
          <p className="text-muted-foreground text-sm leading-relaxed">Enterprise content management system for managing clients, projects, and website content.</p>
          <div className="luxury-divider my-8" />
          <p className="text-xs text-muted-foreground">Authorized personnel only</p>
        </div>
      </div>

      {/* Right - Login Form */}
      <div className="flex-1 flex items-center justify-center p-8">
        <div className="w-full max-w-sm">
          <div className="lg:hidden mb-8 text-center">
            <div className="w-12 h-12 rounded-sm bg-primary flex items-center justify-center mx-auto mb-4">
              <span className="font-display text-primary-foreground text-lg font-bold">R</span>
            </div>
            <h1 className="font-display text-xl font-bold">Admin Login</h1>
          </div>

          <div className="lg:block hidden">
            <h2 className="font-display text-2xl font-semibold mb-2">Welcome back</h2>
            <p className="text-sm text-muted-foreground mb-8">Sign in to your admin account</p>
          </div>

          <form className="space-y-4" onSubmit={handleSubmit}>
            <div>
              <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1.5 block">Email Address</label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <input value={email} onChange={(event) => setEmail(event.target.value)} type="email" placeholder="admin@rockmould.com" className="w-full bg-muted border border-border rounded-sm pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-primary/50" />
              </div>
            </div>
            <div>
              <label className="text-xs uppercase tracking-wider text-muted-foreground mb-1.5 block">Password</label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <input value={password} onChange={(event) => setPassword(event.target.value)} type={showPassword ? "text" : "password"} placeholder="Enter your password" className="w-full bg-muted border border-border rounded-sm pl-10 pr-10 py-3 text-sm focus:outline-none focus:border-primary/50" />
                <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground">
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>
            <div className="flex items-center justify-between">
              <label className="flex items-center gap-2 text-xs text-muted-foreground cursor-pointer">
                <input type="checkbox" className="rounded border-border" />
                Remember me
              </label>
              <button type="button" className="text-xs text-primary hover:underline">Forgot password?</button>
            </div>
            <button type="submit" className="luxury-btn-primary w-full text-sm py-3 flex items-center justify-center gap-2">
              <Lock className="w-4 h-4" /> Sign In
            </button>
          </form>

          <p className="text-xs text-muted-foreground text-center mt-8">
            <Link to="/" className="text-primary hover:underline">Back to Website</Link>
          </p>
        </div>
      </div>
    </div>
  );
}
