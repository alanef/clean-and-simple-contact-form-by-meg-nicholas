#!/bin/bash

# Script to generate KB-ready HTML from markdown files
# - Removes H1 (KB uses post title)
# - No table of contents (KB generates from H2s)
# - Clean HTML content only

echo "Generating KB-ready HTML files..."

# Process each markdown file
for md_file in *.md; do
    if [ -f "$md_file" ]; then
        # Get base name without extension
        base_name="${md_file%.md}"
        output_file="${base_name}-kb.html"
        
        echo "Processing: $md_file -> $output_file"
        
        # Convert to HTML without standalone wrapper
        # Skip the first heading level (H1)
        # No table of contents
        pandoc "$md_file" \
            -f markdown \
            -t html5 \
            --no-highlight \
            -o temp.html
        
        # Remove the first H1 tag and its content (handles multi-line)
        # Also remove the Table of Contents section
        perl -0pe 's/<h1[^>]*>.*?<\/h1>\s*//s' temp.html | \
        perl -0pe 's/<h2[^>]*>Table of Contents<\/h2>.*?<hr.*?\/>\s*//s' > "$output_file"
        
        # Clean up
        rm -f temp.html
        
        echo "  Created: $output_file"
    fi
done

echo "KB-ready files generated!"
echo ""
echo "Files created:"
ls -1 *-kb.html 2>/dev/null || echo "No files generated"