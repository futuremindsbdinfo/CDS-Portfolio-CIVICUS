import { Link } from "@tanstack/react-router";
import { useEffect, useRef, useState, type ReactNode } from "react";

export const NAV: { label: string; href: string }[] = [
  { label: "হোম", href: "/" },
  { label: "আমাদের সম্পর্কে", href: "/#about" },
  { label: "প্রজেক্টস", href: "/#projects" },
  { label: "গ্যালারি", href: "/gallery" },
  { label: "নোটিশ", href: "/notices" },
  { label: "ডোনেশন", href: "/donate" },
  { label: "যোগাযোগ", href: "/#contact" },
];

export function SiteHeader() {
  const [openMenu, setOpenMenu] = useState(false);
  const menuRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!openMenu) return;
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && setOpenMenu(false);
    const onClick = (e: MouseEvent) => {
      if (menuRef.current && !menuRef.current.contains(e.target as Node)) setOpenMenu(false);
    };
    document.addEventListener("keydown", onKey);
    document.addEventListener("mousedown", onClick);
    return () => {
      document.removeEventListener("keydown", onKey);
      document.removeEventListener("mousedown", onClick);
    };
  }, [openMenu]);

  return (
    <header className="sticky top-0 z-50 border-b border-border/60 bg-background/85 backdrop-blur-md">
      <div className="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
        <Link to="/" className="flex min-w-0 items-center gap-3">
          <span className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-gradient text-white shadow-card">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5">
              <path d="M12 3l3 6 6 .9-4.5 4.3 1.1 6.3L12 17.8 6.4 20.5l1.1-6.3L3 9.9 9 9z" strokeLinejoin="round" />
            </svg>
          </span>
          <span className="min-w-0">
            <div className="truncate font-serif-bn text-base font-bold leading-tight sm:text-lg">
              Citizen Development Society
            </div>
            <div className="truncate text-[11px] font-medium text-muted-foreground">
              নাগরিক উন্নয়ন সমিতি · CDS
            </div>
          </span>
        </Link>

        <nav className="ml-auto hidden items-center gap-1 lg:flex">
          {NAV.map((n) => (
            <a
              key={n.label}
              href={n.href}
              className="rounded-full px-3 py-2 text-sm font-medium text-foreground/80 transition hover:bg-primary-soft hover:text-primary"
            >
              {n.label}
            </a>
          ))}
        </nav>

        <a
          href="/donate"
          className="ml-auto inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110 lg:ml-3"
        >
          <svg viewBox="0 0 24 24" fill="currentColor" className="h-4 w-4">
            <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" />
          </svg>
          ডোনেট করুন
        </a>

        <button
          onClick={() => setOpenMenu((v) => !v)}
          aria-label="Menu"
          className="grid h-10 w-10 shrink-0 place-items-center rounded-lg border border-border bg-surface lg:hidden"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5">
            {openMenu ? <path d="M6 6l12 12M18 6L6 18" /> : <path d="M4 7h16M4 12h16M4 17h16" />}
          </svg>
        </button>
      </div>

      <div
        ref={menuRef}
        className={`overflow-hidden border-t border-border bg-background/95 backdrop-blur transition-[max-height] duration-300 lg:hidden ${
          openMenu ? "max-h-96" : "max-h-0"
        }`}
      >
        <nav className="mx-auto flex max-w-7xl flex-col px-4 py-2 sm:px-6">
          {NAV.map((n) => (
            <a
              key={n.label}
              href={n.href}
              onClick={() => setOpenMenu(false)}
              className="rounded-md px-3 py-3 text-sm font-medium text-foreground/85 hover:bg-primary-soft hover:text-primary"
            >
              {n.label}
            </a>
          ))}
        </nav>
      </div>
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer id="contact" className="relative overflow-hidden bg-secondary text-white">
      <div
        className="absolute inset-0 opacity-90"
        style={{ background: "linear-gradient(135deg,#1e3a8a 0%,#1e40af 55%,#0f2a6b 100%)" }}
      />
      <svg
        className="absolute inset-x-0 top-0 h-24 w-full text-background"
        viewBox="0 0 1440 100"
        preserveAspectRatio="none"
      >
        <path fill="currentColor" d="M0 0h1440v40c-240 60-480 60-720 30S240 20 0 60z" />
      </svg>
      <div className="relative mx-auto max-w-7xl px-4 pb-10 pt-32 sm:px-6 lg:px-8">
        <div className="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <div className="flex items-center gap-3">
              <span className="grid h-10 w-10 place-items-center rounded-xl bg-white/10 backdrop-blur">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5 text-white">
                  <path d="M12 3l3 6 6 .9-4.5 4.3 1.1 6.3L12 17.8 6.4 20.5l1.1-6.3L3 9.9 9 9z" strokeLinejoin="round" />
                </svg>
              </span>
              <div className="font-serif-bn text-lg font-bold">CDS</div>
            </div>
            <p className="mt-4 text-sm leading-relaxed text-white/80">
              Citizen Development Society — সুশিক্ষা, সুস্বাস্থ্য, সুনাগরিক ও সুশাসনের
              লক্ষ্যে কাজ করা একটি অলাভজনক সংগঠন।
            </p>
          </div>
          <div>
            <div className="font-serif-bn text-base font-bold">দ্রুত লিংক</div>
            <ul className="mt-4 space-y-2 text-sm text-white/85">
              {NAV.slice(1, 6).map((n) => (
                <li key={n.label}>
                  <a href={n.href} className="hover:text-white hover:underline">
                    {n.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>
          <div>
            <div className="font-serif-bn text-base font-bold">যোগাযোগ</div>
            <ul className="mt-4 space-y-2 text-sm text-white/85">
              <li>বাড়ি ২৩, রোড ৫, ধানমন্ডি, ঢাকা</li>
              <li>+৮৮০ ১৭০০-০০০০০০</li>
              <li>info@cds-bd.org</li>
            </ul>
            <Link
              to="/admin"
              className="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-white/70 hover:text-white"
            >
              অ্যাডমিন প্যানেল →
            </Link>
          </div>
          <div>
            <div className="font-serif-bn text-base font-bold">সোশ্যাল মিডিয়া</div>
            <div className="mt-4 flex gap-3">
              {["F", "X", "in", "IG"].map((s) => (
                <a
                  key={s}
                  href="#"
                  className="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-sm font-bold text-white transition hover:bg-primary"
                >
                  {s}
                </a>
              ))}
            </div>
          </div>
        </div>
        <div className="mt-10 flex flex-col items-center justify-between gap-3 border-t border-white/15 pt-6 text-xs text-white/70 sm:flex-row">
          <div>© {new Date().getFullYear()} Citizen Development Society (CDS)। সর্বস্বত্ব সংরক্ষিত।</div>
          <div>Made with ♥ in Bangladesh</div>
        </div>
      </div>
    </footer>
  );
}

export function PageBanner({
  title,
  subtitle,
  crumbs,
}: {
  title: string;
  subtitle?: string;
  crumbs: { label: string; to?: string }[];
}) {
  return (
    <section className="relative overflow-hidden border-b border-border/60 bg-surface-2">
      <div className="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-primary-soft/60" />
      <div className="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-secondary/10" />
      <div className="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <nav className="mb-4 flex flex-wrap items-center gap-1.5 text-xs font-medium text-muted-foreground">
          {crumbs.map((c, i) => (
            <span key={i} className="flex items-center gap-1.5">
              {c.to ? (
                <Link to={c.to} className="hover:text-primary">
                  {c.label}
                </Link>
              ) : (
                <span className="text-foreground/70">{c.label}</span>
              )}
              {i < crumbs.length - 1 && <span className="text-muted-foreground/60">›</span>}
            </span>
          ))}
        </nav>
        <h1 className="font-serif-bn text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
          {title}
        </h1>
        {subtitle && (
          <p className="mt-3 max-w-2xl text-base leading-relaxed text-muted-foreground">
            {subtitle}
          </p>
        )}
      </div>
    </section>
  );
}

export function PageShell({ children }: { children: ReactNode }) {
  return (
    <div className="bg-warm-grain min-h-screen font-sans-bn text-foreground">
      <SiteHeader />
      {children}
      <SiteFooter />
    </div>
  );
}
