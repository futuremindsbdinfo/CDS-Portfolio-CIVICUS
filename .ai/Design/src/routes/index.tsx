import { createFileRoute, Link } from "@tanstack/react-router";
import { useEffect, useRef, useState } from "react";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "Citizen Development Society (CDS) — নাগরিক উন্নয়ন সমিতি" },
      {
        name: "description",
        content:
          "সুশিক্ষা, সুস্বাস্থ্য, সুনাগরিক ও সুশাসনের লক্ষ্যে কাজ করা একটি স্বেচ্ছাসেবী সংগঠন।",
      },
    ],
  }),
  component: HomePage,
});

const NAV = [
  { label: "হোম", href: "#" },
  { label: "আমাদের সম্পর্কে", href: "#about" },
  { label: "প্রজেক্টস", href: "#projects" },
  { label: "গ্যালারি", href: "#gallery" },
  { label: "নোটিশ", href: "#notices" },
  { label: "ডোনেশন", href: "#donate" },
  { label: "যোগাযোগ", href: "#contact" },
];

const PILLARS = [
  {
    bn: "সুশিক্ষা",
    en: "Good Education",
    desc: "শিক্ষাকে সবার জন্য সহজলভ্য ও মানসম্পন্ন করে তোলা।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <path d="M3 8l9-4 9 4-9 4-9-4z" strokeLinejoin="round" />
        <path d="M7 10v5c0 1.5 2.5 3 5 3s5-1.5 5-3v-5" strokeLinecap="round" />
        <path d="M21 8v6" strokeLinecap="round" />
      </svg>
    ),
  },
  {
    bn: "সুস্বাস্থ্য",
    en: "Good Health",
    desc: "প্রতিটি পরিবারে সুস্থ ও নিরাপদ জীবনের নিশ্চয়তা।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" strokeLinejoin="round" />
      </svg>
    ),
  },
  {
    bn: "সুনাগরিক",
    en: "Good Citizenship",
    desc: "দায়িত্বশীল ও সচেতন নাগরিক গড়ে তোলা।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <circle cx="12" cy="8" r="3.2" />
        <path d="M5 20c1.5-3.5 4.2-5 7-5s5.5 1.5 7 5" strokeLinecap="round" />
      </svg>
    ),
  },
  {
    bn: "সুশাসন",
    en: "Good Governance",
    desc: "স্বচ্ছতা, জবাবদিহিতা ও ন্যায়ের চর্চা।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <path d="M4 21h16M6 21V10M18 21V10M4 10l8-6 8 6" strokeLinejoin="round" />
        <path d="M10 21v-6h4v6" />
      </svg>
    ),
  },
];

const STATS = [
  { label: "স্বেচ্ছাসেবী", value: 320, suffix: "+" },
  { label: "সম্পন্ন প্রজেক্ট", value: 45, suffix: "" },
  { label: "উপকারভোগী পরিবার", value: 1500, suffix: "+" },
  { label: "প্রতিষ্ঠার বছর", value: 2015, suffix: "" },
];

const SHORTCUTS = [
  {
    title: "প্রজেক্টস",
    desc: "আমাদের চলমান ও সম্পন্ন সকল উন্নয়ন প্রকল্প।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" className="h-full w-full">
        <rect x="3" y="4" width="18" height="16" rx="2" />
        <path d="M3 9h18M8 14h5" strokeLinecap="round" />
      </svg>
    ),
  },
  {
    title: "গ্যালারি",
    desc: "সংগঠনের কর্মকাণ্ডের মুহূর্তগুলি।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" className="h-full w-full">
        <rect x="3" y="5" width="18" height="14" rx="2" />
        <circle cx="9" cy="11" r="2" />
        <path d="M3 17l5-4 5 4 3-3 5 4" strokeLinejoin="round" />
      </svg>
    ),
  },
  {
    title: "নোটিশ বোর্ড",
    desc: "গুরুত্বপূর্ণ ঘোষণা ও পরিপত্র।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" className="h-full w-full">
        <path d="M6 3h9l5 5v13H6z" strokeLinejoin="round" />
        <path d="M14 3v6h6M9 13h8M9 17h5" strokeLinecap="round" />
      </svg>
    ),
  },
  {
    title: "ডোনেশন",
    desc: "আপনার সহায়তা আমাদের পথচলা সহজ করে।",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" className="h-full w-full">
        <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" strokeLinejoin="round" />
      </svg>
    ),
  },
];

const PROJECTS = [
  {
    title_bn: "গ্রামীণ পাঠাগার",
    title_en: "Rural Library Initiative",
    desc: "প্রত্যন্ত গ্রামে বই ও পাঠাগার পৌঁছে দেওয়ার উদ্যোগ।",
    status: "চলমান",
    tone: "warning",
    hue: "linear-gradient(135deg,#3A7D5C,#1e3a8a)",
  },
  {
    title_bn: "মাতৃস্বাস্থ্য ক্যাম্প",
    title_en: "Maternal Health Camp",
    desc: "বিনামূল্যে চিকিৎসা ও পরামর্শ সেবা প্রদান।",
    status: "সম্পন্ন",
    tone: "success",
    hue: "linear-gradient(135deg,#1e3a8a,#3A7D5C)",
  },
  {
    title_bn: "নাগরিক সচেতনতা কর্মশালা",
    title_en: "Civic Awareness Workshop",
    desc: "তরুণদের জন্য অধিকার ও দায়িত্ব বিষয়ক প্রশিক্ষণ।",
    status: "চলমান",
    tone: "warning",
    hue: "linear-gradient(135deg,#3A7D5C,#0f766e)",
  },
];

const NOTICES = [
  {
    title: "বার্ষিক সাধারণ সভা ২০২৬",
    date: "১৫ মার্চ, ২০২৬",
    excerpt: "সকল সদস্যকে সংগঠনের বার্ষিক সাধারণ সভায় উপস্থিত থাকার জন্য অনুরোধ করা হলো।",
  },
  {
    title: "শীতবস্ত্র বিতরণ কর্মসূচি",
    date: "২০ ডিসেম্বর, ২০২৫",
    excerpt: "উত্তরাঞ্চলে শীতার্তদের মাঝে কম্বল ও শীতবস্ত্র বিতরণ শুরু হচ্ছে।",
  },
  {
    title: "স্বেচ্ছাসেবক নিয়োগ বিজ্ঞপ্তি",
    date: "০৫ ডিসেম্বর, ২০২৫",
    excerpt: "নতুন প্রকল্পের জন্য আগ্রহী স্বেচ্ছাসেবকদের আবেদন আহ্বান।",
  },
  {
    title: "বৃক্ষরোপণ অভিযান সফল",
    date: "১০ নভেম্বর, ২০২৫",
    excerpt: "সারা দেশে ৫০০০+ গাছের চারা রোপণ সম্পন্ন হয়েছে।",
  },
];

const FAQS = [
  {
    q: "CDS কীভাবে অনুদান ব্যবহার করে?",
    a: "সকল অনুদান সরাসরি প্রকল্প বাস্তবায়ন, উপকারভোগী সহায়তা ও পরিচালন ব্যয়ে ব্যবহৃত হয়। প্রতি বছর নিরীক্ষিত প্রতিবেদন প্রকাশ করা হয়।",
  },
  {
    q: "আমি কীভাবে স্বেচ্ছাসেবক হতে পারি?",
    a: "যোগাযোগ ফর্ম পূরণ করে অথবা সরাসরি অফিসে এসে নিবন্ধন করতে পারেন। আপনার আগ্রহ অনুযায়ী প্রকল্পে যুক্ত করা হবে।",
  },
  {
    q: "সংগঠনটি কোথায় কাজ করে?",
    a: "প্রাথমিকভাবে বাংলাদেশের গ্রামীণ ও প্রান্তিক জনপদে কাজ করি; বর্তমানে ১২টি জেলায় প্রকল্প চলমান।",
  },
  {
    q: "ডোনেশনের রশিদ পাওয়া যাবে?",
    a: "হ্যাঁ, প্রতিটি অনুদানের জন্য সরকারি নিয়মে রশিদ প্রদান করা হয়।",
  },
];

function useCountUp(target: number, duration = 1400, start: boolean) {
  const [n, setN] = useState(0);
  useEffect(() => {
    if (!start) return;
    let raf = 0;
    const t0 = performance.now();
    const step = (t: number) => {
      const p = Math.min(1, (t - t0) / duration);
      const eased = 1 - Math.pow(1 - p, 3);
      setN(Math.round(target * eased));
      if (p < 1) raf = requestAnimationFrame(step);
    };
    raf = requestAnimationFrame(step);
    return () => cancelAnimationFrame(raf);
  }, [target, duration, start]);
  return n;
}

function StatItem({ value, label, suffix }: { value: number; label: string; suffix: string }) {
  const ref = useRef<HTMLDivElement>(null);
  const [seen, setSeen] = useState(false);
  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const io = new IntersectionObserver(([e]) => e.isIntersecting && setSeen(true), { threshold: 0.4 });
    io.observe(el);
    return () => io.disconnect();
  }, []);
  const n = useCountUp(value, 1400, seen);
  return (
    <div ref={ref} className="text-center">
      <div className="font-serif-bn text-4xl font-bold tracking-tight text-white sm:text-5xl">
        {n.toLocaleString("bn-BD")}
        {suffix}
      </div>
      <div className="mt-2 text-sm font-medium text-white/80">{label}</div>
    </div>
  );
}

function HomePage() {
  const [openMenu, setOpenMenu] = useState(false);
  const [openFaq, setOpenFaq] = useState<number | null>(0);
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
    <div className="bg-warm-grain min-h-screen font-sans-bn text-foreground">
      {/* HEADER */}
      <header className="sticky top-0 z-50 border-b border-border/60 bg-background/85 backdrop-blur-md">
        <div className="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
          <a href="#" className="flex min-w-0 items-center gap-3">
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
          </a>

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
            href="#donate"
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

        {/* mobile menu */}
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

      {/* HERO */}
      <section className="relative overflow-hidden">
        <div className="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-8 lg:py-24">
          <div className="relative">
            <span className="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">
              <span className="h-1.5 w-1.5 rounded-full bg-primary" />
              জনগণের জন্য · জনগণের সাথে
            </span>
            <h1 className="mt-5 font-serif-bn text-4xl font-bold leading-[1.15] tracking-tight sm:text-5xl lg:text-6xl">
              একটি সুন্দর সমাজ,
              <br />
              <span className="bg-gradient-to-br from-primary to-secondary bg-clip-text text-transparent">
                সবার অংশগ্রহণে গড়ি
              </span>
            </h1>
            <p className="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground sm:text-lg">
              সুশিক্ষা, সুস্বাস্থ্য, সুনাগরিক ও সুশাসন — এই চার স্তম্ভকে সামনে রেখে আমরা কাজ করছি
              দেশের প্রান্তিক জনপদে, প্রতিটি পরিবারের পাশে।
            </p>
            <div className="mt-8 flex flex-wrap gap-3">
              <a
                href="#about"
                className="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-3 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110"
              >
                আমাদের সম্পর্কে জানুন
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
                  <path d="M5 12h14M13 6l6 6-6 6" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
              </a>
              <a
                href="#donate"
                className="inline-flex items-center gap-2 rounded-full border border-border bg-surface px-5 py-3 text-sm font-semibold text-foreground transition hover:bg-primary-soft hover:text-primary"
              >
                ডোনেট করুন
              </a>
            </div>
          </div>

          {/* Organic SVG art */}
          <div className="relative hidden lg:block">
            <div className="relative aspect-square">
              <svg viewBox="0 0 500 500" className="absolute inset-0 h-full w-full">
                <defs>
                  <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stopColor="#3A7D5C" />
                    <stop offset="100%" stopColor="#1e3a8a" />
                  </linearGradient>
                  <linearGradient id="g2" x1="0" y1="0" x2="1" y2="0">
                    <stop offset="0%" stopColor="#3A7D5C" stopOpacity="0.15" />
                    <stop offset="100%" stopColor="#1e3a8a" stopOpacity="0.15" />
                  </linearGradient>
                </defs>
                <path
                  fill="url(#g1)"
                  d="M420 260c0 95-77 170-172 170S60 355 78 260 155 90 250 90s170 75 170 170z"
                  opacity="0.95"
                />
                <path
                  fill="url(#g2)"
                  d="M460 240c0 118-90 210-208 210S48 358 48 240 138 30 256 30s204 92 204 210z"
                />
                <g stroke="white" strokeWidth="2" fill="none" opacity="0.7">
                  <circle cx="250" cy="230" r="60" />
                  <path d="M190 230a60 60 0 01120 0" />
                  <path d="M250 170v120M190 230h120" />
                </g>
                <g fill="white">
                  <circle cx="150" cy="150" r="6" />
                  <circle cx="360" cy="180" r="4" />
                  <circle cx="380" cy="330" r="7" />
                  <circle cx="140" cy="340" r="5" />
                </g>
              </svg>
              <div className="absolute -bottom-4 -left-4 rounded-2xl bg-surface p-4 shadow-card">
                <div className="text-xs text-muted-foreground">সক্রিয় জেলা</div>
                <div className="font-serif-bn text-2xl font-bold text-primary">১২ +</div>
              </div>
              <div className="absolute -right-2 top-8 rounded-2xl bg-surface p-4 shadow-card">
                <div className="text-xs text-muted-foreground">চলমান প্রকল্প</div>
                <div className="font-serif-bn text-2xl font-bold text-secondary">১৮</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ABOUT PREVIEW */}
      <section id="about" className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid items-center gap-10 lg:grid-cols-[1fr_1.2fr]">
          <div className="relative">
            <div className="aspect-[4/3] overflow-hidden rounded-3xl bg-brand-gradient p-8 shadow-card">
              <svg viewBox="0 0 300 220" className="h-full w-full">
                <g fill="none" stroke="white" strokeWidth="1.5" opacity="0.85">
                  <circle cx="80" cy="110" r="28" />
                  <circle cx="150" cy="110" r="34" />
                  <circle cx="220" cy="110" r="28" />
                  <path d="M40 180c25-30 70-40 110-40s85 10 110 40" strokeLinecap="round" />
                </g>
                <g fill="white" opacity="0.9">
                  <circle cx="80" cy="110" r="6" />
                  <circle cx="150" cy="110" r="8" />
                  <circle cx="220" cy="110" r="6" />
                </g>
              </svg>
            </div>
          </div>
          <div>
            <div className="text-xs font-semibold uppercase tracking-widest text-primary">আমাদের সম্পর্কে</div>
            <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
              মানুষের জন্য, মানবতার জন্য
            </h2>
            <p className="mt-4 text-base leading-relaxed text-muted-foreground">
              Citizen Development Society (CDS) ২০১৫ সাল থেকে বাংলাদেশের প্রান্তিক জনপদে
              শিক্ষা, স্বাস্থ্য ও নাগরিক সচেতনতামূলক কার্যক্রম পরিচালনা করছে। আমাদের লক্ষ্য —
              একটি অন্তর্ভুক্তিমূলক, ন্যায়ভিত্তিক ও টেকসই সমাজ।
            </p>
            <a
              href="#"
              className="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:underline"
            >
              বিস্তারিত জানুন
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
                <path d="M5 12h14M13 6l6 6-6 6" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </a>
          </div>
        </div>
      </section>

      {/* PILLARS */}
      <section className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="mb-10 text-center">
          <div className="text-xs font-semibold uppercase tracking-widest text-primary">আমাদের চার স্তম্ভ</div>
          <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
            যে মূল্যবোধে আমরা বিশ্বাসী
          </h2>
        </div>
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {PILLARS.map((p) => (
            <div
              key={p.bn}
              className="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:shadow-card-hover"
            >
              <div className="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-primary-soft opacity-70 transition group-hover:scale-125" />
              <div className="relative">
                <div className="grid h-12 w-12 place-items-center rounded-xl bg-primary text-primary-foreground shadow-card">
                  {p.icon}
                </div>
                <div className="mt-4 inline-flex items-center rounded-full bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">
                  {p.en}
                </div>
                <h3 className="mt-2 font-serif-bn text-xl font-bold">{p.bn}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{p.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* STATS BAND */}
      <section className="relative mx-4 my-10 overflow-hidden rounded-3xl bg-brand-gradient px-6 py-14 shadow-card-hover sm:mx-6 lg:mx-auto lg:max-w-7xl lg:px-12">
        <svg
          className="absolute inset-0 h-full w-full opacity-20"
          viewBox="0 0 800 300"
          preserveAspectRatio="none"
        >
          <path d="M0 200 Q200 100 400 200 T800 200 V300 H0Z" fill="white" />
        </svg>
        <div className="relative grid grid-cols-2 gap-8 lg:grid-cols-4">
          {STATS.map((s) => (
            <StatItem key={s.label} value={s.value} label={s.label} suffix={s.suffix} />
          ))}
        </div>
      </section>

      {/* SHORTCUTS */}
      <section className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="mb-10 flex items-end justify-between gap-4">
          <div>
            <div className="text-xs font-semibold uppercase tracking-widest text-primary">গুরুত্বপূর্ণ লিংক</div>
            <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">দ্রুত পৌঁছান</h2>
          </div>
        </div>
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {SHORTCUTS.map((s) => (
            <a
              key={s.title}
              href="#"
              className="group relative overflow-hidden rounded-2xl border border-border bg-surface p-6 shadow-card transition hover:-translate-y-1 hover:border-primary/40 hover:shadow-card-hover"
            >
              <div className="pointer-events-none absolute -bottom-6 -right-6 h-32 w-32 text-primary/10 transition group-hover:scale-110 group-hover:text-primary/20">
                {s.icon}
              </div>
              <div className="relative">
                <div className="grid h-10 w-10 place-items-center rounded-lg bg-primary-soft text-primary">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5">
                    <path d="M7 17L17 7M9 7h8v8" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>
                </div>
                <h3 className="mt-4 font-serif-bn text-lg font-bold">{s.title}</h3>
                <p className="mt-1 text-sm text-muted-foreground">{s.desc}</p>
              </div>
            </a>
          ))}
        </div>
      </section>

      {/* PROJECTS */}
      <section id="projects" className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="mb-10 flex flex-wrap items-end justify-between gap-4">
          <div>
            <div className="text-xs font-semibold uppercase tracking-widest text-primary">ফিচার্ড প্রজেক্ট</div>
            <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">আমাদের সাম্প্রতিক কাজ</h2>
          </div>
          <a href="#" className="text-sm font-semibold text-primary hover:underline">
            সব প্রজেক্ট দেখুন →
          </a>
        </div>
        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          {PROJECTS.map((p) => (
            <article
              key={p.title_en}
              className="group overflow-hidden rounded-2xl border border-border bg-surface shadow-card transition hover:-translate-y-1 hover:shadow-card-hover"
            >
              <div
                className="relative aspect-[16/10] overflow-hidden"
                style={{ background: p.hue }}
              >
                <svg className="absolute inset-0 h-full w-full opacity-30" viewBox="0 0 400 250">
                  <circle cx="80" cy="60" r="80" fill="white" />
                  <circle cx="320" cy="200" r="120" fill="white" />
                </svg>
                <span
                  className={`absolute right-3 top-3 rounded-full px-3 py-1 text-xs font-semibold ${
                    p.tone === "success"
                      ? "bg-success text-success-foreground"
                      : "bg-warning text-warning-foreground"
                  }`}
                >
                  {p.status}
                </span>
              </div>
              <div className="p-5">
                <div className="text-xs font-medium text-muted-foreground">{p.title_en}</div>
                <h3 className="mt-1 font-serif-bn text-lg font-bold">{p.title_bn}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{p.desc}</p>
                <a href="#" className="mt-4 inline-flex text-sm font-semibold text-primary hover:underline">
                  বিস্তারিত →
                </a>
              </div>
            </article>
          ))}
        </div>
      </section>

      {/* NOTICES */}
      <section id="notices" className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid gap-10 lg:grid-cols-[1fr_2fr]">
          <div>
            <div className="text-xs font-semibold uppercase tracking-widest text-primary">নোটিশ বোর্ড</div>
            <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">সাম্প্রতিক নোটিশ</h2>
            <p className="mt-3 text-sm text-muted-foreground">
              সংগঠন থেকে প্রকাশিত সর্বশেষ ঘোষণা ও পরিপত্র এখানে পাবেন।
            </p>
            <a
              href="#"
              className="mt-6 inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-2 text-sm font-semibold text-primary hover:bg-primary-soft"
            >
              সব নোটিশ দেখুন →
            </a>
          </div>
          <ul className="divide-y divide-border overflow-hidden rounded-2xl border border-border bg-surface shadow-card">
            {NOTICES.map((n) => (
              <li key={n.title} className="group flex gap-4 p-5 transition hover:bg-primary-soft/40">
                <div className="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-5 w-5">
                    <path d="M6 3h9l5 5v13H6z M14 3v6h6" strokeLinejoin="round" />
                  </svg>
                </div>
                <div className="min-w-0 flex-1">
                  <div className="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <h3 className="font-serif-bn text-base font-bold">{n.title}</h3>
                    <span className="text-xs text-muted-foreground">{n.date}</span>
                  </div>
                  <p className="mt-1 text-sm text-muted-foreground">{n.excerpt}</p>
                </div>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* FAQ */}
      <section className="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="mb-10 text-center">
          <div className="text-xs font-semibold uppercase tracking-widest text-primary">প্রশ্নোত্তর</div>
          <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">প্রায়শই জিজ্ঞাসিত প্রশ্ন</h2>
        </div>
        <div className="space-y-3">
          {FAQS.map((f, i) => {
            const open = openFaq === i;
            return (
              <div
                key={f.q}
                className={`overflow-hidden rounded-2xl border bg-surface transition ${
                  open ? "border-primary/40 shadow-card" : "border-border"
                }`}
              >
                <button
                  onClick={() => setOpenFaq(open ? null : i)}
                  className="flex w-full items-center justify-between gap-4 px-5 py-4 text-left"
                >
                  <span className="font-serif-bn text-base font-semibold">{f.q}</span>
                  <span
                    className={`grid h-7 w-7 shrink-0 place-items-center rounded-full bg-primary-soft text-primary transition ${
                      open ? "rotate-45" : ""
                    }`}
                  >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" className="h-4 w-4">
                      <path d="M12 5v14M5 12h14" strokeLinecap="round" />
                    </svg>
                  </span>
                </button>
                <div
                  className={`grid transition-[grid-template-rows] duration-300 ${
                    open ? "grid-rows-[1fr]" : "grid-rows-[0fr]"
                  }`}
                >
                  <div className="overflow-hidden">
                    <p className="px-5 pb-5 text-sm leading-relaxed text-muted-foreground">{f.a}</p>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </section>

      {/* DONATE CALLOUT */}
      <section id="donate" className="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div className="relative overflow-hidden rounded-3xl border border-primary/20 bg-surface p-8 shadow-card sm:p-12">
          <div className="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-primary-soft" />
          <div className="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-secondary/10" />
          <div className="relative grid gap-6 lg:grid-cols-[2fr_1fr] lg:items-center">
            <div>
              <div className="text-xs font-semibold uppercase tracking-widest text-primary">আপনার সহায়তায়</div>
              <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
                একটি ছোট অনুদান, বদলে দিতে পারে একটি জীবন
              </h2>
              <p className="mt-3 max-w-2xl text-base leading-relaxed text-muted-foreground">
                আপনার প্রতিটি অবদান শিশুর শিক্ষা, মায়ের স্বাস্থ্যসেবা ও তরুণদের প্রশিক্ষণে
                সরাসরি ব্যবহৃত হয়। আজই যুক্ত হোন।
              </p>
            </div>
            <div className="flex flex-col gap-3 sm:flex-row lg:flex-col">
              <a
                href="#"
                className="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-card hover:brightness-110"
              >
                এখনই ডোনেট করুন
              </a>
              <a
                href="#contact"
                className="inline-flex items-center justify-center gap-2 rounded-full border border-border bg-surface px-6 py-3 text-sm font-semibold text-foreground hover:bg-primary-soft hover:text-primary"
              >
                যোগাযোগ করুন
              </a>
            </div>
          </div>
        </div>
      </section>

      {/* FOOTER */}
      <footer id="contact" className="relative overflow-hidden bg-secondary text-white">
        <div
          className="absolute inset-0 opacity-90"
          style={{
            background:
              "linear-gradient(135deg,#1e3a8a 0%,#1e40af 55%,#0f2a6b 100%)",
          }}
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
    </div>
  );
}
