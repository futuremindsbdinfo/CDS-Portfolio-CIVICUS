// assets/js/scripts.js

document.addEventListener('DOMContentLoaded', () => {
    
    // ==========================================
    // Project Filtering Logic (projects.php)
    // ==========================================
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    if (filterBtns.length > 0 && projectCards.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active styling from all buttons
                filterBtns.forEach(b => {
                    b.classList.remove('bg-cds-green', 'text-white');
                    b.classList.add('bg-white', 'text-gray-700');
                });
                
                // Add active styling to the clicked button
                btn.classList.remove('bg-white', 'text-gray-700');
                btn.classList.add('bg-cds-green', 'text-white');

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
    // Gallery Lightbox Logic (gallery.php)
    // ==========================================
    const galleryItems = document.querySelectorAll('.gallery-item');
    if (galleryItems.length > 0) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxCaptionBn = document.getElementById('lightbox-caption-bn');
        const lightboxCaptionEn = document.getElementById('lightbox-caption-en');
        const lightboxClose = document.getElementById('lightbox-close');

        // Open Lightbox
        galleryItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const imgSrc = item.getAttribute('href');
                const captionBn = item.getAttribute('data-caption-bn');
                const captionEn = item.getAttribute('data-caption-en');
                
                lightboxImg.src = imgSrc;
                lightboxCaptionBn.textContent = captionBn;
                lightboxCaptionEn.textContent = captionEn;
                
                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
            });
        });

        // Close Lightbox on Close Button Click
        lightboxClose.addEventListener('click', () => {
            closeLightbox();
        });

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
});
