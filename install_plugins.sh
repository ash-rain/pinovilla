#!/bin/bash

PLUGIN_DIR="wp-content/plugins"
mkdir -p "$PLUGIN_DIR"

# Contact Form 7
echo "Downloading Contact Form 7..."
curl -L -o cf7.zip https://downloads.wordpress.org/plugin/contact-form-7.latest-stable.zip
unzip -q -o cf7.zip -d "$PLUGIN_DIR"
rm cf7.zip
echo "Contact Form 7 installed."

# Amelia Lite
echo "Downloading Amelia Lite..."
curl -L -o amelia.zip https://downloads.wordpress.org/plugin/ameliabooking.latest-stable.zip
unzip -q -o amelia.zip -d "$PLUGIN_DIR"
rm amelia.zip
echo "Amelia Lite installed."

# WPML (Placeholder)
echo "WPML is a paid plugin. Please upload the plugin files to $PLUGIN_DIR/sitepress-multilingual-cms/"
mkdir -p "$PLUGIN_DIR/sitepress-multilingual-cms"
