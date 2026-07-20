import { createFileRoute } from "@tanstack/react-router";
import { useEffect, useState } from "react";
import { PageBanner, PageShell } from "../components/site-chrome";

export const Route = createFileRoute("/gallery")({
  head: () => ({
    meta: [
      { title: "গ্যালারি — Citizen Development Society (CDS)" },
      {
        name: "description",
        content: "CDS-এর কর্মকাণ্ড, প্রকল্প ও কমিউনিটি ইভেন্টের ছবির গ্যালারি।",
      },
      { property: "og:title", content: "গ্যালারি — CDS" },
      { property: "og:description", content: "সংগঠনের কর্মকাণ্ডের মুহূর্তগুলি।" },
    ],
  }),
  component: GalleryPage,
});

const PROJECTS = ["সব", "গ্রামীণ পাঠাগার", "মাতৃস্বাস্থ্য ক্যাম্প", "নাগরিক কর্মশালা", "বৃক্ষরোপণ"];

type Item = {
  id: number;
  caption: string;
  date: string;
  project: string;
  ratio: "tall" | "wide" | "square";
  hue: string;
  seed: number;
};

const IMAGES: Item[] = [
  { id: 1, caption: "প্রাথমিক পাঠাগার উদ্বোধন — কুড়িগ্রাম", date: "১২ মার্চ ২০২৬", project: "গ্রামীণ পাঠাগার", ratio: "wide", hue: "linear-gradient(135deg,#3A7D5C,#0f766e)", seed: 3 },
  { id: 2, caption: "মাতৃসেবা ক্যাম্পে চিকিৎসক দল", date: "০৫ ফেব্রু ২০২৬", project: "মাতৃস্বাস্থ্য ক্যাম্প", ratio: "tall", hue: "linear-gradient(135deg,#1e3a8a,#3A7D5C)", seed: 7 },
  { id: 3, caption: "তরুণদের সাথে নাগরিক অধিকার কর্মশালা", date: "২১ জানু ২০২৬", project: "নাগরিক কর্মশালা", ratio: "square", hue: "linear-gradient(135deg,#3A7D5C,#1e3a8a)", seed: 11 },
  { id: 4, caption: "সুন্দরবনে বৃক্ষরোপণ অভিযান", date: "১০ নভে ২০২৫", project: "বৃক্ষরোপণ", ratio: "wide", hue: "linear-gradient(135deg,#0f766e,#3A7D5C)", seed: 4 },
  { id: 5, caption: "স্বেচ্ছাসেবীদের প্রশিক্ষণ শিবির", date: "১৮ অক্টো ২০২৫", project: "নাগরিক কর্মশালা", ratio: "tall", hue: "linear-gradient(135deg,#1e40af,#3A7D5C)", seed: 6 },
  { id: 6, caption: "গ্রামের পাঠাগারে শিশুরা", date: "২৯ সেপ্টে ২০২৫", project: "গ্রামীণ পাঠাগার", ratio: "square", hue: "linear-gradient(135deg,#3A7D5C,#65a30d)", seed: 9 },
  { id: 7, caption: "শীতবস্ত্র বিতরণ কর্মসূচি", date: "২২ ডিসে ২০২৫", project: "নাগরিক কর্মশালা", ratio: "wide", hue: "linear-gradient(135deg,#1e3a8a,#0ea5e9)", seed: 2 },
  { id: 8, caption: "স্বাস্থ্যসেবা রেজিস্ট্রেশন", date: "১৫ আগ ২০২৫", project: "মাতৃস্বাস্থ্য ক্যাম্প", ratio: "tall", hue: "linear-gradient(135deg,#3A7D5C,#1e3a8a)", seed: 8 },
  { id: 9, caption: "নদীপাড়ে চারা রোপণ", date: "০৫ জুলা ২০২৫", project: "বৃক্ষরোপণ", ratio: "square", hue: "linear-gradient(135deg,#065f46,#3A7D5C)", seed: 5 },
  { id: 10, caption: "পাঠাগার তত্ত্বাবধায়ক সভা", date: "২০ জুন ২০২৫", project: "গ্রামীণ পাঠাগার", ratio: "wide", hue: "linear-gradient(135deg,#1e3a8a,#3A7D5C)", seed: 1 },
  { id: 11, caption: "বার্ষিক সম্মেলন — ঢাকা", date: "১১ মে ২০২৫", project: "নাগরিক কর্মশালা", ratio: "tall", hue: "linear-gradient(135deg,#1e40af,#065f46)", seed: 10 },
  { id: 12, caption: "ফলদ চারা বিতরণ", date: "০৩ এপ্রি ২০২৫", project: "বৃক্ষরোপণ", ratio: "square", hue: "linear-gradient(135deg,#3A7D5C,#84cc16)", seed: 12 },
];

function TileArt({ hue, seed }: { hue: string; seed: number }) {
  const cx = 30 + (seed * 37) % 40;
  const cy = 30 + (seed * 53) % 40;
  return (
    <svg viewBox="0 0 200 200" className="absolute inset-0 h-full w-full" preserveAspectRatio="xMidYMid slice">
      <defs>
        <linearGradient id={`g-${seed}`} x1="0" y1="0" x2="1" y2="1">
          <stop offset="0%" stopColor="rgba(255,255,255,0.35)" />
          <stop offset="100%" stopColor="rgba(0,0,0,0.15)" />
        </linearGradient>
      </defs>
      <rect width="200" height="200" style={{ fill: "transparent" }} />
      <circle cx={cx} cy={cy} r="55" fill="rgba(255,255,255,0.18)" />
      <circle cx={200 - cx} cy={200 - cy} r="70" fill="rgba(255,255,255,0.10)" />
      <path d={`M0 ${140 + (seed % 20)} Q100 ${80 + (seed % 40)} 200 ${150 - (seed % 30)} L200 200 L0 200 Z`} fill="rgba(0,0,0,0.18)" />
      <rect width="200" height="200" fill={`url(#g-${seed})`} />
    </svg>
  );
}

function Tile({ item, onOpen }: { item: Item; onOpen: () => void }) {
  const ratioClass =
    item.ratio === "tall" ? "aspect-[3/4]" : item.ratio === "wide" ? "aspect-[4/3]" : "aspect-square";
  return (
    <button
      onClick={onOpen}
      className={`group relative overflow-hidden rounded-2xl border border-border shadow-card transition hover:-translate-y-0.5 hover:shadow-card-hover ${ratioClass}`}
      style={{ background: item.hue }}
    >
      <TileArt hue={item.hue} seed={item.seed} />
      <span className="absolute left-3 top-3 rounded-full bg-black/40 px-2.5 py-1 text-[10px] font-semibold text-white backdrop-blur">
        {item.date}
      </span>
      <div className="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-black/80 to-transparent p-4 text-left transition group-hover:translate-y-0">
        <div className="text-xs font-medium text-white/70">{item.project}</div>
        <div className="mt-0.5 font-serif-bn text-sm font-semibold text-white">{item.caption}</div>
      </div>
    </button>
  );
}

function SkeletonCard({ ratio }: { ratio: "tall" | "wide" | "square" }) {
  const cls = ratio === "tall" ? "aspect-[3/4]" : ratio === "wide" ? "aspect-[4/3]" : "aspect-square";
  return <div className={`animate-pulse rounded-2xl border border-border bg-muted ${cls}`} />;
}

function GalleryPage() {
  const [filter, setFilter] = useState("সব");
  const [visible, setVisible] = useState(8);
  const [openIdx, setOpenIdx] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const t = setTimeout(() => setLoading(false), 400);
    return () => clearTimeout(t);
  }, []);

  const filtered = filter === "সব" ? IMAGES : IMAGES.filter((i) => i.project === filter);
  const shown = filtered.slice(0, visible);

  useEffect(() => {
    if (openIdx === null) return;
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") setOpenIdx(null);
      if (e.key === "ArrowRight") setOpenIdx((i) => (i === null ? i : (i + 1) % filtered.length));
      if (e.key === "ArrowLeft") setOpenIdx((i) => (i === null ? i : (i - 1 + filtered.length) % filtered.length));
    };
    document.addEventListener("keydown", onKey);
    return () => document.removeEventListener("keydown", onKey);
  }, [openIdx, filtered.length]);

  const active = openIdx !== null ? filtered[openIdx] : null;

  return (
    <PageShell>
      <PageBanner
        title="গ্যালারি"
        subtitle="সংগঠনের চলমান ও সম্পন্ন কর্মকাণ্ডের কিছু নির্বাচিত মুহূর্ত।"
        crumbs={[{ label: "হোম", to: "/" }, { label: "গ্যালারি" }]}
      />

      <section className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        {/* Filter tabs */}
        <div className="mb-8 flex flex-wrap gap-2">
          {PROJECTS.map((p) => {
            const active = filter === p;
            return (
              <button
                key={p}
                onClick={() => {
                  setFilter(p);
                  setVisible(8);
                }}
                className={`rounded-full border px-4 py-2 text-sm font-medium transition ${
                  active
                    ? "border-primary bg-primary text-primary-foreground shadow-card"
                    : "border-border bg-surface text-foreground/80 hover:border-primary/40 hover:text-primary"
                }`}
              >
                {p}
              </button>
            );
          })}
        </div>

        {/* Masonry-like columns */}
        {loading ? (
          <div className="columns-2 gap-4 lg:columns-3 xl:columns-4 [&>*]:mb-4 [&>*]:break-inside-avoid">
            <SkeletonCard ratio="tall" />
            <SkeletonCard ratio="wide" />
            <SkeletonCard ratio="square" />
            <SkeletonCard ratio="wide" />
            <SkeletonCard ratio="tall" />
            <SkeletonCard ratio="square" />
          </div>
        ) : (
          <div className="columns-2 gap-4 lg:columns-3 xl:columns-4 [&>*]:mb-4 [&>*]:break-inside-avoid">
            {shown.map((item, idx) => (
              <Tile key={item.id} item={item} onOpen={() => setOpenIdx(idx)} />
            ))}
          </div>
        )}

        {visible < filtered.length && (
          <div className="mt-10 flex justify-center">
            <button
              onClick={() => setVisible((v) => v + 8)}
              className="inline-flex items-center gap-2 rounded-full border border-border bg-surface px-6 py-3 text-sm font-semibold text-foreground shadow-card transition hover:bg-primary-soft hover:text-primary"
            >
              আরও দেখুন
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
                <path d="M6 9l6 6 6-6" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </button>
          </div>
        )}
      </section>

      {/* Lightbox */}
      {active && (
        <div
          className="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 p-4 backdrop-blur-sm"
          onClick={() => setOpenIdx(null)}
        >
          <button
            aria-label="Close"
            onClick={() => setOpenIdx(null)}
            className="absolute right-4 top-4 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5">
              <path d="M6 6l12 12M18 6L6 18" />
            </svg>
          </button>
          <button
            aria-label="Previous"
            onClick={(e) => {
              e.stopPropagation();
              setOpenIdx((i) => (i === null ? i : (i - 1 + filtered.length) % filtered.length));
            }}
            className="absolute left-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5">
              <path d="M15 6l-6 6 6 6" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </button>
          <button
            aria-label="Next"
            onClick={(e) => {
              e.stopPropagation();
              setOpenIdx((i) => (i === null ? i : (i + 1) % filtered.length));
            }}
            className="absolute right-4 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-5 w-5">
              <path d="M9 6l6 6-6 6" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </button>

          <div
            className="relative flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-surface shadow-card-hover"
            onClick={(e) => e.stopPropagation()}
          >
            <div
              className="relative aspect-[16/10] w-full"
              style={{ background: active.hue }}
            >
              <TileArt hue={active.hue} seed={active.seed} />
            </div>
            <div className="flex flex-wrap items-center justify-between gap-3 border-t border-border p-5">
              <div className="min-w-0">
                <div className="text-xs font-semibold uppercase tracking-widest text-primary">
                  {active.project}
                </div>
                <div className="mt-1 font-serif-bn text-lg font-bold">{active.caption}</div>
              </div>
              <span className="rounded-full bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">
                {active.date}
              </span>
            </div>
          </div>
        </div>
      )}
    </PageShell>
  );
}
