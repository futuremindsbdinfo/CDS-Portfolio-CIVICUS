// assets/js/scripts.js

document.addEventListener('DOMContentLoaded', () => {
    
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
                mobileMenu.classList.add('max-h-96');
                if(iconOpen) iconOpen.classList.add('hidden');
                if(iconClose) iconClose.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('max-h-0');
                mobileMenu.classList.remove('max-h-96');
                if(iconOpen) iconOpen.classList.remove('hidden');
                if(iconClose) iconClose.classList.add('hidden');
            }
        });
        
        // Close menu on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('max-h-96')) {
                mobileMenu.classList.add('max-h-0');
                mobileMenu.classList.remove('max-h-96');
                if(iconOpen) iconOpen.classList.remove('hidden');
                if(iconClose) iconClose.classList.add('hidden');
            }
        });

        // Close when clicking outside
        document.addEventListener('mousedown', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                if (mobileMenu.classList.contains('max-h-96')) {
                    mobileMenu.classList.add('max-h-0');
                    mobileMenu.classList.remove('max-h-96');
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
        const lightboxClose = document.getElementById('lightbox-close');

        if(lightbox) {
            // Open Lightbox
            galleryItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
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
                    
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                });
            });

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
            
            // Close Lightbox on Escape Key Press
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) {
                    closeLightbox();
                }
            });

            function closeLightbox() {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
            }
        }
    }
});
