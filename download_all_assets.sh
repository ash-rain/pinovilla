#!/bin/bash

BASE_URL="https://pinovilla.com"
THEME_DIR="wp-content/themes/pinovilla"

echo "Scanning all CSS files for assets..."

find "$THEME_DIR/assets/Website/css" -name "*.css" | while read css_file; do
    echo "Processing $css_file..."
    grep -o 'url([[:space:]]*[^)]*)' "$css_file" | sed 's/url(//;s/)//;s/["'\'']//g' | sort | uniq | while read -r asset_path; do
        # Skip data URIs and external URLs
        if [[ "$asset_path" == data:* ]] || [[ "$asset_path" == http* ]]; then
            continue
        fi
        
        # Resolve path
        # Assuming asset_path is relative to the css file location
        # Most are ../fonts/ or ../images/
        
        if [[ "$asset_path" == ../* ]]; then
            rel_path=${asset_path#../}
            full_path="/assets/Website/$rel_path"
        else
            # Assume it's relative to css folder directly
            full_path="/assets/Website/css/$asset_path"
        fi
        
        # Remove query strings/hashes
        clean_path=$(echo "$full_path" | cut -d? -f1 | cut -d# -f1)
        
        # Create directory
        dir=$(dirname "$THEME_DIR$clean_path")
        mkdir -p "$dir"
        
        # Check if file already exists
        if [ ! -f "$THEME_DIR$clean_path" ]; then
            echo "Downloading $BASE_URL$clean_path to $THEME_DIR$clean_path"
            curl -L -s "$BASE_URL$clean_path" -o "$THEME_DIR$clean_path"
        fi
    done
done
