import re

with open('includes/header.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace all instances of CIVICUS with CDS in the menu
content = content.replace('CIVICUS', 'CDS')
content = content.replace('civicus', 'cds')

# Fix sub-dropdown styling to match original
# Replace the wrapper of sub-dropdowns
# Old: <div class="absolute left-full top-0 pl-2 w-[...] sub-dropdown z-50">
#      <div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">
#          <a href="#" class="block rounded-lg p-2.5 text-sm hover:bg-primary-soft transition">...</a>
# New: <div class="absolute left-full top-0 w-[...] sub-dropdown z-50">
#      <div class="py-2 bg-white shadow-xl border border-border flex flex-col">
#          <a href="#" class="block px-4 py-2 text-sm hover:text-primary transition text-foreground/80">...</a>

# I'll use regex to fix all the sub-dropdowns in one go.
# Since it's easier to just rebuild the relevant parts, I will do some regex replacements.

# Fix the gap on the absolute container
content = re.sub(
    r'<div class="absolute left-full top-0 pl-2 (w-\d+) sub-dropdown z-50">',
    r'<div class="absolute left-full top-0 \1 sub-dropdown z-50 -mt-2">',
    content
)

# Fix the inner container
content = re.sub(
    r'<div class="p-2 rounded-xl bg-white shadow-xl ring-1 ring-black/5 flex flex-col gap-1">',
    r'<div class="py-2 bg-white shadow-lg border border-border/50 flex flex-col">',
    content
)

# Fix the links inside the sub-dropdowns
content = re.sub(
    r'<a href="#" class="block rounded-lg p-2\.?5? text-sm hover:bg-primary-soft transition">',
    r'<a href="#" class="block px-5 py-2 text-sm text-foreground/80 hover:text-primary transition">',
    content
)

with open('includes/header.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated CIVICUS -> CDS and fixed sub-menu CSS")
