// assets/js/scripts.js

document.addEventListener('DOMContentLoaded', () => {
    // ==========================================
    // Language Toggle Logic (Bilingual Support)
    // ==========================================
    const langBtns = document.querySelectorAll('.lang-toggle-btn');
    
    function applyLanguage(lang) {
        document.documentElement.lang = lang;
        localStorage.setItem('cds_lang', lang);

        // Toggle data-lang elements
        const allLangEls = document.querySelectorAll('[data-lang]');
        allLangEls.forEach(el => {
            if (el.getAttribute('data-lang') === lang) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });

        // Update header active language text indicators (BN / EN)
        const langTextBadges = document.querySelectorAll('.active-lang-text');
        langTextBadges.forEach(badge => {
            badge.textContent = lang.toUpperCase();
        });

        // Toggle checkmarks inside dropdowns
        const checksBn = document.querySelectorAll('.check-bn');
        const checksEn = document.querySelectorAll('.check-en');
        if (lang === 'bn') {
            checksBn.forEach(el => el.classList.remove('hidden'));
            checksEn.forEach(el => el.classList.add('hidden'));
        } else {
            checksBn.forEach(el => el.classList.add('hidden'));
            checksEn.forEach(el => el.classList.remove('hidden'));
        }

        // Handle input placeholders
        const inputsWithEnPlaceholder = document.querySelectorAll('[data-en-placeholder]');
        inputsWithEnPlaceholder.forEach(input => {
            if (lang === 'en') {
                if (!input.hasAttribute('data-bn-placeholder')) {
                    input.setAttribute('data-bn-placeholder', input.getAttribute('placeholder') || '');
                }
                input.setAttribute('placeholder', input.getAttribute('data-en-placeholder'));
            } else {
                if (input.hasAttribute('data-bn-placeholder')) {
                    input.setAttribute('placeholder', input.getAttribute('data-bn-placeholder'));
                }
            }
        });

        // Close dropdowns if open
        const langDropdowns = document.querySelectorAll('#desktop-lang-dropdown, #mobile-lang-dropdown');
        langDropdowns.forEach(d => d.classList.add('hidden'));
    }

    // Initialize Language
    const savedLang = localStorage.getItem('cds_lang') || 'bn';
    applyLanguage(savedLang);

    // Bind Toggle Buttons
    if (langBtns.length > 0) {
        langBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const langToSet = btn.getAttribute('data-set-lang');
                if (langToSet) applyLanguage(langToSet);
            });
        });
    }

    // ==========================================
    // Mobile Menu Toggle Logic (Lovable Header)
    // ==========================================
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        const iconOpen = mobileMenuBtn.querySelector('.menu-open-icon');
        const iconClose = mobileMenuBtn.querySelector('.menu-close-icon');

        mobileMenuBtn.addEventListener('click', () => {
            const isClosed = mobileMenu.classList.contains('max-h-0');
            if (isClosed) {
                mobileMenu.classList.remove('max-h-0');
                mobileMenu.classList.add('max-h-[calc(100vh-4rem)]');
                document.body.style.overflow = 'hidden';
                if(iconOpen) iconOpen.classList.add('hidden');
                if(iconClose) iconClose.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('max-h-0');
                mobileMenu.classList.remove('max-h-[calc(100vh-4rem)]');
                document.body.style.overflow = '';
                if(iconOpen) iconOpen.classList.remove('hidden');
                if(iconClose) iconClose.classList.add('hidden');
            }
        });
        
        // Close menu on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('max-h-[calc(100vh-4rem)]')) {
                mobileMenu.classList.add('max-h-0');
                mobileMenu.classList.remove('max-h-[calc(100vh-4rem)]');
                document.body.style.overflow = '';
                if(iconOpen) iconOpen.classList.remove('hidden');
                if(iconClose) iconClose.classList.add('hidden');
            }
        });

        // Close when clicking outside
        document.addEventListener('mousedown', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                if (mobileMenu.classList.contains('max-h-[calc(100vh-4rem)]')) {
                    mobileMenu.classList.add('max-h-0');
                    mobileMenu.classList.remove('max-h-[calc(100vh-4rem)]');
                    document.body.style.overflow = '';
                    if(iconOpen) iconOpen.classList.remove('hidden');
                    if(iconClose) iconClose.classList.add('hidden');
                }
            }
        });
    }

    // ==========================================
    // Accordion Logic (Lovable FAQs)
    // ==========================================
    const faqs = document.querySelectorAll('.faq-item');
    faqs.forEach(item => {
        const btn = item.querySelector('.faq-btn');
        const content = item.querySelector('.faq-content');
        const iconOpen = item.querySelector('.faq-icon-open');
        const iconClose = item.querySelector('.faq-icon-close');

        if (btn && content) {
            btn.addEventListener('click', () => {
                const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                
                // Optional: Close all other accordions first
                /*
                faqs.forEach(otherItem => {
                    if (otherItem !== item) {
                        const otherBtn = otherItem.querySelector('.faq-btn');
                        const otherContent = otherItem.querySelector('.faq-content');
                        if (otherBtn && otherContent) {
                            otherBtn.setAttribute('aria-expanded', 'false');
                            otherContent.classList.add('grid-rows-[0fr]', 'opacity-0');
                            otherContent.classList.remove('grid-rows-[1fr]', 'opacity-100');
                        }
                    }
                });
                */

                if (isExpanded) {
                    btn.setAttribute('aria-expanded', 'false');
                    content.classList.add('grid-rows-[0fr]', 'opacity-0');
                    content.classList.remove('grid-rows-[1fr]', 'opacity-100');
                    if(iconOpen) iconOpen.classList.remove('hidden');
                    if(iconClose) iconClose.classList.add('hidden');
                } else {
                    btn.setAttribute('aria-expanded', 'true');
                    content.classList.remove('grid-rows-[0fr]', 'opacity-0');
                    content.classList.add('grid-rows-[1fr]', 'opacity-100');
                    if(iconOpen) iconOpen.classList.add('hidden');
                    if(iconClose) iconClose.classList.remove('hidden');
                }
            });
        }
    });

    // ==========================================
    // Project Filtering Logic
    // ==========================================
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    if (filterBtns.length > 0 && projectCards.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active styling from all buttons
                filterBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('bg-white', 'text-foreground');
                });
                
                // Add active styling to the clicked button
                btn.classList.remove('bg-white', 'text-foreground');
                btn.classList.add('bg-primary', 'text-white');

                const filterValue = btn.getAttribute('data-filter');

                // Filter cards
                projectCards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        if (card.getAttribute('data-status') === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });
    }

    // ==========================================
    // Gallery Lightbox Logic
    // ==========================================
    const galleryItems = document.querySelectorAll('.gallery-item');
    if (galleryItems.length > 0) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxCaptionBn = document.getElementById('lightbox-caption-bn');
        const lightboxCaptionEn = document.getElementById('lightbox-caption-en');
        const lightboxProject = document.getElementById('lightbox-project');
        const lightboxDate = document.getElementById('lightbox-date');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const lightboxClose = document.getElementById('lightbox-close');
        
        let currentIndex = 0;

        if(lightbox) {
            function updateLightbox(index) {
                if (index < 0) index = galleryItems.length - 1;
                if (index >= galleryItems.length) index = 0;
                currentIndex = index;
                
                const item = galleryItems[currentIndex];
                const imgSrc = item.getAttribute('href');
                const captionBn = item.getAttribute('data-caption-bn');
                const captionEn = item.getAttribute('data-caption-en') || '';
                const project = item.getAttribute('data-project') || '';
                const date = item.getAttribute('data-date') || '';
                
                if(lightboxImg) lightboxImg.src = imgSrc;
                if(lightboxCaptionBn) lightboxCaptionBn.textContent = captionBn;
                if(lightboxCaptionEn) lightboxCaptionEn.textContent = captionEn;
                if(lightboxProject) lightboxProject.textContent = project;
                if(lightboxDate) lightboxDate.textContent = date;
            }

            // Open Lightbox
            galleryItems.forEach((item, index) => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    updateLightbox(index);
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                });
            });

            // Prev / Next logic
            function showPrev() {
                updateLightbox(currentIndex - 1);
            }
            function showNext() {
                updateLightbox(currentIndex + 1);
            }

            if (lightboxPrev) {
                lightboxPrev.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showPrev();
                });
            }
            if (lightboxNext) {
                lightboxNext.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showNext();
                });
            }

            // Close Lightbox on Close Button Click
            if(lightboxClose) {
                lightboxClose.addEventListener('click', closeLightbox);
            }

            // Close Lightbox on Background Click
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    closeLightbox();
                }
            });
            
            // Keyboard Navigation (Esc, Left, Right)
            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('hidden')) {
                    if (e.key === 'Escape') {
                        closeLightbox();
                    } else if (e.key === 'ArrowLeft') {
                        showPrev();
                    } else if (e.key === 'ArrowRight') {
                        showNext();
                    }
                }
            });

            function closeLightbox() {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
            }
        }
    }
});
