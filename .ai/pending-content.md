# Pending Content & Placeholders

This document lists all the files and locations where placeholder images or dummy text are currently being used. **Please update these before launching the site live.**

## 1. Images & Visuals

- **Team Members (about.php)**
  - Line 108: `assets/img/team/placeholder.jpg` (President image)
  - Line 123: `assets/img/team/placeholder.jpg` (Secretary image)
  - Line 138: `assets/img/team/placeholder.jpg` (Treasurer image)
  - Line 153: `assets/img/team/placeholder.jpg` (Coordinator image)
  - *Action:* Replace these placeholder files in the `assets/img/team/` directory with real team member photos.

- **Homepage Hero/Preview (index.php)**
  - Line 32-35: `<div class="bg-gray-200 aspect-video... Image/Video Placeholder ...`
  - *Action:* Replace this placeholder `div` with an actual video tag, slider, or high-quality static image representing CDS activities.

- **Admin Panel Default Images**
  - `admin/projects_admin.php` (Line 35): `$cover_image = 'assets/img/projects/placeholder.jpg';`
  - `admin/gallery_admin.php` (Line 38): `$image_path = 'assets/img/gallery/placeholder.jpg';`
  - *Action:* When you implement actual file uploading functionality (Phase 6+ / Future), remove these hardcoded placeholders. Until then, ensure `placeholder.jpg` exists in those directories or replace it with valid default images.

## 2. Content & Text

- **Team Member Names & Roles (about.php)**
  - Ensure the names "Ashraful" (President), etc., are the final names and add their real bio/details if needed.
- **Organization History & Vision (about.php)**
  - The current text is general (though well-written). Review it to ensure it perfectly aligns with CDS's real-world history and mission.
- **Impact Stats Band (index.php)**
  - There are 4 hardcoded placeholder numbers with vanilla JS count-up animation: "স্বেচ্ছাসেবী" (320), "সম্পন্ন প্রজেক্ট" (45), "উপকারভোগী পরিবার" (1500), "প্রতিষ্ঠার বছর" (2015).
  - *Action:* Replace the `data-target="..."` values in `index.php` (around lines 85-115) with the actual, current statistics before launch.

> **Note to Dev:** As you replace these items, you can check them off this list!
