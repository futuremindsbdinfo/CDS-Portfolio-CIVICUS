import os
import re

directories_to_scan = ['.', 'includes', 'admin', 'admin/includes']

for directory in directories_to_scan:
    if not os.path.exists(directory):
        continue
    for filename in os.listdir(directory):
        if filename.endswith(".php"):
            filepath = os.path.join(directory, filename)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Replace href="/..." with href="..."
            content = re.sub(r'href="/([^/])', r'href="\1', content)
            
            # Replace src="/..." with src="..."
            content = re.sub(r'src="/([^/])', r'src="\1', content)
            
            # Special case for href="/" -> href="index.php"
            content = re.sub(r'href="/"', r'href="index.php"', content)
            
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
                
print("Paths fixed!")
