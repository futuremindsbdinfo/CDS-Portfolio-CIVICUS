import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { PageBanner, PageShell } from "../components/site-chrome";

export const Route = createFileRoute("/donate")({
  head: () => ({
    meta: [
      { title: "ডোনেশন — Citizen Development Society (CDS)" },
      {
        name: "description",
        content:
          "CDS-এর কার্যক্রমে অবদান রাখুন — bKash, Nagad, Rocket ও ব্যাংক ট্রান্সফারের মাধ্যমে।",
      },
      { property: "og:title", content: "ডোনেশন — CDS" },
      {
        property: "og:description",
        content: "একটি ছোট অনুদান, বদলে দিতে পারে একটি জীবন।",
      },
    ],
  }),
  component: DonatePage,
});

const METHODS = [
  {
    name: "bKash",
    type: "Merchant / Personal",
    number: "০১৭০০-১১১১১১",
    ref: "Ref: CDS-Donation",
    accent: "linear-gradient(135deg,#e2136e,#a30f57)",
    instructions: "পার্সোনাল হিসেবে সেন্ড মানি করুন এবং রেফারেন্সে 'CDS' লিখুন।",
  },
  {
    name: "Nagad",
    type: "Personal",
    number: "০১৭০০-২২২২২২",
    ref: "Ref: CDS-Donation",
    accent: "linear-gradient(135deg,#f26522,#c94a12)",
    instructions: "Nagad অ্যাপ থেকে সেন্ড মানি করে ট্রানজেকশন আইডি সংরক্ষণ করুন।",
  },
  {
    name: "Rocket",
    type: "Personal",
    number: "০১৭০০-৩৩৩৩৩৩-৫",
    ref: "Ref: CDS-Donation",
    accent: "linear-gradient(135deg,#8b1c8b,#5a1560)",
    instructions: "Rocket থেকে সেন্ড মানি — সেবা কোড ব্যবহার করুন।",
  },
  {
    name: "ব্যাংক ট্রান্সফার",
    type: "Islami Bank BD",
    number: "A/C: 2050 3417 5698 001",
    ref: "শাখা: ধানমন্ডি",
    accent: "linear-gradient(135deg,#1e3a8a,#3A7D5C)",
    instructions: "যেকোনো ব্যাংক থেকে সরাসরি ট্রান্সফার করা যাবে। রশিদ সংরক্ষণ করুন।",
  },
];

const PILLARS = [
  {
    bn: "সুশিক্ষা",
    color: "text-emerald-700",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <path d="M3 8l9-4 9 4-9 4-9-4z" strokeLinejoin="round" />
        <path d="M7 10v5c0 1.5 2.5 3 5 3s5-1.5 5-3v-5" strokeLinecap="round" />
      </svg>
    ),
  },
  {
    bn: "সুস্বাস্থ্য",
    color: "text-rose-700",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" strokeLinejoin="round" />
      </svg>
    ),
  },
  {
    bn: "সুনাগরিক",
    color: "text-blue-700",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <circle cx="12" cy="8" r="3.2" />
        <path d="M5 20c1.5-3.5 4.2-5 7-5s5.5 1.5 7 5" strokeLinecap="round" />
      </svg>
    ),
  },
  {
    bn: "সুশাসন",
    color: "text-amber-700",
    icon: (
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="h-7 w-7">
        <path d="M4 21h16M6 21V10M18 21V10M4 10l8-6 8 6" strokeLinejoin="round" />
        <path d="M10 21v-6h4v6" />
      </svg>
    ),
  },
];

const FAQS = [
  {
    q: "আমার দেওয়া টাকা কীভাবে ব্যবহার হয়?",
    a: "প্রতিটি অনুদান সরাসরি প্রকল্প বাস্তবায়ন (৭৫%), উপকারভোগী সহায়তা (১৫%) ও পরিচালন ব্যয়ে (১০%) ব্যবহৃত হয়। বার্ষিক নিরীক্ষিত প্রতিবেদন প্রকাশ্য।",
  },
  {
    q: "অনুদানের রশিদ পাওয়া যাবে কি?",
    a: "হ্যাঁ। ফর্ম জমা দেওয়ার পর ৭২ ঘণ্টার মধ্যে ইমেইলে ডিজিটাল রশিদ পাঠানো হয়; হার্ড কপি প্রয়োজন হলে অফিস থেকে সংগ্রহ করা যাবে।",
  },
  {
    q: "নির্দিষ্ট প্রকল্পে অনুদান দেওয়া যাবে কি?",
    a: "হ্যাঁ, ফর্মে মন্তব্যের ঘরে প্রকল্পের নাম উল্লেখ করলে আপনার অনুদান শুধুমাত্র সেই প্রকল্পে ব্যবহৃত হবে।",
  },
];

function CopyableRow({ label, value }: { label: string; value: string }) {
  const [copied, setCopied] = useState(false);
  return (
    <div className="flex items-center justify-between gap-3 rounded-xl bg-white/15 px-3 py-2 text-white backdrop-blur">
      <div className="min-w-0">
        <div className="text-[10px] font-semibold uppercase tracking-widest text-white/70">{label}</div>
        <div className="truncate font-mono text-sm font-bold">{value}</div>
      </div>
      <button
        onClick={() => {
          navigator.clipboard?.writeText(value);
          setCopied(true);
          setTimeout(() => setCopied(false), 1200);
        }}
        className="shrink-0 rounded-full bg-white/20 px-3 py-1 text-[10px] font-semibold hover:bg-white/30"
      >
        {copied ? "কপি হয়েছে" : "কপি"}
      </button>
    </div>
  );
}

type FormState = { name: string; phone: string; email: string; amount: string; method: string; txn: string };

function DonatePage() {
  const [form, setForm] = useState<FormState>({
    name: "",
    phone: "",
    email: "",
    amount: "",
    method: "",
    txn: "",
  });
  const [touched, setTouched] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const [openFaq, setOpenFaq] = useState<number | null>(0);

  const errors = {
    name: !form.name ? "পূর্ণ নাম আবশ্যক" : "",
    phone: !/^0?1[3-9]\d{8,9}$/.test(form.phone.replace(/[-\s]/g, "")) ? "সঠিক মোবাইল নম্বর দিন" : "",
    amount: !form.amount || Number(form.amount) < 100 ? "ন্যূনতম ১০০ টাকা" : "",
    method: !form.method ? "পেমেন্ট মেথড নির্বাচন করুন" : "",
    txn: !form.txn ? "ট্রানজেকশন আইডি আবশ্যক" : "",
  };
  const hasErrors = Object.values(errors).some(Boolean);

  return (
    <PageShell>
      <PageBanner
        title="ডোনেশন"
        subtitle="আপনার প্রতিটি অবদান একটি শিশুর হাতে বই, একটি মায়ের হাতে সেবা তুলে দেয়।"
        crumbs={[{ label: "হোম", to: "/" }, { label: "ডোনেশন" }]}
      />

      {/* Hero band */}
      <section className="mx-auto max-w-7xl px-4 pt-10 sm:px-6 lg:px-8">
        <div className="relative overflow-hidden rounded-3xl border border-primary/20 bg-surface p-8 shadow-card sm:p-12">
          <div className="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-primary-soft" />
          <div className="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-secondary/10" />
          <div className="relative grid gap-10 lg:grid-cols-[1.4fr_1fr] lg:items-center">
            <div>
              <span className="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary-soft px-3 py-1 text-xs font-semibold text-primary">
                <span className="h-1.5 w-1.5 rounded-full bg-primary" />
                আপনি পাশে থাকলে সম্ভব
              </span>
              <h2 className="mt-4 font-serif-bn text-3xl font-bold leading-tight sm:text-4xl">
                একটি ছোট অনুদান,{" "}
                <span className="bg-gradient-to-br from-primary to-secondary bg-clip-text text-transparent">
                  বদলে দিতে পারে একটি জীবন
                </span>
              </h2>
              <p className="mt-4 max-w-xl text-base leading-relaxed text-muted-foreground">
                আপনার অনুদানে চলে গ্রামীণ পাঠাগার, মাতৃস্বাস্থ্য ক্যাম্প, তরুণদের প্রশিক্ষণ ও
                শীতবস্ত্র বিতরণের মতো কর্মসূচি। প্রতিটি টাকা কোথায় গেল — আমরা প্রতি বছর
                নিরীক্ষিত প্রতিবেদনে জানাই।
              </p>
              <div className="mt-6 flex flex-wrap gap-2 text-xs font-semibold">
                <span className="rounded-full border border-border bg-surface px-3 py-1.5 text-foreground/80">
                  ৭৫% প্রকল্পে
                </span>
                <span className="rounded-full border border-border bg-surface px-3 py-1.5 text-foreground/80">
                  ১৫% সরাসরি উপকারভোগীতে
                </span>
                <span className="rounded-full border border-border bg-surface px-3 py-1.5 text-foreground/80">
                  বার্ষিক নিরীক্ষিত
                </span>
              </div>
            </div>
            <div className="relative hidden aspect-square lg:block">
              <svg viewBox="0 0 400 400" className="absolute inset-0 h-full w-full">
                <defs>
                  <linearGradient id="dh" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stopColor="#3A7D5C" />
                    <stop offset="100%" stopColor="#1e3a8a" />
                  </linearGradient>
                </defs>
                <circle cx="200" cy="200" r="150" fill="url(#dh)" opacity="0.95" />
                <path
                  d="M200 300s-90-55-90-125a45 45 0 0190-30 45 45 0 0190 30c0 70-90 125-90 125z"
                  fill="#FAF8F3"
                  opacity="0.95"
                />
                <path
                  d="M200 300s-90-55-90-125a45 45 0 0190-30 45 45 0 0190 30c0 70-90 125-90 125z"
                  fill="none"
                  stroke="#3A7D5C"
                  strokeWidth="4"
                />
                <circle cx="200" cy="200" r="150" fill="none" stroke="#fff" strokeWidth="2" strokeDasharray="4 8" opacity="0.5" />
              </svg>
            </div>
          </div>
        </div>
      </section>

      {/* Payment methods */}
      <section className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="mb-8 text-center">
          <div className="text-xs font-semibold uppercase tracking-widest text-primary">পেমেন্ট মেথড</div>
          <h2 className="mt-2 font-serif-bn text-3xl font-bold sm:text-4xl">
            যেকোনো একটি মাধ্যমে অনুদান পাঠান
          </h2>
        </div>
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {METHODS.map((m) => (
            <div
              key={m.name}
              className="group relative overflow-hidden rounded-2xl border border-border bg-surface shadow-card transition hover:-translate-y-1 hover:shadow-card-hover"
            >
              <div className="relative p-5 text-white" style={{ background: m.accent }}>
                <div className="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10" />
                <div className="absolute -bottom-8 -left-4 h-16 w-16 rounded-full bg-black/10" />
                <div className="relative">
                  <div className="text-[10px] font-semibold uppercase tracking-widest text-white/80">
                    {m.type}
                  </div>
                  <div className="mt-1 font-serif-bn text-xl font-bold">{m.name}</div>
                </div>
                <div className="relative mt-4 space-y-2">
                  <CopyableRow label="নম্বর / A/C" value={m.number} />
                  <div className="text-[11px] font-medium text-white/80">{m.ref}</div>
                </div>
              </div>
              <div className="p-5">
                <p className="text-xs leading-relaxed text-muted-foreground">{m.instructions}</p>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* Trust band */}
      <section className="border-y border-border bg-surface-2/60">
        <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
          <div className="mb-8 text-center">
            <div className="text-xs font-semibold uppercase tracking-widest text-primary">
              স্বচ্ছতা ও ভরসা
            </div>
            <h2 className="mt-2 font-serif-bn text-2xl font-bold sm:text-3xl">
              আপনার অনুদান যেখানে পৌঁছায়
            </h2>
          </div>
          <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {PILLARS.map((p) => (
              <div key={p.bn} className="flex items-center gap-4 rounded-2xl border border-border bg-surface p-5 shadow-card">
                <span className={`grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-primary-soft ${p.color}`}>
                  {p.icon}
                </span>
                <div className="min-w-0">
                  <div className="font-serif-bn text-lg font-bold">{p.bn}</div>
                  <div className="text-xs text-muted-foreground">চার স্তম্ভের একটি</div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Interest form */}
      <section className="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="rounded-3xl border border-border bg-surface p-6 shadow-card sm:p-10">
          <div className="mb-6">
            <div className="text-xs font-semibold uppercase tracking-widest text-primary">
              ডোনেশন-ইন্টারেস্ট ফর্ম
            </div>
            <h2 className="mt-2 font-serif-bn text-2xl font-bold sm:text-3xl">
              অনুদান তথ্য নিবন্ধন করুন
            </h2>
            <p className="mt-2 rounded-xl border border-warning/30 bg-warning/10 p-3 text-xs leading-relaxed text-warning-foreground">
              <strong>নোট:</strong> এই ফর্মটি শুধুমাত্র আপনার অনুদানের তথ্য নিবন্ধন করে —{" "}
              এখানে সরাসরি অনলাইন পেমেন্ট প্রসেস হয় না। উপরের যেকোনো পেমেন্ট মেথডে অনুদান পাঠানোর পর নিচের ট্রানজেকশন আইডি সংযুক্ত করে ফর্মটি পূরণ করুন।
            </p>
          </div>

          {submitted ? (
            <div className="rounded-2xl border border-success/30 bg-success/10 p-6 text-center">
              <span className="mx-auto grid h-12 w-12 place-items-center rounded-full bg-success text-success-foreground">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" className="h-6 w-6">
                  <path d="M5 12l5 5L20 7" strokeLinecap="round" strokeLinejoin="round" />
                </svg>
              </span>
              <h3 className="mt-3 font-serif-bn text-xl font-bold">ধন্যবাদ, {form.name || "বন্ধু"}!</h3>
              <p className="mt-1 text-sm text-muted-foreground">
                আপনার অনুদানের তথ্য সফলভাবে জমা হয়েছে। ৭২ ঘণ্টার মধ্যে ইমেইলে রশিদ পাঠানো হবে।
              </p>
              <button
                onClick={() => {
                  setSubmitted(false);
                  setForm({ name: "", phone: "", email: "", amount: "", method: "", txn: "" });
                  setTouched(false);
                }}
                className="mt-4 inline-flex items-center gap-2 rounded-full border border-border bg-surface px-5 py-2 text-sm font-semibold hover:bg-primary-soft hover:text-primary"
              >
                নতুন ফর্ম
              </button>
            </div>
          ) : (
            <form
              onSubmit={(e) => {
                e.preventDefault();
                setTouched(true);
                if (!hasErrors) setSubmitted(true);
              }}
              className="grid gap-4 sm:grid-cols-2"
            >
              <Field label="পূর্ণ নাম *" error={touched ? errors.name : ""}>
                <input
                  value={form.name}
                  onChange={(e) => setForm({ ...form, name: e.target.value })}
                  placeholder="আব্দুল করিম"
                  className={inputCls(touched && !!errors.name)}
                />
              </Field>
              <Field label="ফোন নম্বর *" error={touched ? errors.phone : ""}>
                <input
                  value={form.phone}
                  onChange={(e) => setForm({ ...form, phone: e.target.value })}
                  placeholder="01700-000000"
                  className={inputCls(touched && !!errors.phone)}
                />
              </Field>
              <Field label="ইমেইল (ঐচ্ছিক)">
                <input
                  type="email"
                  value={form.email}
                  onChange={(e) => setForm({ ...form, email: e.target.value })}
                  placeholder="you@example.com"
                  className={inputCls(false)}
                />
              </Field>
              <Field label="ডোনেশনের পরিমাণ (৳) *" error={touched ? errors.amount : ""}>
                <input
                  type="number"
                  value={form.amount}
                  onChange={(e) => setForm({ ...form, amount: e.target.value })}
                  placeholder="১০০০"
                  className={inputCls(touched && !!errors.amount)}
                />
              </Field>
              <Field label="পেমেন্ট মেথড *" error={touched ? errors.method : ""}>
                <select
                  value={form.method}
                  onChange={(e) => setForm({ ...form, method: e.target.value })}
                  className={inputCls(touched && !!errors.method)}
                >
                  <option value="">নির্বাচন করুন</option>
                  {METHODS.map((m) => (
                    <option key={m.name} value={m.name}>
                      {m.name}
                    </option>
                  ))}
                </select>
              </Field>
              <Field label="ট্রানজেকশন আইডি *" error={touched ? errors.txn : ""}>
                <input
                  value={form.txn}
                  onChange={(e) => setForm({ ...form, txn: e.target.value })}
                  placeholder="TX9A82K1Z"
                  className={inputCls(touched && !!errors.txn)}
                />
              </Field>

              <div className="sm:col-span-2 mt-2 flex flex-wrap items-center justify-between gap-3">
                <p className="text-xs text-muted-foreground">
                  ফর্ম জমা দিয়ে আপনি আমাদের{" "}
                  <a href="#" className="font-semibold text-primary hover:underline">গোপনীয়তা নীতিমালা</a>{" "}
                  মেনে নিচ্ছেন।
                </p>
                <button
                  type="submit"
                  className="inline-flex items-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-card transition hover:brightness-110"
                >
                  <svg viewBox="0 0 24 24" fill="currentColor" className="h-4 w-4">
                    <path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" />
                  </svg>
                  ফর্ম জমা দিন
                </button>
              </div>
            </form>
          )}
        </div>
      </section>

      {/* Donation FAQ */}
      <section className="mx-auto max-w-4xl px-4 pb-20 sm:px-6 lg:px-8">
        <div className="mb-8 text-center">
          <div className="text-xs font-semibold uppercase tracking-widest text-primary">প্রশ্নোত্তর</div>
          <h2 className="mt-2 font-serif-bn text-2xl font-bold sm:text-3xl">
            ডোনেশন সংক্রান্ত সাধারণ প্রশ্ন
          </h2>
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
    </PageShell>
  );
}

function Field({
  label,
  error,
  children,
}: {
  label: string;
  error?: string;
  children: React.ReactNode;
}) {
  return (
    <label className="block">
      <span className="mb-1.5 block text-xs font-semibold text-foreground/80">{label}</span>
      {children}
      {error && (
        <span className="mt-1 flex items-center gap-1 text-[11px] font-medium text-destructive">
          <svg viewBox="0 0 24 24" fill="currentColor" className="h-3 w-3">
            <path d="M12 2l10 18H2z" />
          </svg>
          {error}
        </span>
      )}
    </label>
  );
}

function inputCls(hasError: boolean) {
  return `w-full rounded-xl border bg-background px-4 py-2.5 text-sm outline-none transition placeholder:text-muted-foreground/60 focus:ring-2 focus:ring-primary/30 ${
    hasError
      ? "border-destructive focus:border-destructive"
      : "border-border focus:border-primary"
  }`;
}
