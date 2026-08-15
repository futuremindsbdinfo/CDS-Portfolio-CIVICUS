import re

with open('includes/header.php', 'r', encoding='utf-8') as f:
    content = f.read()

nav_content = """<nav class="hidden items-center gap-0.5 xl:gap-1 lg:flex">
              <a href="index.php" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">হোম</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- Who We Are Mega Menu -->
              <div class="group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">আমাদের সম্পর্কে</span><span data-lang="en" class="hidden">Who We Are</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>

                  <div class="mega-full-panel custom-dropdown">
                      <div class="mx-auto max-w-7xl px-8 py-7 flex">
                          <!-- Col 1 -->
                          <div class="flex-1 pr-8">
                              <div class="mega-col-heading"><span data-lang="bn">আমাদের প্রভাব</span><span data-lang="en" class="hidden">Our Impact Stories</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">জেনারেল কমেন্ট ৩৭</span><span data-lang="en" class="hidden">General Comment 37</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">সহ-সৃষ্টি</span><span data-lang="en" class="hidden">Co-Creation</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">জাম্বিয়া: ১৫+ বছরের অধিকার আন্দোলন</span><span data-lang="en" class="hidden">Zambia: 15+ year campaign</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 2 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">মূল্যবোধ ও জবাবদিহিতা</span><span data-lang="en" class="hidden">Values & Accountability</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">বৈচিত্র্য ও অন্তর্ভুক্তি</span><span data-lang="en" class="hidden">Diversity and Inclusion</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">আমাদের জবাবদিহি করুন</span><span data-lang="en" class="hidden">Hold Us to Account</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 3 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">সংগঠন</span><span data-lang="en" class="hidden">Organization</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">বার্ষিক প্রতিবেদন</span><span data-lang="en" class="hidden">Annual Reports</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between">
                                      <span><span data-lang="bn">বোর্ড</span><span data-lang="en" class="hidden">Board</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                                  <div class="absolute left-full top-0 sub-dropdown z-50" style="min-width:190px">
                                      <div class="sub-panel"><a href="#"><span data-lang="bn">বোর্ড নির্বাচন ২০২৬</span><span data-lang="en" class="hidden">Board Elections 2026</span></a></div>
                                  </div>
                              </div>
                              <a href="#" class="mega-link"><span data-lang="bn">সদস্যবৃন্দ</span><span data-lang="en" class="hidden">Members</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 4 -->
                          <div class="flex-1 pl-8">
                              <div class="mega-col-heading opacity-0">x</div>
                              <a href="#" class="mega-link"><span data-lang="bn">নেটওয়ার্ক</span><span data-lang="en" class="hidden">Networks</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">কর্মকর্তাবৃন্দ</span><span data-lang="en" class="hidden">Staff</span></a>
                              <a href="contact.php" class="mega-link"><span data-lang="bn">যোগাযোগ করুন</span><span data-lang="en" class="hidden">Contact Us</span></a>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- What We Do Mega Menu -->
              <div class="group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">কার্যক্রম সমূহ</span><span data-lang="en" class="hidden">What We Do</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>

                  <div class="mega-full-panel custom-dropdown">
                      <div class="mx-auto max-w-7xl px-8 py-7 flex">
                          <!-- Col 1 -->
                          <div class="flex-1 pr-8">
                              <div class="mega-col-heading"><span data-lang="bn">গবেষণা ও তথ্য</span><span data-lang="en" class="hidden">Co-creating Knowledge</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস মনিটর রেটিং</span><span data-lang="en" class="hidden">CDS Monitor Ratings</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস লেন্স বিশ্লেষণ</span><span data-lang="en" class="hidden">CDS Lens Analysis</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between">
                                      <span><span data-lang="bn">আমাদের প্রতিবেদন</span><span data-lang="en" class="hidden">Our Reports</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                                  <div class="absolute left-full top-0 sub-dropdown z-50" style="min-width:220px">
                                      <div class="sub-panel">
                                          <a href="#"><span data-lang="bn">সিভিল সোসাইটি রিপোর্ট</span><span data-lang="en" class="hidden">State of Civil Society Reports</span></a>
                                          <a href="#"><span data-lang="bn">পিপল পাওয়ার আন্ডার অ্যাটাক</span><span data-lang="en" class="hidden">People Power Under Attack</span></a>
                                          <a href="#"><span data-lang="bn">অন্যান্য প্রকাশনা</span><span data-lang="en" class="hidden">Other Publications</span></a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 2 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">পরিবর্তনের জন্য সংগ্রাম</span><span data-lang="en" class="hidden">Advocating for Change</span></div>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between">
                                      <span><span data-lang="bn">ক্যাম্পেইনসমূহ</span><span data-lang="en" class="hidden">Campaigns</span></span>
                                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg>
                                  </a>
                              </div>
                              <a href="#" class="mega-link"><span data-lang="bn">জাতিসংঘে সিডিএস</span><span data-lang="en" class="hidden">CDS at the UN</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">নাগরিক স্থান প্রকল্প</span><span data-lang="en" class="hidden">Civic Space Project in Central America</span></a>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 3 -->
                          <div class="flex-1 px-8">
                              <div class="mega-col-heading"><span data-lang="bn">সক্ষমতা বৃদ্ধি</span><span data-lang="en" class="hidden">Enabling and Resourcing</span></div>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">লোকাল লিডারশিপ ল্যাবস</span><span data-lang="en" class="hidden">Local Leadership Labs</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                              </div>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">ডিজিটাল ডেমোক্রেসি ইনিশিয়েটিভ</span><span data-lang="en" class="hidden">Digital Democracy Initiative</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                              </div>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস যুব</span><span data-lang="en" class="hidden">CDS Youth</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">CHARM Africa</span><span data-lang="en" class="hidden">CHARM Africa</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">FoPA</span><span data-lang="en" class="hidden">FoPA</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">সম্পন্ন প্রকল্পসমূহ</span><span data-lang="en" class="hidden">Completed Projects</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                                  <div class="absolute left-full top-0 sub-dropdown z-50" style="min-width:240px">
                                      <div class="sub-panel">
                                          <a href="#"><span data-lang="bn">Stand As My Witness</span><span data-lang="en" class="hidden">Stand As My Witness</span></a>
                                          <a href="#"><span data-lang="bn">Donor Challenge</span><span data-lang="en" class="hidden">Donor Challenge</span></a>
                                          <a href="#"><span data-lang="bn">আন্তর্জাতিক সিভিল সোসাইটি সপ্তাহ ২০২৫</span><span data-lang="en" class="hidden">International Civil Society Week 2025</span></a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- divider -->
                          <div class="w-px bg-gray-100 mx-2"></div>
                          <!-- Col 4 -->
                          <div class="flex-1 pl-8">
                              <div class="mega-col-heading"><span data-lang="bn">নেটওয়ার্ক গঠন</span><span data-lang="en" class="hidden">Building Networks</span></div>
                              <a href="#" class="mega-link"><span data-lang="bn">ভুকা! সিভিক অ্যাকশন কোয়ালিশন</span><span data-lang="en" class="hidden">Vuka! Coalition for Civic Action</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">AGNA</span><span data-lang="en" class="hidden">AGNA</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">পরিবর্তনের জন্য উদ্ভাবন</span><span data-lang="en" class="hidden">Innovation for Change</span></a>
                              <a href="#" class="mega-link"><span data-lang="bn">সিডিএস যুব</span><span data-lang="en" class="hidden">CDS Youth</span></a>
                              <div class="relative group-sub">
                                  <a href="#" class="mega-link flex items-center justify-between"><span><span data-lang="bn">সম্পন্ন প্রকল্পসমূহ</span><span data-lang="en" class="hidden">Completed Projects</span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 ml-2 shrink-0"><path d="M9 5l7 7-7 7"/></svg></a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Engage & Act -->
              <div class="relative group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">অংশগ্রহণ করুন</span><span data-lang="en" class="hidden">Engage & Act</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  <div class="absolute left-0 top-full pt-1 custom-dropdown z-50" style="min-width:180px">
                      <div class="bg-white shadow-2xl border border-gray-100">
                          <a href="gov-links.php" class="mega-link px-5 py-2.5"><span data-lang="bn">সরকারি লিংকসমূহ</span><span data-lang="en" class="hidden">Govt Links</span></a>
                          <a href="forms.php" class="mega-link px-5 py-2.5"><span data-lang="bn">আবেদন ফরম</span><span data-lang="en" class="hidden">Application Forms</span></a>
                          <a href="contact.php" class="mega-link px-5 py-2.5"><span data-lang="bn">যোগাযোগ</span><span data-lang="en" class="hidden">Contact</span></a>
                      </div>
                  </div>
              </div>

              <!-- Publications -->
              <a href="#" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                  <span data-lang="bn">প্রতিবেদন ও প্রকাশনা</span><span data-lang="en" class="hidden">Publications</span>
              </a>

              <!-- News & Stories -->
              <a href="#" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                  <span data-lang="bn">সংবাদ ও অভিজ্ঞতা</span><span data-lang="en" class="hidden">News & Stories</span>
              </a>

              <!-- Desktop Search Bar (Right aligned) -->
              <div class="relative ml-auto flex items-center">
                  <input type="text" placeholder="Search..." class="w-32 xl:w-48 rounded-full border border-border bg-surface px-4 py-1.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute right-3 h-4 w-4 text-foreground/50"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
              </div>

              <!-- CTA Button -->
              <a href="donation.php" class="ml-2 whitespace-nowrap rounded-full bg-primary/10 px-4 py-1.5 text-sm font-bold text-primary transition hover:bg-primary hover:text-white <?php echo isActiveLink('donation.php', $current_page); ?>">
                  <span data-lang="bn">অনুদান</span><span data-lang="en" class="hidden">Donate</span>
              </a>
          </nav>"""

start_idx = content.find('<nav class="hidden items-center gap-0.5 xl:gap-1 lg:flex relative">')
if start_idx == -1:
    start_idx = content.find('<nav class="hidden items-center gap-0.5 xl:gap-1 lg:flex">')

end_idx = content.find('</nav>', start_idx) + 6

new_content = content[:start_idx] + nav_content + content[end_idx:]

with open('includes/header.php', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Fixed nav structure!")
