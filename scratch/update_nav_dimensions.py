import re

with open('includes/header.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the mega menus specifically.
# First, let's fix the `<nav>` to not be static. 
# We'll just replace the entire `<nav class="hidden items-center gap-0.5 xl:gap-1 lg:flex static">` block.

new_nav = """          <nav class="hidden items-center gap-0.5 xl:gap-1 lg:flex relative">
              <a href="index.php" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary <?php echo isActiveLink('index.php', $current_page); ?>">
                  <span data-lang="bn">Home</span><span data-lang="en" class="hidden">Home</span>
              </a>
              
              <!-- Who We Are Mega Menu -->
              <div class="group relative">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">Who We Are</span><span data-lang="en" class="hidden">Who We Are</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  
                  <div class="absolute left-0 top-full pt-2 w-max custom-dropdown z-50">
                      <div class="bg-white shadow-xl ring-1 ring-black/5 rounded-2xl p-6 flex gap-8">
                          <!-- Column 1 -->
                          <div class="w-48">
                              <h4 class="font-bold text-foreground mb-4 text-sm"><span data-lang="bn">Our Impact Stories</span><span data-lang="en" class="hidden">Our Impact Stories</span></h4>
                              <ul class="space-y-3">
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">General Comment 37</span><span data-lang="en" class="hidden">General Comment 37</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Co-Creation</span><span data-lang="en" class="hidden">Co-Creation</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Zambia: 15+ year campaign for rights</span><span data-lang="en" class="hidden">Zambia: 15+ year campaign for rights</span></a></li>
                              </ul>
                          </div>
                          <!-- Column 2 -->
                          <div class="w-48">
                              <h4 class="font-bold text-foreground mb-4 text-sm"><span data-lang="bn">Values and accountability</span><span data-lang="en" class="hidden">Values and accountability</span></h4>
                              <ul class="space-y-3">
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Diversity and Inclusion</span><span data-lang="en" class="hidden">Diversity and Inclusion</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Hold Us to Account</span><span data-lang="en" class="hidden">Hold Us to Account</span></a></li>
                              </ul>
                          </div>
                          <!-- Column 3 -->
                          <div class="w-48">
                              <h4 class="font-bold text-foreground mb-4 text-sm"><span data-lang="bn">Organization</span><span data-lang="en" class="hidden">Organization</span></h4>
                              <ul class="space-y-3">
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Annual Reports</span><span data-lang="en" class="hidden">Annual Reports</span></a></li>
                                  <li class="relative group-sub">
                                      <a href="#" class="text-sm text-foreground/70 hover:text-primary transition flex items-center justify-between"><span data-lang="bn">Board</span><span data-lang="en" class="hidden">Board</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M9 5l7 7-7 7" /></svg></a>
                                      <div class="absolute left-full top-0 pl-2 w-48 sub-dropdown z-50">
                                          <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
                                              <a href="#" class="block rounded-lg p-2 text-sm hover:bg-primary-soft transition"><span data-lang="bn">Board Elections 2026</span><span data-lang="en" class="hidden">Board Elections 2026</span></a>
                                          </div>
                                      </div>
                                  </li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Members</span><span data-lang="en" class="hidden">Members</span></a></li>
                              </ul>
                          </div>
                          <!-- Column 4 -->
                          <div class="w-48">
                              <h4 class="font-bold text-foreground mb-4 text-sm opacity-0 hidden lg:block">Spacer</h4>
                              <ul class="space-y-3">
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Networks</span><span data-lang="en" class="hidden">Networks</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Staff</span><span data-lang="en" class="hidden">Staff</span></a></li>
                                  <li><a href="contact.php" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Contact Us</span><span data-lang="en" class="hidden">Contact Us</span></a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- What We Do Mega Menu -->
              <div class="group relative">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">What We Do</span><span data-lang="en" class="hidden">What We Do</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  
                  <div class="absolute left-1/2 -translate-x-1/3 xl:-translate-x-1/2 top-full pt-2 w-max custom-dropdown z-50">
                      <div class="bg-white shadow-xl ring-1 ring-black/5 rounded-2xl p-6 flex gap-8">
                          <!-- Column 1 -->
                          <div class="w-56">
                              <h4 class="font-bold text-foreground mb-4 text-sm"><span data-lang="bn">Co-creating Knowledge</span><span data-lang="en" class="hidden">Co-creating Knowledge</span></h4>
                              <ul class="space-y-3">
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">CIVICUS Monitor Ratings</span><span data-lang="en" class="hidden">CIVICUS Monitor Ratings</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">CIVICUS Lens Analysis</span><span data-lang="en" class="hidden">CIVICUS Lens Analysis</span></a></li>
                                  <li class="relative group-sub">
                                      <a href="#" class="text-sm text-foreground/70 hover:text-primary transition flex items-center justify-between"><span data-lang="bn">Our Reports</span><span data-lang="en" class="hidden">Our Reports</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M9 5l7 7-7 7" /></svg></a>
                                      <div class="absolute left-full top-0 pl-2 w-56 sub-dropdown z-50">
                                          <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
                                              <a href="#" class="block rounded-lg p-2 text-sm hover:bg-primary-soft transition"><span data-lang="bn">State of Civil Society Reports</span><span data-lang="en" class="hidden">State of Civil Society Reports</span></a>
                                              <a href="#" class="block rounded-lg p-2 text-sm hover:bg-primary-soft transition"><span data-lang="bn">People Power Under Attack</span><span data-lang="en" class="hidden">People Power Under Attack</span></a>
                                              <a href="#" class="block rounded-lg p-2 text-sm hover:bg-primary-soft transition"><span data-lang="bn">Other Publications</span><span data-lang="en" class="hidden">Other Publications</span></a>
                                          </div>
                                      </div>
                                  </li>
                              </ul>
                          </div>
                          <!-- Column 2 -->
                          <div class="w-56">
                              <h4 class="font-bold text-foreground mb-4 text-sm"><span data-lang="bn">Advocating for Change</span><span data-lang="en" class="hidden">Advocating for Change</span></h4>
                              <ul class="space-y-3">
                                  <li class="relative group-sub">
                                      <a href="#" class="text-sm text-foreground/70 hover:text-primary transition flex items-center justify-between"><span data-lang="bn">Campaigns</span><span data-lang="en" class="hidden">Campaigns</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M9 5l7 7-7 7" /></svg></a>
                                  </li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">CIVICUS at the UN</span><span data-lang="en" class="hidden">CIVICUS at the UN</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Civic Space Project in Central America (ES)</span><span data-lang="en" class="hidden">Civic Space Project in Central America (ES)</span></a></li>
                              </ul>
                          </div>
                          <!-- Column 3 -->
                          <div class="w-56">
                              <h4 class="font-bold text-foreground mb-4 text-sm"><span data-lang="bn">Enabling and Resourcing</span><span data-lang="en" class="hidden">Enabling and Resourcing</span></h4>
                              <ul class="space-y-3">
                                  <li class="relative group-sub"><a href="#" class="text-sm text-foreground/70 hover:text-primary transition flex items-center justify-between"><span data-lang="bn">Local Leadership Labs</span><span data-lang="en" class="hidden">Local Leadership Labs</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M9 5l7 7-7 7" /></svg></a></li>
                                  <li class="relative group-sub"><a href="#" class="text-sm text-foreground/70 hover:text-primary transition flex items-center justify-between"><span data-lang="bn">Digital Democracy Initiative</span><span data-lang="en" class="hidden">Digital Democracy Initiative</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M9 5l7 7-7 7" /></svg></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">CIVICUS Youth</span><span data-lang="en" class="hidden">CIVICUS Youth</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">CHARM Africa</span><span data-lang="en" class="hidden">CHARM Africa</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">FoPA</span><span data-lang="en" class="hidden">FoPA</span></a></li>
                                  <li class="relative group-sub">
                                      <a href="#" class="text-sm text-foreground/70 hover:text-primary transition flex items-center justify-between"><span data-lang="bn">Completed Projects</span><span data-lang="en" class="hidden">Completed Projects</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M9 5l7 7-7 7" /></svg></a>
                                      <div class="absolute left-full top-0 pl-2 w-56 sub-dropdown z-50">
                                          <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
                                              <a href="#" class="block rounded-lg p-2.5 text-sm hover:bg-primary-soft transition"><span data-lang="bn">Stand As My Witness</span><span data-lang="en" class="hidden">Stand As My Witness</span></a>
                                              <a href="#" class="block rounded-lg p-2.5 text-sm hover:bg-primary-soft transition"><span data-lang="bn">Donor Challenge</span><span data-lang="en" class="hidden">Donor Challenge</span></a>
                                              <a href="#" class="block rounded-lg p-2.5 text-sm hover:bg-primary-soft transition"><span data-lang="bn">International Civil Society Week 2025</span><span data-lang="en" class="hidden">International Civil Society Week 2025</span></a>
                                          </div>
                                      </div>
                                  </li>
                              </ul>
                          </div>
                          <!-- Column 4 -->
                          <div class="w-56">
                              <h4 class="font-bold text-foreground mb-4 text-sm"><span data-lang="bn">Building Networks</span><span data-lang="en" class="hidden">Building Networks</span></h4>
                              <ul class="space-y-3">
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Vuka! Coalition for Civic Action</span><span data-lang="en" class="hidden">Vuka! Coalition for Civic Action</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">AGNA</span><span data-lang="en" class="hidden">AGNA</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">Innovation for Change</span><span data-lang="en" class="hidden">Innovation for Change</span></a></li>
                                  <li><a href="#" class="text-sm text-foreground/70 hover:text-primary transition block"><span data-lang="bn">CIVICUS Youth</span><span data-lang="en" class="hidden">CIVICUS Youth</span></a></li>
                                  <li class="relative group-sub"><a href="#" class="text-sm text-foreground/70 hover:text-primary transition flex items-center justify-between"><span data-lang="bn">Completed Projects</span><span data-lang="en" class="hidden">Completed Projects</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3"><path d="M9 5l7 7-7 7" /></svg></a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Engage & Act -->
              <div class="relative group">
                  <button class="flex items-center gap-1 whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                      <span data-lang="bn">Engage & Act</span><span data-lang="en" class="hidden">Engage & Act</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 transition-transform group-hover:rotate-180"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                  </button>
                  <div class="absolute left-0 top-full pt-2 w-48 custom-dropdown z-50">
                      <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
                          <a href="gov-links.php" class="block rounded-lg p-2.5 hover:bg-primary-soft transition text-foreground">
                              <div class="font-semibold text-sm"><span data-lang="bn">Govt Links</span><span data-lang="en" class="hidden">Govt Links</span></div>
                          </a>
                          <a href="forms.php" class="block rounded-lg p-2.5 hover:bg-primary-soft transition text-foreground">
                              <div class="font-semibold text-sm"><span data-lang="bn">Application Forms</span><span data-lang="en" class="hidden">Application Forms</span></div>
                          </a>
                          <a href="contact.php" class="block rounded-lg p-2.5 hover:bg-primary-soft transition text-foreground">
                              <div class="font-semibold text-sm"><span data-lang="bn">Contact</span><span data-lang="en" class="hidden">Contact</span></div>
                          </a>
                      </div>
                  </div>
              </div>

              <!-- Publications -->
              <a href="#" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                  <span data-lang="bn">Publications</span><span data-lang="en" class="hidden">Publications</span>
              </a>

              <!-- News & Stories -->
              <a href="#" class="whitespace-nowrap rounded-full px-2 xl:px-3 py-2 text-sm font-medium transition hover:bg-primary-soft hover:text-primary text-foreground/80">
                  <span data-lang="bn">News & Stories</span><span data-lang="en" class="hidden">News & Stories</span>
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

content = re.sub(r'<nav class="hidden items-center gap-0\.5 xl:gap-1 lg:flex.*?">.*?</nav>', new_nav, content, flags=re.DOTALL)

with open('includes/header.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated nav block with confined width Mega Menus")
