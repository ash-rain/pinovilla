#!/bin/bash

BASE_URL="https://pinovilla.com"
THEME_DIR="wp-content/themes/pinovilla"

# List of missing CSS assets
CSS_ASSETS=(
"/assets/Website/css/animate.css"
"/assets/Website/css/owl.css"
"/assets/Website/css/swiper.min.css"
"/assets/Website/css/jquery.fancybox.min.css"
"/assets/Website/css/linear.css"
"/assets/Website/css/fontawesome-free.css"
"/assets/Website/css/fontawesome.css"
"/assets/Website/css/flaticon.css"
"/assets/Website/css/tm-bs-mp.css"
"/assets/Website/css/tm-utility-classes.css"
)

for asset in "${CSS_ASSETS[@]}"; do
    clean_asset=$(echo "$asset" | cut -d? -f1)
    dir=$(dirname "$THEME_DIR$clean_asset")
    mkdir -p "$dir"
    echo "Downloading $BASE_URL$asset to $THEME_DIR$clean_asset"
    curl -L -s "$BASE_URL$asset" -o "$THEME_DIR$clean_asset"
done

# Extract images from style.css and download them
# We look for url(../images/...) and similar patterns
# The grep command extracts the content inside url()
# Then we normalize the path.
# Since style.css is in /assets/Website/css/, ../images/ means /assets/Website/images/

echo "Extracting images from style.css..."
grep -o 'url([[:space:]]*[^)]*)' "$THEME_DIR/assets/Website/css/style.css" | sed 's/url(//;s/)//;s/["'\'']//g' | sort | uniq | while read -r img_path; do
    # Skip data URIs and external URLs
    if [[ "$img_path" == data:* ]] || [[ "$img_path" == http* ]]; then
        continue
    fi
    
    # Resolve path relative to css folder
    # Assuming img_path starts with ../
    if [[ "$img_path" == ../* ]]; then
        # Remove ../
        rel_path=${img_path#../}
        full_path="/assets/Website/$rel_path"
    else
        # Assume it's relative to css folder directly (unlikely for images but possible)
        full_path="/assets/Website/css/$img_path"
    fi
    
    # Remove query strings
    clean_path=$(echo "$full_path" | cut -d? -f1)
    
    # Create directory
    dir=$(dirname "$THEME_DIR$clean_path")
    mkdir -p "$dir"
    
    # Download
    echo "Downloading $BASE_URL$clean_path to $THEME_DIR$clean_path"
    curl -L -s "$BASE_URL$clean_path" -o "$THEME_DIR$clean_path"
done
