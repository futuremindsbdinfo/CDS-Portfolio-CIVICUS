import { createFileRoute, Link } from "@tanstack/react-router";
import { useState, type ReactNode } from "react";

export const Route = createFileRoute("/admin")({
  head: () => ({
    meta: [
      { title: "অ্যাডমিন প্যানেল · CDS" },
      { name: "description", content: "CDS অ্যাডমিন ড্যাশবোর্ড।" },
      { name: "robots", content: "noindex" },
    ],
  }),
  component: AdminApp,
});

type SectionKey =
  | "dashboard"
  | "notices"
  | "projects"
  | "gallery"
  | "messages"
  | "donations"
  | "settings";

const NAV: { key: SectionKey | "logout"; label: string; icon: ReactNode }[] = [
  { key: "dashboard", label: "Dashboard", icon: <IconGrid /> },
  { key: "notices", label: "Notices", icon: <IconDoc /> },
  { key: "projects", label: "Projects", icon: <IconLayers /> },
  { key: "gallery", label: "Gallery", icon: <IconImage /> },
  { key: "messages", label: "Contact Messages", icon: <IconMail /> },
  { key: "donations", label: "Donation Interests", icon: <IconHeart /> },
  { key: "settings", label: "Admin Settings", icon: <IconCog /> },
  { key: "logout", label: "Logout", icon: <IconLogout /> },
];

function IconCog() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <circle cx="12" cy="12" r="3" />
      <path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1A1.7 1.7 0 004.6 9a1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z" />
    </svg>
  );
}
function IconEye({ off = false }: { off?: boolean }) {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
      <circle cx="12" cy="12" r="3" />
      {off && <path d="M3 3l18 18" strokeLinecap="round" />}
    </svg>
  );
}
function IconCheck() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
      <path d="M5 12l4 4 10-10" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}
function IconXMark() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
      <path d="M6 6l12 12M18 6L6 18" strokeLinecap="round" />
    </svg>
  );
}
function IconClock() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <circle cx="12" cy="12" r="9" />
      <path d="M12 7v5l3 2" strokeLinecap="round" />
    </svg>
  );
}

function IconGrid() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <rect x="3" y="3" width="7" height="7" rx="1.5" />
      <rect x="14" y="3" width="7" height="7" rx="1.5" />
      <rect x="3" y="14" width="7" height="7" rx="1.5" />
      <rect x="14" y="14" width="7" height="7" rx="1.5" />
    </svg>
  );
}
function IconDoc() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <path d="M6 3h9l5 5v13H6z M14 3v6h6" strokeLinejoin="round" />
    </svg>
  );
}
function IconLayers() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5M3 18l9 5 9-5" strokeLinejoin="round" />
    </svg>
  );
}
function IconImage() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <rect x="3" y="5" width="18" height="14" rx="2" />
      <circle cx="9" cy="11" r="2" />
      <path d="M3 17l5-4 5 4 3-3 5 4" />
    </svg>
  );
}
function IconMail() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <rect x="3" y="5" width="18" height="14" rx="2" />
      <path d="M3 7l9 6 9-6" />
    </svg>
  );
}
function IconHeart() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" />
    </svg>
  );
}
function IconLogout() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <path d="M15 4h4v16h-4M10 8l-4 4 4 4M6 12h10" strokeLinejoin="round" />
    </svg>
  );
}
function IconPlus() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
      <path d="M12 5v14M5 12h14" strokeLinecap="round" />
    </svg>
  );
}
function IconSearch() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <circle cx="11" cy="11" r="7" />
      <path d="M20 20l-3.5-3.5" strokeLinecap="round" />
    </svg>
  );
}
function IconEdit() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <path d="M4 20h4l10-10-4-4L4 16v4z M13 6l4 4" strokeLinejoin="round" />
    </svg>
  );
}
function IconTrash() {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
      <path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" strokeLinejoin="round" />
    </svg>
  );
}

/* --------------------- Dummy data --------------------- */

const STATS = [
  { label: "Total Notices", value: 24, tone: "green" },
  { label: "Total Projects", value: 18, tone: "blue" },
  { label: "Gallery Images", value: 132, tone: "amber" },
  { label: "Unread Messages", value: 7, tone: "rose" },
  { label: "Pending Donations", value: 12, tone: "violet" },
  { label: "Total Admins", value: 4, tone: "slate" },
];

const ACTIVITY = [
  { t: "New contact message from Rahim Uddin", ago: "5 min ago" },
  { t: "New donation interest submitted — ৳ ৫,০০০", ago: "22 min ago" },
  { t: "Gallery image uploaded: Winter Camp 2025", ago: "1 hr ago" },
  { t: "Notice published: বার্ষিক সাধারণ সভা", ago: "3 hr ago" },
  { t: "Project status updated: Rural Library → Ongoing", ago: "Yesterday" },
];

const CHART = [42, 55, 38, 70, 62, 88, 74, 95, 80, 110, 96, 120];
const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

const NOTICES = [
  { title: "বার্ষিক সাধারণ সভা ২০২৬", date: "২০২৬-০৩-১৫", pdf: true },
  { title: "শীতবস্ত্র বিতরণ কর্মসূচি", date: "২০২৫-১২-২০", pdf: true },
  { title: "স্বেচ্ছাসেবক নিয়োগ বিজ্ঞপ্তি", date: "২০২৫-১২-০৫", pdf: false },
  { title: "বৃক্ষরোপণ অভিযান সফল", date: "২০২৫-১১-১০", pdf: false },
];

const PROJECTS = [
  { title: "গ্রামীণ পাঠাগার", status: "ongoing", start: "২০২৫-০১-১৫", end: "চলমান" },
  { title: "মাতৃস্বাস্থ্য ক্যাম্প", status: "completed", start: "২০২৪-০৬-১০", end: "২০২৪-১২-০১" },
  { title: "নাগরিক সচেতনতা কর্মশালা", status: "ongoing", start: "২০২৫-০৩-০১", end: "চলমান" },
  { title: "বৃক্ষরোপণ অভিযান", status: "completed", start: "২০২৫-০৭-০৫", end: "২০২৫-১১-১০" },
];

const GALLERY = [
  { cap: "Winter Camp 2025", hue: "from-emerald-400 to-teal-600" },
  { cap: "Book Distribution", hue: "from-blue-400 to-indigo-600" },
  { cap: "Health Camp", hue: "from-amber-400 to-orange-600" },
  { cap: "Youth Workshop", hue: "from-rose-400 to-pink-600" },
  { cap: "Tree Plantation", hue: "from-lime-400 to-green-600" },
  { cap: "Community Meet", hue: "from-violet-400 to-purple-600" },
  { cap: "Relief Drive", hue: "from-cyan-400 to-sky-600" },
  { cap: "Awareness Rally", hue: "from-fuchsia-400 to-pink-600" },
];

const MESSAGES = [
  { name: "Rahim Uddin", email: "rahim@example.com", phone: "০১৭১১-১২৩৪৫৬", subject: "স্বেচ্ছাসেবক হতে চাই", date: "২০২৬-০১-১৫", read: false, ip: "103.15.24.87",
    body: "আসসালামু আলাইকুম। আমি আপনাদের সংগঠনের একজন স্বেচ্ছাসেবক হিসেবে কাজ করতে আগ্রহী। দয়া করে আমাকে প্রয়োজনীয় তথ্য দিয়ে সহায়তা করবেন। ধন্যবাদ।" },
  { name: "Fatema Begum", email: "fatema@example.com", phone: "০১৮১১-৯৯৮৮৭৭", subject: "ডোনেশন সংক্রান্ত জিজ্ঞাসা", date: "২০২৬-০১-১৪", read: false, ip: "103.15.28.14",
    body: "আমি প্রতি মাসে নিয়মিত দান করতে চাই। এর জন্য কোন প্রক্রিয়া অনুসরণ করতে হবে দয়া করে জানাবেন।" },
  { name: "Karim Sheikh", email: "karim@example.com", phone: "০১৯১১-৫৫৪৪৩৩", subject: "প্রকল্প প্রস্তাব", date: "২০২৬-০১-১২", read: true, ip: "45.120.8.10",
    body: "আমাদের এলাকায় একটি পাঠাগার প্রতিষ্ঠার জন্য একটি প্রস্তাবনা পাঠাতে চাই।" },
  { name: "Ayesha Rahman", email: "ayesha@example.com", phone: "০১৭৭৭-১২৩৪৫৬", subject: "মিডিয়া রিকোয়েস্ট", date: "২০২৬-০১-১০", read: true, ip: "203.82.155.22",
    body: "একটি সংবাদপত্রের জন্য সাক্ষাৎকার নিতে চাই।" },
];

const DONATIONS = [
  { donor: "Sabbir Ahmed", email: "sabbir@example.com", phone: "০১৭১১-১১১১১১", amtRaw: 10000, amt: "৳ ১০,০০০", method: "bKash", txn: "TRX8823401", status: "verified", date: "২০২৬-০১-১৪", ip: "103.15.10.4" },
  { donor: "Nusrat Jahan", email: "nusrat@example.com", phone: "০১৮১১-২২২২২২", amtRaw: 5000, amt: "৳ ৫,০০০", method: "Bank", txn: "BNK-556677", status: "pending", date: "২০২৬-০১-১৩", ip: "103.15.10.8" },
  { donor: "Tanvir Hasan", email: "tanvir@example.com", phone: "০১৯১১-৩৩৩৩৩৩", amtRaw: 2500, amt: "৳ ২,৫০০", method: "Nagad", txn: "NGD-998812", status: "pending", date: "২০২৬-০১-১২", ip: "103.15.11.19" },
  { donor: "Rumana Akter", email: "rumana@example.com", phone: "০১৭২১-৪৪৪৪৪৪", amtRaw: 20000, amt: "৳ ২০,০০০", method: "Bank", txn: "BNK-102938", status: "verified", date: "২০২৬-০১-১০", ip: "203.82.155.9" },
  { donor: "Anonymous", email: "—", phone: "—", amtRaw: 500, amt: "৳ ৫০০", method: "Rocket", txn: "RKT-000345", status: "rejected", date: "২০২৬-০১-০৯", ip: "45.120.7.5" },
];

const ACTIVITY_FEED = [
  { type: "message", text: "নতুন মেসেজ — Rahim Uddin", ago: "২ ঘণ্টা আগে" },
  { type: "donation", text: "নতুন ডোনেশন ইন্টারেস্ট — ৳ ১০,০০০ (bKash)", ago: "৪ ঘণ্টা আগে" },
  { type: "notice", text: "নোটিশ প্রকাশিত — বার্ষিক সাধারণ সভা ২০২৬", ago: "গতকাল" },
  { type: "project", text: "প্রজেক্ট আপডেট — গ্রামীণ পাঠাগার", ago: "২ দিন আগে" },
  { type: "gallery", text: "গ্যালারিতে ৫টি নতুন ছবি যোগ", ago: "৩ দিন আগে" },
  { type: "message", text: "নতুন মেসেজ — Fatema Begum", ago: "৩ দিন আগে" },
];

const WEEK_CHART = [12, 18, 9, 24, 30, 22, 27];
const WEEK_DAYS = ["শনি", "রবি", "সোম", "মঙ্গল", "বুধ", "বৃহঃ", "শুক্র"];

const ADMIN_PROFILE = {
  username: "admin",
  fullName: "মোঃ আব্দুল করিম",
  email: "admin@cds-bd.org",
  lastLogin: "২০২৬-০১-১৫ · ১০:২৪ AM",
};

/* --------------------- Shared UI --------------------- */

function Badge({ tone, children }: { tone: "success" | "warning" | "danger" | "muted"; children: ReactNode }) {
  const cls =
    tone === "success"
      ? "bg-emerald-100 text-emerald-700 ring-emerald-200"
      : tone === "warning"
        ? "bg-amber-100 text-amber-700 ring-amber-200"
        : tone === "danger"
          ? "bg-rose-100 text-rose-700 ring-rose-200"
          : "bg-slate-100 text-slate-700 ring-slate-200";
  return (
    <span className={`inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ${cls}`}>
      {children}
    </span>
  );
}

function statusTone(s: string): "success" | "warning" | "danger" | "muted" {
  if (s === "completed" || s === "verified") return "success";
  if (s === "ongoing" || s === "pending") return "warning";
  if (s === "rejected" || s === "unread") return "danger";
  return "muted";
}

function SectionHeader({
  title,
  desc,
  action,
}: {
  title: string;
  desc?: string;
  action?: ReactNode;
}) {
  return (
    <div className="mb-6 flex flex-wrap items-end justify-between gap-4">
      <div>
        <h1 className="font-serif-bn text-2xl font-bold text-slate-900">{title}</h1>
        {desc && <p className="mt-1 text-sm text-slate-500">{desc}</p>}
      </div>
      {action}
    </div>
  );
}

function Table({ headers, children }: { headers: string[]; children: ReactNode }) {
  return (
    <div className="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
      <div className="overflow-x-auto">
        <table className="min-w-full divide-y divide-slate-200">
          <thead className="bg-slate-50">
            <tr>
              {headers.map((h) => (
                <th
                  key={h}
                  className="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500"
                >
                  <span className="inline-flex items-center gap-1">
                    {h}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-3 w-3 text-slate-400">
                      <path d="M8 9l4-4 4 4M8 15l4 4 4-4" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </span>
                </th>
              ))}
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100 bg-white">{children}</tbody>
        </table>
      </div>
      <div className="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
        <div>Showing 1–10 of 24</div>
        <div className="flex gap-1">
          <button className="rounded-md border border-slate-200 px-2.5 py-1 hover:bg-slate-50">‹</button>
          <button className="rounded-md border border-slate-200 bg-primary px-2.5 py-1 text-white">1</button>
          <button className="rounded-md border border-slate-200 px-2.5 py-1 hover:bg-slate-50">2</button>
          <button className="rounded-md border border-slate-200 px-2.5 py-1 hover:bg-slate-50">3</button>
          <button className="rounded-md border border-slate-200 px-2.5 py-1 hover:bg-slate-50">›</button>
        </div>
      </div>
    </div>
  );
}

function IconBtn({ children, tone = "default" }: { children: ReactNode; tone?: "default" | "danger" }) {
  return (
    <button
      className={`grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white hover:bg-slate-50 ${
        tone === "danger" ? "text-rose-600 hover:bg-rose-50" : "text-slate-600"
      }`}
    >
      {children}
    </button>
  );
}

function PrimaryButton({ children, onClick }: { children: ReactNode; onClick?: () => void }) {
  return (
    <button
      onClick={onClick}
      className="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110"
    >
      {children}
    </button>
  );
}

function GhostButton({ children, onClick }: { children: ReactNode; onClick?: () => void }) {
  return (
    <button
      onClick={onClick}
      className="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
    >
      {children}
    </button>
  );
}

function Modal({ open, onClose, title, children, footer }: { open: boolean; onClose: () => void; title: string; children: ReactNode; footer?: ReactNode }) {
  if (!open) return null;
  return (
    <div className="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-4">
      <div className="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div className="flex items-center justify-between border-b border-slate-200 px-5 py-4">
          <div className="font-serif-bn text-lg font-bold text-slate-900">{title}</div>
          <button onClick={onClose} className="grid h-8 w-8 place-items-center rounded-md text-slate-500 hover:bg-slate-100">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
              <path d="M6 6l12 12M18 6L6 18" />
            </svg>
          </button>
        </div>
        <div className="max-h-[70vh] overflow-y-auto px-5 py-5">{children}</div>
        {footer && <div className="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-3">{footer}</div>}
      </div>
    </div>
  );
}

function Field({ label, children }: { label: string; children: ReactNode }) {
  return (
    <label className="block">
      <span className="mb-1.5 block text-xs font-semibold text-slate-600">{label}</span>
      {children}
    </label>
  );
}

const inputCls =
  "w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20";

function EmptyState({ message }: { message: string }) {
  return (
    <div className="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
      <div className="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-7 w-7">
          <rect x="3" y="4" width="18" height="16" rx="2" />
          <path d="M3 10h18M8 4v16" />
        </svg>
      </div>
      <div className="font-serif-bn text-sm font-semibold text-slate-700">{message}</div>
    </div>
  );
}

/* --------------------- Sections --------------------- */

function Dashboard() {
  const toneMap: Record<string, string> = {
    green: "from-emerald-500/10 to-emerald-500/0 text-emerald-700 ring-emerald-500/20",
    blue: "from-blue-500/10 to-blue-500/0 text-blue-700 ring-blue-500/20",
    amber: "from-amber-500/10 to-amber-500/0 text-amber-700 ring-amber-500/20",
    rose: "from-rose-500/10 to-rose-500/0 text-rose-700 ring-rose-500/20",
    violet: "from-violet-500/10 to-violet-500/0 text-violet-700 ring-violet-500/20",
    slate: "from-slate-500/10 to-slate-500/0 text-slate-700 ring-slate-500/20",
  };
  const iconMap: Record<string, ReactNode> = {
    "Total Notices": <IconDoc />,
    "Total Projects": <IconLayers />,
    "Gallery Images": <IconImage />,
    "Unread Messages": <IconMail />,
    "Pending Donations": <IconHeart />,
    "Total Admins": <IconCog />,
  };
  const activityIcon = (t: string) => {
    if (t === "message") return <IconMail />;
    if (t === "donation") return <IconHeart />;
    if (t === "notice") return <IconDoc />;
    if (t === "project") return <IconLayers />;
    return <IconImage />;
  };
  const activityTone = (t: string) =>
    t === "message" ? "bg-rose-100 text-rose-700" :
    t === "donation" ? "bg-violet-100 text-violet-700" :
    t === "notice" ? "bg-emerald-100 text-emerald-700" :
    t === "project" ? "bg-blue-100 text-blue-700" : "bg-amber-100 text-amber-700";
  const maxWeek = Math.max(...WEEK_CHART);

  return (
    <div>
      <SectionHeader title="Dashboard Overview" desc="সবকিছু এক নজরে · আজ, ১৫ জানুয়ারি ২০২৬" />
      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        {STATS.map((s) => {
          const highlight = (s.label === "Unread Messages" || s.label === "Pending Donations") && (s.value as number) > 0;
          return (
            <div
              key={s.label}
              className={`relative overflow-hidden rounded-xl bg-gradient-to-br ${toneMap[s.tone]} bg-white p-4 shadow-sm ring-1 ${
                highlight ? "ring-2 ring-rose-400/60" : ""
              }`}
            >
              <div className="flex items-center justify-between">
                <div className="text-xs font-medium text-slate-500">{s.label}</div>
                <span className="grid h-7 w-7 place-items-center rounded-md bg-white/70 text-slate-600 ring-1 ring-slate-200">
                  {iconMap[s.label]}
                </span>
              </div>
              <div className="mt-2 font-serif-bn text-3xl font-bold text-slate-900">
                {s.value}
                {highlight && <span className="ml-1.5 inline-block h-2 w-2 rounded-full bg-rose-500 align-middle" />}
              </div>
              <div className="mt-1 text-[11px] font-medium">
                {highlight ? "মনোযোগ প্রয়োজন" : "↑ 12% এই মাসে"}
              </div>
            </div>
          );
        })}
      </div>

      <div className="mt-6 grid gap-6 lg:grid-cols-[3fr_2fr]">
        {/* Recent Activity */}
        <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <div className="mb-4 flex items-center justify-between">
            <div className="font-serif-bn text-base font-bold text-slate-900">সাম্প্রতিক কার্যক্রম</div>
            <GhostButton>সব দেখুন</GhostButton>
          </div>
          <ul className="relative space-y-4 pl-3">
            <span className="absolute left-[11px] top-1 bottom-1 w-px bg-slate-200" />
            {ACTIVITY_FEED.map((a, i) => (
              <li key={i} className="relative flex gap-3">
                <span className={`z-10 grid h-6 w-6 shrink-0 place-items-center rounded-full ring-4 ring-white ${activityTone(a.type)}`}>
                  <span className="scale-75">{activityIcon(a.type)}</span>
                </span>
                <div className="min-w-0 flex-1">
                  <div className="text-sm text-slate-800">{a.text}</div>
                  <div className="mt-0.5 inline-flex items-center gap-1 text-[11px] text-slate-500">
                    <IconClock /> {a.ago}
                  </div>
                </div>
              </li>
            ))}
          </ul>
        </div>

        {/* Quick Actions */}
        <div className="space-y-5">
          <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="mb-3 font-serif-bn text-base font-bold text-slate-900">Quick Actions</div>
            <div className="grid gap-2">
              <button className="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition">
                <span className="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary group-hover:bg-white/20"><IconPlus /></span>
                নতুন নোটিশ যোগ করুন
              </button>
              <button className="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition">
                <span className="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary"><IconLayers /></span>
                নতুন প্রজেক্ট যোগ করুন
              </button>
              <button className="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition">
                <span className="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary"><IconMail /></span>
                মেসেজ দেখুন
              </button>
              <button className="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-semibold text-slate-800 hover:bg-primary hover:text-primary-foreground hover:border-primary transition">
                <span className="grid h-8 w-8 place-items-center rounded-md bg-primary/10 text-primary"><IconImage /></span>
                গ্যালারিতে ছবি আপলোড
              </button>
            </div>
          </div>

          <div className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="mb-3 flex items-center justify-between">
              <div>
                <div className="font-serif-bn text-base font-bold text-slate-900">গত ৭ দিন</div>
                <div className="text-xs text-slate-500">Submissions & messages</div>
              </div>
            </div>
            <div className="flex h-32 items-end gap-2">
              {WEEK_CHART.map((v, i) => (
                <div key={i} className="flex flex-1 flex-col items-center gap-1.5">
                  <div
                    className="w-full rounded-t-md bg-gradient-to-t from-primary to-primary/60"
                    style={{ height: `${(v / maxWeek) * 100}%` }}
                    title={`${WEEK_DAYS[i]}: ${v}`}
                  />
                  <div className="text-[10px] text-slate-500">{WEEK_DAYS[i]}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

function NoticesSection() {
  const [openForm, setOpenForm] = useState(false);
  return (
    <div>
      <SectionHeader
        title="Notices"
        desc="সকল প্রকাশিত ও খসড়া নোটিশ ব্যবস্থাপনা"
        action={
          <PrimaryButton onClick={() => setOpenForm(true)}>
            <IconPlus /> নতুন নোটিশ যোগ করুন
          </PrimaryButton>
        }
      />
      <Table headers={["Title (BN)", "Published", "PDF", "Actions"]}>
        {NOTICES.map((n, i) => (
          <tr key={n.title} className={i % 2 ? "bg-slate-50/50" : ""}>
            <td className="px-4 py-3 font-serif-bn font-semibold text-slate-900">{n.title}</td>
            <td className="px-4 py-3 text-sm text-slate-600">{n.date}</td>
            <td className="px-4 py-3">
              {n.pdf ? <Badge tone="success">Attached</Badge> : <Badge tone="muted">None</Badge>}
            </td>
            <td className="px-4 py-3">
              <div className="flex gap-1.5">
                <IconBtn><IconEdit /></IconBtn>
                <IconBtn tone="danger"><IconTrash /></IconBtn>
              </div>
            </td>
          </tr>
        ))}
      </Table>

      <Modal
        open={openForm}
        onClose={() => setOpenForm(false)}
        title="নতুন নোটিশ যোগ করুন"
        footer={
          <>
            <GhostButton onClick={() => setOpenForm(false)}>Cancel</GhostButton>
            <PrimaryButton onClick={() => setOpenForm(false)}>Save Notice</PrimaryButton>
          </>
        }
      >
        <div className="space-y-4">
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="শিরোনাম (Bangla)"><input className={inputCls} placeholder="নোটিশ শিরোনাম" /></Field>
            <Field label="Title (English)"><input className={inputCls} placeholder="Notice title" /></Field>
          </div>
          <Field label="বিবরণ (Bangla)"><textarea rows={3} className={inputCls} /></Field>
          <Field label="Content (English)"><textarea rows={3} className={inputCls} /></Field>
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Publish Date"><input type="date" className={inputCls} /></Field>
            <Field label="PDF Attachment (optional)">
              <input type="file" className="block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-primary" />
            </Field>
          </div>
        </div>
      </Modal>
    </div>
  );
}

function ProjectsSection() {
  const [openForm, setOpenForm] = useState(false);
  return (
    <div>
      <SectionHeader
        title="Projects"
        desc="চলমান ও সম্পন্ন সকল প্রকল্প"
        action={
          <PrimaryButton onClick={() => setOpenForm(true)}>
            <IconPlus /> নতুন প্রজেক্ট
          </PrimaryButton>
        }
      />
      <Table headers={["Cover", "Title", "Status", "Start", "End", "Actions"]}>
        {PROJECTS.map((p, i) => (
          <tr key={p.title} className={i % 2 ? "bg-slate-50/50" : ""}>
            <td className="px-4 py-3">
              <div className="h-10 w-14 rounded-md bg-gradient-to-br from-primary to-secondary" />
            </td>
            <td className="px-4 py-3 font-serif-bn font-semibold text-slate-900">{p.title}</td>
            <td className="px-4 py-3">
              <Badge tone={statusTone(p.status)}>{p.status === "ongoing" ? "চলমান" : "সম্পন্ন"}</Badge>
            </td>
            <td className="px-4 py-3 text-sm text-slate-600">{p.start}</td>
            <td className="px-4 py-3 text-sm text-slate-600">{p.end}</td>
            <td className="px-4 py-3">
              <div className="flex gap-1.5">
                <IconBtn><IconEdit /></IconBtn>
                <IconBtn tone="danger"><IconTrash /></IconBtn>
              </div>
            </td>
          </tr>
        ))}
      </Table>

      <Modal
        open={openForm}
        onClose={() => setOpenForm(false)}
        title="নতুন প্রজেক্ট যোগ করুন"
        footer={
          <>
            <GhostButton onClick={() => setOpenForm(false)}>Cancel</GhostButton>
            <PrimaryButton onClick={() => setOpenForm(false)}>Save Project</PrimaryButton>
          </>
        }
      >
        <div className="space-y-4">
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="শিরোনাম (BN)"><input className={inputCls} /></Field>
            <Field label="Title (EN)"><input className={inputCls} /></Field>
          </div>
          <Field label="বিবরণ (BN)"><textarea rows={2} className={inputCls} /></Field>
          <Field label="Description (EN)"><textarea rows={2} className={inputCls} /></Field>
          <div className="grid gap-4 sm:grid-cols-3">
            <Field label="Status">
              <select className={inputCls}>
                <option>ongoing</option>
                <option>completed</option>
              </select>
            </Field>
            <Field label="Start Date"><input type="date" className={inputCls} /></Field>
            <Field label="End Date"><input type="date" className={inputCls} /></Field>
          </div>
          <Field label="Cover Image">
            <input type="file" className="block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-primary" />
          </Field>
        </div>
      </Modal>
    </div>
  );
}

function GallerySection() {
  const [openForm, setOpenForm] = useState(false);
  return (
    <div>
      <SectionHeader
        title="Gallery"
        desc="ছবি ও ক্যাপশন ব্যবস্থাপনা"
        action={
          <PrimaryButton onClick={() => setOpenForm(true)}>
            <IconPlus /> ছবি আপলোড করুন
          </PrimaryButton>
        }
      />
      <div className="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        {GALLERY.map((g) => (
          <div key={g.cap} className="group relative aspect-square overflow-hidden rounded-xl border border-slate-200 shadow-sm">
            <div className={`h-full w-full bg-gradient-to-br ${g.hue}`} />
            <div className="absolute inset-0 flex items-end bg-gradient-to-t from-black/70 via-black/20 to-transparent p-3 opacity-0 transition group-hover:opacity-100">
              <div className="flex w-full items-end justify-between gap-2">
                <div className="min-w-0 text-sm font-semibold text-white">{g.cap}</div>
                <div className="flex gap-1.5">
                  <button className="grid h-8 w-8 place-items-center rounded-md bg-white/90 text-slate-700 hover:bg-white"><IconEdit /></button>
                  <button className="grid h-8 w-8 place-items-center rounded-md bg-white/90 text-rose-600 hover:bg-white"><IconTrash /></button>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
      <Modal
        open={openForm}
        onClose={() => setOpenForm(false)}
        title="ছবি আপলোড করুন"
        footer={
          <>
            <GhostButton onClick={() => setOpenForm(false)}>Cancel</GhostButton>
            <PrimaryButton onClick={() => setOpenForm(false)}>Upload</PrimaryButton>
          </>
        }
      >
        <div className="space-y-4">
          <Field label="Image">
            <input type="file" className="block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-primary" />
          </Field>
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="ক্যাপশন (BN)"><input className={inputCls} /></Field>
            <Field label="Caption (EN)"><input className={inputCls} /></Field>
          </div>
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Linked Project">
              <select className={inputCls}>
                <option>— None —</option>
                {PROJECTS.map((p) => <option key={p.title}>{p.title}</option>)}
              </select>
            </Field>
            <Field label="Event Date"><input type="date" className={inputCls} /></Field>
          </div>
        </div>
      </Modal>
    </div>
  );
}

function FilterTabs<T extends string>({ tabs, active, onChange }: { tabs: { key: T; label: string; count?: number }[]; active: T; onChange: (k: T) => void }) {
  return (
    <div className="mb-4 inline-flex rounded-lg border border-slate-200 bg-white p-1 shadow-sm">
      {tabs.map((t) => (
        <button
          key={t.key}
          onClick={() => onChange(t.key)}
          className={`inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-semibold transition ${
            active === t.key ? "bg-primary text-primary-foreground" : "text-slate-600 hover:bg-slate-50"
          }`}
        >
          {t.label}
          {typeof t.count === "number" && (
            <span className={`rounded-full px-1.5 py-0.5 text-[10px] font-bold ${active === t.key ? "bg-white/25 text-white" : "bg-slate-100 text-slate-600"}`}>
              {t.count}
            </span>
          )}
        </button>
      ))}
    </div>
  );
}

function MessagesSection() {
  const [messages, setMessages] = useState(MESSAGES);
  const [selected, setSelected] = useState<null | (typeof MESSAGES)[number]>(null);
  const [tab, setTab] = useState<"all" | "unread" | "read">("all");

  const unreadCount = messages.filter((m) => !m.read).length;
  const filtered = messages.filter((m) => tab === "all" ? true : tab === "unread" ? !m.read : m.read);

  const toggleRead = (name: string, read: boolean) => {
    setMessages((prev) => prev.map((m) => (m.name === name ? { ...m, read } : m)));
    setSelected((s) => (s && s.name === name ? { ...s, read } : s));
  };
  const remove = (name: string) => {
    setMessages((prev) => prev.filter((m) => m.name !== name));
    setSelected(null);
  };

  return (
    <div>
      <SectionHeader title="Contact Messages" desc="প্রাপ্ত সকল বার্তা" />
      <FilterTabs
        active={tab}
        onChange={setTab}
        tabs={[
          { key: "all", label: "সব", count: messages.length },
          { key: "unread", label: "না পড়া", count: unreadCount },
          { key: "read", label: "পড়া", count: messages.length - unreadCount },
        ]}
      />

      {filtered.length === 0 ? (
        <EmptyState message="কোনো মেসেজ নেই" />
      ) : (
        <Table headers={["নাম", "বিষয়", "ফোন", "তারিখ", "স্ট্যাটাস", "Actions"]}>
          {filtered.map((m, i) => (
            <tr
              key={m.name}
              className={`cursor-pointer transition ${!m.read ? "bg-primary-soft/30" : i % 2 ? "bg-slate-50/50" : ""} hover:bg-primary-soft/40`}
              onClick={() => { setSelected(m); if (!m.read) toggleRead(m.name, true); }}
            >
              <td className={`px-4 py-3 text-slate-900 ${!m.read ? "font-bold" : "font-semibold"}`}>{m.name}</td>
              <td className={`px-4 py-3 text-sm text-slate-700 ${!m.read ? "font-semibold" : ""}`}>{m.subject}</td>
              <td className="px-4 py-3 text-sm text-slate-600">{m.phone}</td>
              <td className="px-4 py-3 text-sm text-slate-600">{m.date}</td>
              <td className="px-4 py-3">
                {m.read ? (
                  <Badge tone="muted">পড়া</Badge>
                ) : (
                  <span className="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-bold text-rose-700 ring-1 ring-rose-200">
                    <span className="h-1.5 w-1.5 animate-pulse rounded-full bg-rose-500" />
                    না পড়া
                  </span>
                )}
              </td>
              <td className="px-4 py-3" onClick={(e) => e.stopPropagation()}>
                <div className="flex gap-1.5">
                  <IconBtn><span onClick={() => setSelected(m)}><IconEye /></span></IconBtn>
                  <IconBtn><span onClick={() => toggleRead(m.name, !m.read)}><IconCheck /></span></IconBtn>
                  <IconBtn tone="danger"><span onClick={() => remove(m.name)}><IconTrash /></span></IconBtn>
                </div>
              </td>
            </tr>
          ))}
        </Table>
      )}

      <Modal
        open={!!selected}
        onClose={() => setSelected(null)}
        title={selected?.subject ?? ""}
        footer={
          selected && (
            <>
              <GhostButton onClick={() => setSelected(null)}>Close</GhostButton>
              <GhostButton onClick={() => toggleRead(selected.name, !selected.read)}>
                {selected.read ? "Mark as unread" : "Mark as read"}
              </GhostButton>
              <button
                onClick={() => remove(selected.name)}
                className="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-rose-700"
              >
                Delete
              </button>
            </>
          )
        }
      >
        {selected && (
          <div className="space-y-4">
            <div className="flex items-center gap-3">
              <div className="grid h-12 w-12 place-items-center rounded-full bg-primary text-primary-foreground font-bold">
                {selected.name.charAt(0)}
              </div>
              <div className="min-w-0">
                <div className="font-semibold text-slate-900">{selected.name}</div>
                <div className="truncate text-xs text-slate-500">{selected.email} · {selected.phone}</div>
              </div>
              {!selected.read && <Badge tone="danger">Unread</Badge>}
            </div>
            <div className="grid grid-cols-2 gap-3 rounded-lg bg-slate-50 p-3 text-xs">
              <div><span className="text-slate-500">Submitted:</span> <span className="font-semibold text-slate-800">{selected.date}</span></div>
              <div><span className="text-slate-500">IP:</span> <span className="font-mono text-slate-800">{selected.ip}</span></div>
            </div>
            <div>
              <div className="mb-1.5 text-xs font-semibold text-slate-600">Message</div>
              <p className="rounded-lg border border-slate-200 bg-white p-4 text-sm leading-relaxed text-slate-700">
                {selected.body}
              </p>
            </div>
          </div>
        )}
      </Modal>
    </div>
  );
}

function DonationsSection() {
  const [donations, setDonations] = useState(DONATIONS);
  const [selected, setSelected] = useState<null | (typeof DONATIONS)[number]>(null);
  const [tab, setTab] = useState<"all" | "pending" | "verified" | "rejected">("all");

  const totalPending = donations.filter((d) => d.status === "pending").length;
  const totalVerified = donations.filter((d) => d.status === "verified").reduce((s, d) => s + d.amtRaw, 0);
  const filtered = donations.filter((d) => tab === "all" ? true : d.status === tab);

  const setStatus = (txn: string, status: string) => {
    setDonations((prev) => prev.map((d) => (d.txn === txn ? { ...d, status } : d)));
    setSelected((s) => (s && s.txn === txn ? { ...s, status } : s));
  };
  const remove = (txn: string) => {
    setDonations((prev) => prev.filter((d) => d.txn !== txn));
    setSelected(null);
  };

  const methodColor = (m: string) =>
    m === "bKash" ? "bg-pink-100 text-pink-700" :
    m === "Nagad" ? "bg-orange-100 text-orange-700" :
    m === "Rocket" ? "bg-violet-100 text-violet-700" : "bg-blue-100 text-blue-700";

  const statusBadge = (s: string) =>
    s === "verified" ? <Badge tone="success">Verified</Badge> :
    s === "pending" ? <Badge tone="warning">Pending</Badge> :
    <Badge tone="danger">Rejected</Badge>;

  return (
    <div>
      <SectionHeader title="Donation Interests" desc="জমা হওয়া দানের আগ্রহপত্রসমূহ" />

      {/* Stat strip */}
      <div className="mb-5 grid gap-3 sm:grid-cols-3">
        <div className="rounded-xl border border-amber-200 bg-amber-50 p-4">
          <div className="text-xs font-medium text-amber-700">Pending</div>
          <div className="mt-1 font-serif-bn text-2xl font-bold text-amber-900">{totalPending}</div>
        </div>
        <div className="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
          <div className="text-xs font-medium text-emerald-700">Verified Total</div>
          <div className="mt-1 font-serif-bn text-2xl font-bold text-emerald-900">৳ {totalVerified.toLocaleString("bn-BD")}</div>
        </div>
        <div className="rounded-xl border border-slate-200 bg-white p-4">
          <div className="text-xs font-medium text-slate-500">Total Entries</div>
          <div className="mt-1 font-serif-bn text-2xl font-bold text-slate-900">{donations.length}</div>
        </div>
      </div>

      <FilterTabs
        active={tab}
        onChange={setTab}
        tabs={[
          { key: "all", label: "সব", count: donations.length },
          { key: "pending", label: "Pending", count: totalPending },
          { key: "verified", label: "Verified", count: donations.filter((d) => d.status === "verified").length },
          { key: "rejected", label: "Rejected", count: donations.filter((d) => d.status === "rejected").length },
        ]}
      />

      {filtered.length === 0 ? (
        <EmptyState message="কোনো ডোনেশন ইন্টারেস্ট নেই" />
      ) : (
        <Table headers={["দাতার নাম", "পরিমাণ", "মেথড", "ট্রানজেকশন", "তারিখ", "স্ট্যাটাস", "Actions"]}>
          {filtered.map((d, i) => (
            <tr
              key={d.txn}
              className={`cursor-pointer ${i % 2 ? "bg-slate-50/50" : ""} hover:bg-primary-soft/40`}
              onClick={() => setSelected(d)}
            >
              <td className="px-4 py-3 font-semibold text-slate-900">{d.donor}</td>
              <td className="px-4 py-3 font-serif-bn font-bold text-primary">{d.amt}</td>
              <td className="px-4 py-3">
                <span className={`inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-xs font-semibold ${methodColor(d.method)}`}>
                  <span className="h-1.5 w-1.5 rounded-full bg-current" />
                  {d.method}
                </span>
              </td>
              <td className="px-4 py-3 font-mono text-xs text-slate-700">{d.txn}</td>
              <td className="px-4 py-3 text-sm text-slate-600">{d.date}</td>
              <td className="px-4 py-3">{statusBadge(d.status)}</td>
              <td className="px-4 py-3" onClick={(e) => e.stopPropagation()}>
                <div className="flex gap-1.5">
                  <button title="Verify" onClick={() => setStatus(d.txn, "verified")} className="grid h-8 w-8 place-items-center rounded-md border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100"><IconCheck /></button>
                  <button title="Pending" onClick={() => setStatus(d.txn, "pending")} className="grid h-8 w-8 place-items-center rounded-md border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100"><IconClock /></button>
                  <button title="Reject" onClick={() => setStatus(d.txn, "rejected")} className="grid h-8 w-8 place-items-center rounded-md border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100"><IconXMark /></button>
                  <IconBtn tone="danger"><span onClick={() => remove(d.txn)}><IconTrash /></span></IconBtn>
                </div>
              </td>
            </tr>
          ))}
        </Table>
      )}

      <Modal
        open={!!selected}
        onClose={() => setSelected(null)}
        title={selected ? `${selected.donor} · ${selected.amt}` : ""}
        footer={
          selected && (
            <>
              <GhostButton onClick={() => setSelected(null)}>Close</GhostButton>
              <button onClick={() => setStatus(selected.txn, "rejected")} className="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100">Reject</button>
              <button onClick={() => setStatus(selected.txn, "pending")} className="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3.5 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100">Pending</button>
              <PrimaryButton onClick={() => setStatus(selected.txn, "verified")}>Approve</PrimaryButton>
            </>
          )
        }
      >
        {selected && (
          <div className="space-y-4">
            <div className="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div>
                <div className="text-xs text-slate-500">Amount</div>
                <div className="font-serif-bn text-2xl font-bold text-primary">{selected.amt}</div>
              </div>
              {statusBadge(selected.status)}
            </div>
            <div className="grid grid-cols-2 gap-3 text-sm">
              <div><div className="text-xs text-slate-500">Donor</div><div className="font-semibold text-slate-900">{selected.donor}</div></div>
              <div><div className="text-xs text-slate-500">Method</div><div className="font-semibold text-slate-900">{selected.method}</div></div>
              <div><div className="text-xs text-slate-500">Email</div><div className="text-slate-800">{selected.email}</div></div>
              <div><div className="text-xs text-slate-500">Phone</div><div className="text-slate-800">{selected.phone}</div></div>
              <div><div className="text-xs text-slate-500">Transaction ID</div><div className="font-mono text-slate-800">{selected.txn}</div></div>
              <div><div className="text-xs text-slate-500">Submitted</div><div className="text-slate-800">{selected.date}</div></div>
              <div className="col-span-2"><div className="text-xs text-slate-500">IP Address</div><div className="font-mono text-xs text-slate-600">{selected.ip}</div></div>
            </div>
          </div>
        )}
      </Modal>
    </div>
  );
}

function SettingsSection() {
  const [cur, setCur] = useState("");
  const [nw, setNw] = useState("");
  const [cf, setCf] = useState("");
  const [showCur, setShowCur] = useState(false);
  const [showNw, setShowNw] = useState(false);
  const [showCf, setShowCf] = useState(false);
  const [saved, setSaved] = useState(false);

  const score = (() => {
    let s = 0;
    if (nw.length >= 8) s++;
    if (nw.length >= 12) s++;
    if (/[0-9]/.test(nw)) s++;
    if (/[^A-Za-z0-9]/.test(nw)) s++;
    return Math.min(s, 3);
  })();
  const strengthLabel = ["দুর্বল", "দুর্বল", "মাঝারি", "শক্তিশালী"][score];
  const strengthColor = ["bg-rose-500", "bg-rose-500", "bg-amber-500", "bg-emerald-500"][score];
  const mismatch = cf.length > 0 && cf !== nw;

  const pwField = (label: string, val: string, set: (v: string) => void, show: boolean, toggle: () => void, err?: string) => (
    <Field label={label}>
      <div className="relative">
        <input
          type={show ? "text" : "password"}
          value={val}
          onChange={(e) => set(e.target.value)}
          className={`${inputCls} pr-10 ${err ? "border-rose-300 focus:border-rose-500 focus:ring-rose-200" : ""}`}
          placeholder="••••••••"
        />
        <button
          type="button"
          onClick={toggle}
          className="absolute right-2 top-1/2 grid h-7 w-7 -translate-y-1/2 place-items-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-700"
          aria-label="Toggle visibility"
        >
          <IconEye off={show} />
        </button>
      </div>
      {err && <div className="mt-1 text-xs text-rose-600">{err}</div>}
    </Field>
  );

  return (
    <div>
      <SectionHeader title="Admin Settings" desc="প্রোফাইল ও পাসওয়ার্ড ব্যবস্থাপনা" />

      <div className="mx-auto grid max-w-[520px] gap-5">
        {/* Profile card */}
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
          <div className="flex items-center gap-4">
            <div className="grid h-14 w-14 place-items-center rounded-full bg-primary text-lg font-bold text-primary-foreground">
              {ADMIN_PROFILE.fullName.charAt(0)}
            </div>
            <div className="min-w-0">
              <div className="font-serif-bn text-lg font-bold text-slate-900">{ADMIN_PROFILE.fullName}</div>
              <div className="truncate text-xs text-slate-500">@{ADMIN_PROFILE.username} · {ADMIN_PROFILE.email}</div>
            </div>
          </div>
          <div className="mt-4 flex items-center gap-2 rounded-lg bg-slate-50 p-3 text-xs text-slate-600">
            <IconClock />
            শেষ লগইন: <span className="font-semibold text-slate-800">{ADMIN_PROFILE.lastLogin}</span>
          </div>
        </div>

        {/* Password card */}
        <form
          onSubmit={(e) => { e.preventDefault(); setSaved(true); setTimeout(() => setSaved(false), 2500); setCur(""); setNw(""); setCf(""); }}
          className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
        >
          <div className="mb-4">
            <div className="font-serif-bn text-lg font-bold text-slate-900">পাসওয়ার্ড পরিবর্তন</div>
            <div className="text-xs text-slate-500">আপনার অ্যাকাউন্ট সুরক্ষিত রাখুন</div>
          </div>

          <div className="space-y-4">
            {pwField("বর্তমান পাসওয়ার্ড", cur, setCur, showCur, () => setShowCur((v) => !v))}

            <div>
              {pwField("নতুন পাসওয়ার্ড", nw, setNw, showNw, () => setShowNw((v) => !v))}
              {nw.length > 0 && (
                <div className="mt-2">
                  <div className="flex h-1.5 gap-1">
                    {[0, 1, 2].map((i) => (
                      <div key={i} className={`h-full flex-1 rounded-full ${i <= score - 1 ? strengthColor : "bg-slate-200"}`} />
                    ))}
                  </div>
                  <div className="mt-1 flex items-center justify-between text-[11px]">
                    <span className="text-slate-500">পাসওয়ার্ডের শক্তি</span>
                    <span className={`font-semibold ${score >= 3 ? "text-emerald-700" : score >= 2 ? "text-amber-700" : "text-rose-700"}`}>{strengthLabel}</span>
                  </div>
                </div>
              )}
              <p className="mt-2 text-[11px] text-slate-500">কমপক্ষে ১২ ক্যারেক্টার, সংখ্যা ও বিশেষ চিহ্ন ব্যবহার করুন।</p>
            </div>

            {pwField("নতুন পাসওয়ার্ড নিশ্চিত করুন", cf, setCf, showCf, () => setShowCf((v) => !v), mismatch ? "পাসওয়ার্ড মিলছে না" : undefined)}
          </div>

          {saved && (
            <div className="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200">
              <IconCheck /> পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে
            </div>
          )}

          <div className="mt-6 flex justify-end">
            <PrimaryButton>পাসওয়ার্ড পরিবর্তন করুন</PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  );
}

/* --------------------- Shell --------------------- */

function AdminApp() {
  const [active, setActive] = useState<SectionKey>("dashboard");
  const [sideOpen, setSideOpen] = useState(false);

  const titleFor: Record<SectionKey, string> = {
    dashboard: "Dashboard",
    notices: "Notices",
    projects: "Projects",
    gallery: "Gallery",
    messages: "Contact Messages",
    donations: "Donation Interests",
    settings: "Admin Settings",
  };

  return (
    <div className="min-h-screen bg-slate-100 font-sans-bn text-slate-800">
      {/* Sidebar */}
      <aside
        className={`fixed inset-y-0 left-0 z-40 w-64 transform border-r border-slate-200 bg-white transition-transform lg:translate-x-0 ${
          sideOpen ? "translate-x-0" : "-translate-x-full"
        }`}
      >
        <div className="flex h-16 items-center gap-3 border-b border-slate-200 px-5">
          <div className="grid h-9 w-9 place-items-center rounded-lg bg-primary text-primary-foreground">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
              <path d="M12 3l3 6 6 .9-4.5 4.3 1.1 6.3L12 17.8 6.4 20.5l1.1-6.3L3 9.9 9 9z" strokeLinejoin="round" />
            </svg>
          </div>
          <div className="min-w-0">
            <div className="font-serif-bn text-sm font-bold text-slate-900">CDS Admin</div>
            <div className="truncate text-[11px] text-slate-500">Control Panel</div>
          </div>
        </div>
        <nav className="p-3">
          {NAV.map((item) => {
            const isLogout = item.key === "logout";
            const isActive = !isLogout && active === (item.key as SectionKey);
            return (
              <button
                key={item.label}
                onClick={() => {
                  if (isLogout) return;
                  setActive(item.key as SectionKey);
                  setSideOpen(false);
                }}
                className={`mb-1 flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition ${
                  isActive
                    ? "bg-primary text-primary-foreground shadow-sm"
                    : isLogout
                      ? "mt-4 border-t border-slate-200 pt-4 text-rose-600 hover:bg-rose-50"
                      : "text-slate-700 hover:bg-slate-100"
                }`}
              >
                <span className={isActive ? "text-primary-foreground" : ""}>{item.icon}</span>
                {item.label}
              </button>
            );
          })}
        </nav>
        <div className="absolute inset-x-3 bottom-3 rounded-xl bg-gradient-to-br from-primary to-secondary p-4 text-white">
          <div className="font-serif-bn text-sm font-bold">Need help?</div>
          <p className="mt-1 text-xs text-white/85">ডকুমেন্টেশন ও সাপোর্ট রিসোর্স দেখুন।</p>
          <button className="mt-3 rounded-md bg-white/15 px-3 py-1.5 text-xs font-semibold backdrop-blur hover:bg-white/25">
            Read docs
          </button>
        </div>
      </aside>

      {sideOpen && (
        <div className="fixed inset-0 z-30 bg-slate-900/40 lg:hidden" onClick={() => setSideOpen(false)} />
      )}

      {/* Main */}
      <div className="lg:pl-64">
        {/* Top bar */}
        <header className="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6">
          <button
            onClick={() => setSideOpen((v) => !v)}
            className="grid h-9 w-9 place-items-center rounded-md border border-slate-200 text-slate-700 lg:hidden"
            aria-label="Toggle menu"
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="h-4 w-4">
              <path d="M4 7h16M4 12h16M4 17h16" />
            </svg>
          </button>
          <div className="min-w-0 flex-1">
            <div className="truncate font-serif-bn text-lg font-bold text-slate-900">{titleFor[active]}</div>
            <div className="hidden text-xs text-slate-500 sm:block">Admin · CDS Control Panel</div>
          </div>
          <div className="hidden items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 md:flex">
            <IconSearch />
            <input placeholder="Search..." className="w-48 bg-transparent text-sm outline-none placeholder:text-slate-400" />
          </div>
          <button className="relative grid h-9 w-9 place-items-center rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="h-4 w-4">
              <path d="M6 8a6 6 0 1112 0v5l2 3H4l2-3V8z M10 19a2 2 0 004 0" />
            </svg>
            <span className="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-rose-500" />
          </button>
          <div className="flex items-center gap-2 rounded-lg border border-slate-200 px-2 py-1">
            <div className="grid h-7 w-7 place-items-center rounded-full bg-primary text-xs font-bold text-primary-foreground">A</div>
            <div className="hidden text-xs sm:block">
              <div className="font-semibold text-slate-900">Admin</div>
              <div className="text-slate-500">admin@cds-bd.org</div>
            </div>
          </div>
          <Link to="/" className="grid h-9 w-9 place-items-center rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50" title="Back to site">
            <IconLogout />
          </Link>
        </header>

        <main className="p-4 sm:p-6 lg:p-8">
          {active === "dashboard" && <Dashboard />}
          {active === "notices" && <NoticesSection />}
          {active === "projects" && <ProjectsSection />}
          {active === "gallery" && <GallerySection />}
          {active === "messages" && <MessagesSection />}
          {active === "donations" && <DonationsSection />}
          {active === "settings" && <SettingsSection />}
          {false && <EmptyState message="কোনো ডাটা নেই" />}
        </main>
      </div>
    </div>
  );
}
