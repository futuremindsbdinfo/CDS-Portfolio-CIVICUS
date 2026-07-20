import { createFileRoute, Link } from "@tanstack/react-router";
import { useState } from "react";
import { PageBanner, PageShell } from "../components/site-chrome";

export const Route = createFileRoute("/notices")({
  head: () => ({
    meta: [
      { title: "নোটিশ বোর্ড — Citizen Development Society (CDS)" },
      {
        name: "description",
        content: "CDS-এর গুরুত্বপূর্ণ ঘোষণা, পরিপত্র ও নোটিশসমূহ।",
      },
      { property: "og:title", content: "নোটিশ বোর্ড — CDS" },
      { property: "og:description", content: "গুরুত্বপূর্ণ ঘোষণা ও পরিপত্র।" },
    ],
  }),
  component: NoticesPage,
});

type Notice = {
  id: string;
  title: string;
  date: string;
  excerpt: string;
  content: string;
  hasPdf?: boolean;
  pdfName?: string;
  pdfSize?: string;
};

const NOTICES: Notice[] = [
  {
    id: "agm-2026",
    title: "বার্ষিক সাধারণ সভা ২০২৬ — অংশগ্রহণের আহ্বান",
    date: "১৫ মার্চ ২০২৬",
    excerpt:
      "সকল সদস্যকে সংগঠনের বার্ষিক সাধারণ সভায় উপস্থিত থাকার জন্য বিনীত অনুরোধ জানানো হচ্ছে।",
    content:
      "প্রিয় সদস্যবৃন্দ, Citizen Development Society (CDS)-এর বার্ষিক সাধারণ সভা আগামী ২৮ মার্চ ২০২৬, শনিবার, সকাল ১০টায় সংগঠনের প্রধান কার্যালয়ে অনুষ্ঠিত হবে। সভায় বিগত বছরের কার্যক্রম, নিরীক্ষিত হিসাব ও আগামী বছরের পরিকল্পনা উপস্থাপন করা হবে। সকল সদস্যের যথাসময়ে উপস্থিতি একান্ত কাম্য। উপস্থিতি নিশ্চিত করতে দয়া করে সংযুক্ত ফর্ম পূরণ করুন।",
    hasPdf: true,
    pdfName: "CDS-AGM-2026-Notice.pdf",
    pdfSize: "০.৮ MB",
  },
  {
    id: "winter-2025",
    title: "শীতবস্ত্র বিতরণ কর্মসূচি ২০২৫",
    date: "২০ ডিসেম্বর ২০২৫",
    excerpt: "উত্তরাঞ্চলে শীতার্তদের মাঝে কম্বল ও শীতবস্ত্র বিতরণ কর্মসূচি শুরু হচ্ছে।",
    content:
      "চলমান শীত মৌসুমে উত্তরবঙ্গের প্রান্তিক জনগোষ্ঠীর মাঝে কম্বল ও শীতবস্ত্র বিতরণের একটি বিশেষ কর্মসূচি হাতে নেওয়া হয়েছে। আগ্রহী সহযোগীরা যোগাযোগ করুন।",
    hasPdf: true,
    pdfName: "Winter-Program-2025.pdf",
    pdfSize: "১.২ MB",
  },
  {
    id: "vol-recruit",
    title: "স্বেচ্ছাসেবক নিয়োগ বিজ্ঞপ্তি — জানুয়ারি ২০২৬",
    date: "০৫ ডিসেম্বর ২০২৫",
    excerpt: "নতুন প্রকল্পের জন্য আগ্রহী স্বেচ্ছাসেবকদের কাছ থেকে আবেদন আহ্বান করা হচ্ছে।",
    content:
      "শিক্ষা, স্বাস্থ্য ও পরিবেশ সংক্রান্ত তিনটি নতুন প্রকল্পের জন্য মোট ৪০ জন স্বেচ্ছাসেবক নিয়োগ করা হবে। যোগ্যতা: ন্যূনতম উচ্চ মাধ্যমিক পাশ, বয়স ১৮-৩৫। আগ্রহীরা অনলাইনে আবেদন করতে পারবেন।",
  },
  {
    id: "tree-report",
    title: "বৃক্ষরোপণ অভিযান ২০২৫ সফলভাবে সমাপ্ত",
    date: "১০ নভেম্বর ২০২৫",
    excerpt: "সারা দেশে ৫০০০+ গাছের চারা রোপণ কার্যক্রম সফলভাবে সম্পন্ন হয়েছে।",
    content:
      "চার মাসব্যাপী বৃক্ষরোপণ অভিযান শেষে ১২টি জেলায় মোট ৫,২০০টি চারা রোপণ করা হয়েছে। বিস্তারিত প্রতিবেদন আমাদের প্রকাশনায় পাওয়া যাবে।",
    hasPdf: true,
    pdfName: "Tree-Plantation-Report-2025.pdf",
    pdfSize: "২.৪ MB",
  },
  {
    id: "office-relocation",
    title: "প্রধান কার্যালয় স্থানান্তরের বিজ্ঞপ্তি",
    date: "২৮ অক্টোবর ২০২৫",
    excerpt: "১ ডিসেম্বর ২০২৫ থেকে আমাদের প্রধান কার্যালয় নতুন ঠিকানায় স্থানান্তরিত হবে।",
    content:
      "প্রশাসনিক সুবিধার্থে সংগঠনের প্রধান কার্যালয় বাড়ি ২৩, রোড ৫, ধানমন্ডি, ঢাকা-১২০৫ ঠিকানায় স্থানান্তরিত হবে। সকল যোগাযোগ পূর্বের ফোন ও ইমেইলে যথারীতি চালু থাকবে।",
  },
  {
    id: "health-camp",
    title: "মাতৃস্বাস্থ্য ক্যাম্প — নেত্রকোনা জেলা",
    date: "১৫ অক্টোবর ২০২৫",
    excerpt: "নেত্রকোনায় বিনামূল্যে মাতৃসেবা ক্যাম্প আয়োজন করা হচ্ছে।",
    content:
      "নেত্রকোনা জেলার তিনটি উপজেলায় তিন দিনব্যাপী বিনামূল্যে মাতৃস্বাস্থ্য ক্যাম্প পরিচালনা করা হবে। বিশেষজ্ঞ চিকিৎসক দল উপস্থিত থাকবেন।",
    hasPdf: true,
    pdfName: "Maternal-Camp-Netrokona.pdf",
    pdfSize: "৭৩০ KB",
  },
  {
    id: "library-donation",
    title: "গ্রামীণ পাঠাগারে বই অনুদানের আহ্বান",
    date: "২২ সেপ্টেম্বর ২০২৫",
    excerpt: "নতুন ১০টি পাঠাগারের জন্য বই সংগ্রহ চলছে — যেকোনো ভাল বই দান করুন।",
    content:
      "নতুন প্রতিষ্ঠিত ১০টি গ্রামীণ পাঠাগারের জন্য শিশু-কিশোরদের উপযোগী বই সংগ্রহ চলছে। আপনার সংগ্রহ থেকে অব্যবহৃত বই দান করে সহায়তা করুন।",
  },
  {
    id: "audit-report",
    title: "নিরীক্ষা প্রতিবেদন ২০২৪-২৫ প্রকাশিত",
    date: "১৪ সেপ্টেম্বর ২০২৫",
    excerpt: "স্বাধীন নিরীক্ষা সংস্থা কর্তৃক প্রণীত বার্ষিক নিরীক্ষা প্রতিবেদন প্রকাশ করা হয়েছে।",
    content:
      "২০২৪-২৫ অর্থবছরের নিরীক্ষা প্রতিবেদন আমাদের ওয়েবসাইটে প্রকাশ করা হয়েছে। স্বচ্ছতা ও জবাবদিহিতার প্রতিশ্রুতি অনুযায়ী প্রতিবেদনটি সর্বসাধারণের জন্য উন্মুক্ত।",
    hasPdf: true,
    pdfName: "Audit-Report-2024-25.pdf",
    pdfSize: "১.৭ MB",
  },
];

const IMPORTANT_LINKS = [
  { label: "বার্ষিক প্রতিবেদন ২০২৫", href: "#" },
  { label: "সাংগঠনিক গঠনতন্ত্র", href: "#" },
  { label: "স্বেচ্ছাসেবক নীতিমালা", href: "#" },
  { label: "স্বচ্ছতা ও নিরীক্ষা", href: "#" },
];

function PdfBadge() {
  return (
    <span className="inline-flex items-center gap-1 rounded-full border border-warning/40 bg-warning/15 px-2 py-0.5 text-[10px] font-semibold text-warning-foreground">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-3 w-3">
        <path d="M14 3H6v18h12V7z M14 3v4h4" strokeLinejoin="round" />
      </svg>
      PDF সংযুক্ত
    </span>
  );
}

function NoticeCard({ n, onOpen }: { n: Notice; onOpen: () => void }) {
  return (
    <article className="group rounded-2xl border border-border bg-surface p-5 shadow-card transition hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-card-hover sm:p-6">
      <div className="flex flex-wrap items-center gap-2 text-xs">
        <span className="rounded-full bg-primary-soft px-2.5 py-1 font-semibold text-primary">
          {n.date}
        </span>
        {n.hasPdf && <PdfBadge />}
      </div>
      <h3 className="mt-3 font-serif-bn text-lg font-bold leading-snug group-hover:text-primary sm:text-xl">
        {n.title}
      </h3>
      <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{n.excerpt}</p>
      <button
        onClick={onOpen}
        className="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:underline"
      >
        বিস্তারিত দেখুন
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
          <path d="M5 12h14M13 6l6 6-6 6" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </button>
    </article>
  );
}

function NoticesPage() {
  const [openId, setOpenId] = useState<string | null>(null);
  const [page, setPage] = useState(1);
  const perPage = 5;
  const active = NOTICES.find((n) => n.id === openId) ?? null;

  const totalPages = Math.ceil(NOTICES.length / perPage);
  const paged = NOTICES.slice((page - 1) * perPage, page * perPage);

  if (active) {
    const related = NOTICES.filter((n) => n.id !== active.id).slice(0, 3);
    return (
      <PageShell>
        <PageBanner
          title={active.title}
          crumbs={[
            { label: "হোম", to: "/" },
            { label: "নোটিশ বোর্ড", to: "/notices" },
            { label: active.title.length > 30 ? active.title.slice(0, 30) + "…" : active.title },
          ]}
        />
        <section className="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_320px] lg:px-8">
          <article>
            <div className="flex flex-wrap items-center gap-2 text-xs">
              <span className="rounded-full bg-primary-soft px-2.5 py-1 font-semibold text-primary">
                প্রকাশিত: {active.date}
              </span>
              {active.hasPdf && <PdfBadge />}
            </div>
            <div className="mt-6 rounded-2xl border border-border bg-surface p-6 shadow-card sm:p-8">
              <p className="font-serif-bn text-base leading-[1.9] text-foreground sm:text-lg">
                {active.content}
              </p>
              {active.hasPdf && (
                <div className="mt-8 flex flex-wrap items-center gap-4 rounded-2xl border border-primary/20 bg-primary-soft/40 p-4">
                  <span className="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-white text-primary shadow-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
                      <path d="M14 3H6v18h12V7z M14 3v4h4" strokeLinejoin="round" />
                      <text x="12" y="17" textAnchor="middle" fontSize="5" fontWeight="700" fill="currentColor" stroke="none">PDF</text>
                    </svg>
                  </span>
                  <div className="min-w-0 flex-1">
                    <div className="truncate font-serif-bn text-sm font-bold">{active.pdfName}</div>
                    <div className="text-xs text-muted-foreground">PDF নথি · {active.pdfSize}</div>
                  </div>
                  <div className="flex gap-2">
                    <a href="#" className="inline-flex items-center gap-1.5 rounded-full border border-border bg-surface px-4 py-2 text-xs font-semibold hover:bg-primary-soft hover:text-primary">
                      দেখুন
                    </a>
                    <a href="#" className="inline-flex items-center gap-1.5 rounded-full bg-primary px-4 py-2 text-xs font-semibold text-primary-foreground shadow-card hover:brightness-110">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-3.5 w-3.5">
                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" strokeLinecap="round" strokeLinejoin="round" />
                      </svg>
                      ডাউনলোড
                    </a>
                  </div>
                </div>
              )}
            </div>

            <button
              onClick={() => setOpenId(null)}
              className="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline"
            >
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
                <path d="M19 12H5M11 6l-6 6 6 6" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
              সব নোটিশে ফিরে যান
            </button>

            <div className="mt-12">
              <h3 className="font-serif-bn text-xl font-bold">সাম্প্রতিক অন্যান্য নোটিশ</h3>
              <div className="mt-4 grid gap-4 sm:grid-cols-2">
                {related.map((r) => (
                  <button
                    key={r.id}
                    onClick={() => {
                      setOpenId(r.id);
                      window.scrollTo({ top: 0, behavior: "smooth" });
                    }}
                    className="text-left rounded-xl border border-border bg-surface p-4 shadow-card transition hover:border-primary/30 hover:shadow-card-hover"
                  >
                    <div className="text-xs font-semibold text-primary">{r.date}</div>
                    <div className="mt-1 font-serif-bn text-sm font-bold leading-snug">{r.title}</div>
                  </button>
                ))}
              </div>
            </div>
          </article>

          <RecentSidebar activeId={active.id} onOpen={setOpenId} />
        </section>
      </PageShell>
    );
  }

  return (
    <PageShell>
      <PageBanner
        title="নোটিশ বোর্ড"
        subtitle="সাংগঠনিক ঘোষণা, পরিপত্র, নিয়োগ ও প্রতিবেদনসমূহ এক জায়গায়।"
        crumbs={[{ label: "হোম", to: "/" }, { label: "নোটিশ বোর্ড" }]}
      />
      <section className="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_320px] lg:px-8">
        <div className="space-y-4">
          {paged.map((n) => (
            <NoticeCard key={n.id} n={n} onOpen={() => setOpenId(n.id)} />
          ))}

          {/* Pagination */}
          <div className="mt-8 flex items-center justify-center gap-2">
            <button
              disabled={page === 1}
              onClick={() => setPage((p) => Math.max(1, p - 1))}
              className="grid h-9 w-9 place-items-center rounded-full border border-border bg-surface text-sm disabled:opacity-40"
            >
              ‹
            </button>
            {Array.from({ length: totalPages }).map((_, i) => {
              const p = i + 1;
              const active = p === page;
              return (
                <button
                  key={p}
                  onClick={() => setPage(p)}
                  className={`h-9 min-w-9 rounded-full px-3 text-sm font-semibold transition ${
                    active
                      ? "bg-primary text-primary-foreground shadow-card"
                      : "border border-border bg-surface hover:bg-primary-soft hover:text-primary"
                  }`}
                >
                  {p.toLocaleString("bn-BD")}
                </button>
              );
            })}
            <button
              disabled={page === totalPages}
              onClick={() => setPage((p) => Math.min(totalPages, p + 1))}
              className="grid h-9 w-9 place-items-center rounded-full border border-border bg-surface text-sm disabled:opacity-40"
            >
              ›
            </button>
          </div>
        </div>

        <RecentSidebar activeId={null} onOpen={setOpenId} />
      </section>
    </PageShell>
  );
}

function RecentSidebar({
  activeId,
  onOpen,
}: {
  activeId: string | null;
  onOpen: (id: string) => void;
}) {
  return (
    <aside className="space-y-6 lg:sticky lg:top-24 lg:h-fit">
      <div className="rounded-2xl border border-border bg-surface p-5 shadow-card">
        <div className="flex items-center gap-2 border-b border-border pb-3">
          <span className="grid h-8 w-8 place-items-center rounded-lg bg-primary-soft text-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
              <path d="M6 3h9l5 5v13H6z" strokeLinejoin="round" />
            </svg>
          </span>
          <h3 className="font-serif-bn text-base font-bold">সাম্প্রতিক নোটিশ</h3>
        </div>
        <ul className="mt-3 space-y-3">
          {NOTICES.slice(0, 5).map((n) => (
            <li key={n.id}>
              <button
                onClick={() => onOpen(n.id)}
                className={`block w-full text-left transition ${
                  activeId === n.id ? "text-primary" : "text-foreground/85 hover:text-primary"
                }`}
              >
                <div className="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                  {n.date}
                </div>
                <div className="mt-0.5 font-serif-bn text-sm font-semibold leading-snug">
                  {n.title}
                </div>
              </button>
            </li>
          ))}
        </ul>
      </div>

      <div className="rounded-2xl border border-secondary/20 bg-secondary/5 p-5 shadow-card">
        <h3 className="font-serif-bn text-base font-bold">গুরুত্বপূর্ণ লিংক</h3>
        <ul className="mt-3 space-y-2">
          {IMPORTANT_LINKS.map((l) => (
            <li key={l.label}>
              <Link
                to="/"
                className="flex items-center justify-between gap-2 rounded-lg px-2 py-1.5 text-sm text-foreground/85 hover:bg-white hover:text-secondary"
              >
                <span>{l.label}</span>
                <span className="text-xs text-muted-foreground">→</span>
              </Link>
            </li>
          ))}
        </ul>
      </div>
    </aside>
  );
}
