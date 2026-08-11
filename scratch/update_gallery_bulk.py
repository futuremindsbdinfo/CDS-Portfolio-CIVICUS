import os

filepath = 'E:/Client Projects/01_Client_Projects/cds-portfolio/admin/gallery_admin.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add Bulk Delete Handler after Delete handler (around line 39)
bulk_delete_handler = """
// Handle Bulk Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'bulk_delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids) && is_array($ids) && $db) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        // Delete files first
        $stmt = $db->prepare("SELECT image_path FROM gallery WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $photos = $stmt->fetchAll();
        foreach ($photos as $photo) {
            if ($photo['image_path'] && file_exists(__DIR__ . '/../uploads/gallery/' . $photo['image_path'])) {
                @unlink(__DIR__ . '/../uploads/gallery/' . $photo['image_path']);
            }
        }
        
        // Delete records
        $stmt = $db->prepare("DELETE FROM gallery WHERE id IN ($placeholders)");
        if ($stmt->execute($ids)) {
            $_SESSION['flash_message'] = count($ids) . " photo(s) deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete photos.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: gallery_admin.php");
    exit;
}
"""
content = content.replace("// Handle Add (Bulk Upload)", bulk_delete_handler + "\n// Handle Add (Bulk Upload)")

# 2. Add Bulk Delete button to header (around line 174)
header_html = """
        <button onclick="document.getElementById('add-photo-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
            নতুন ছবি
        </button>
"""
new_header_html = """
        <div class="flex gap-2">
            <button onclick="bulkDelete()" id="bulk-delete-btn" class="hidden inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:brightness-110">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round" /></svg>
                Bulk Delete
            </button>
            <button onclick="document.getElementById('add-photo-form').classList.toggle('hidden')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-3.5 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:brightness-110">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-linecap="round" /></svg>
                নতুন ছবি
            </button>
        </div>
"""
content = content.replace(header_html, new_header_html)

# 3. Add Form wrapper for the table
form_start = """
    <!-- Gallery List -->
    <form id="bulk-delete-form" action="gallery_admin.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="bulk_delete">
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
"""
content = content.replace("    <!-- Gallery List -->\n    <div class=\"rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden\">", form_start)

# 4. Add <th> for checkboxes
thead_tr = """
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-8"><input type="checkbox" onchange="toggleAll(this)" class="rounded border-slate-300 text-primary focus:ring-primary"></th>
"""
content = content.replace("                    <tr class=\"border-b border-slate-200 bg-slate-50 text-xs text-slate-500\">\n", thead_tr)

# 5. Add <td> for checkboxes in the row
tbody_tr = """
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3"><input type="checkbox" name="ids[]" value="<?php echo $photo['id']; ?>" class="row-checkbox rounded border-slate-300 text-primary focus:ring-primary" onchange="checkBulkDeleteBtn()"></td>
"""
content = content.replace("                        <tr class=\"hover:bg-slate-50/50\">\n", tbody_tr)

# 6. colspan="5" to colspan="6"
content = content.replace('colspan="5"', 'colspan="6"')

# 7. Add form end and JS
form_end = """
    </div>
    </form>
"""
content = content.replace("    </div>\n</div>\n\n<?php require_once", form_end + "\n</div>\n\n<script>\nfunction toggleAll(source) {\n    checkboxes = document.querySelectorAll('.row-checkbox');\n    for(var i=0, n=checkboxes.length;i<n;i++) {\n        checkboxes[i].checked = source.checked;\n    }\n    checkBulkDeleteBtn();\n}\n\nfunction checkBulkDeleteBtn() {\n    const anyChecked = document.querySelectorAll('.row-checkbox:checked').length > 0;\n    const btn = document.getElementById('bulk-delete-btn');\n    if (anyChecked) {\n        btn.classList.remove('hidden');\n    } else {\n        btn.classList.add('hidden');\n    }\n}\n\nfunction bulkDelete() {\n    if (confirm('Are you sure you want to delete the selected photos?')) {\n        document.getElementById('bulk-delete-form').submit();\n    }\n}\n</script>\n\n<?php require_once")

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("gallery_admin.php updated for bulk delete.")
