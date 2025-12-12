#!/usr/bin/env python3
import glob
import re

# Remove HTML comment lines from Vue files
for f in glob.glob('/var/www/html/resources/js/**/*.vue', recursive=True):
    try:
        with open(f, 'r', encoding='utf-8') as file:
            content = file.read()
        
        # Remove any HTML comments that appear before the first <script or <template tag
        content = re.sub(r'^<!--\s*.*?-->\s*\n', '', content, flags=re.MULTILINE | re.DOTALL)
        
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
        print(f"Fixed: {f}")
    except Exception as e:
        print(f"Error fixing {f}: {e}")

print("\nDone!")
