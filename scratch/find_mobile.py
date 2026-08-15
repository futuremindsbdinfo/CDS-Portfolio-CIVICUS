with open('scratch/old_header.php', 'r', encoding='utf-16le') as f:
    lines = f.readlines()
for i, line in enumerate(lines):
    if 'id="mobile-menu"' in line:
        print(''.join(lines[i:i+60]))
        break
