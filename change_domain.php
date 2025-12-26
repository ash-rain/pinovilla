<?php
/**
 * WordPress Domain Change Script
 * 
 * This script changes the domain across the entire WordPress site.
 * It handles database updates, serialized data, and content URLs.
 * 
 * Usage: php change_domain.php old-domain.com new-domain.com
 */

// Load WordPress
require_once(__DIR__ . '/wp-load.php');

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Get command line arguments
$old_domain = $argv[1] ?? null;
$new_domain = $argv[2] ?? null;

if (!$old_domain || !$new_domain) {
    echo "Usage: php change_domain.php old-domain.com new-domain.com\n";
    echo "Example: php change_domain.php oldsite.com newsite.com\n";
    exit(1);
}

// Remove http:// or https:// if provided
$old_domain = preg_replace('#^https?://#', '', $old_domain);
$new_domain = preg_replace('#^https?://#', '', $new_domain);

// Trim trailing slashes
$old_domain = rtrim($old_domain, '/');
$new_domain = rtrim($new_domain, '/');

echo "========================================\n";
echo "WordPress Domain Change Script\n";
echo "========================================\n";
echo "Old Domain: $old_domain\n";
echo "New Domain: $new_domain\n";
echo "========================================\n\n";

// Confirm before proceeding
echo "⚠️  WARNING: This will modify your database!\n";
echo "Make sure you have a backup before proceeding.\n\n";
echo "Do you want to continue? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if ($line !== 'yes') {
    echo "Operation cancelled.\n";
    exit(0);
}

global $wpdb;

// Disable WordPress cache
wp_cache_flush();

echo "\n[1/4] Updating WordPress options...\n";

// Update site URL and home URL
$old_url_http = 'http://' . $old_domain;
$old_url_https = 'https://' . $old_domain;
$new_url = 'https://' . $new_domain;

$updated_options = 0;

// Update both http and https versions
$result = $wpdb->update(
    $wpdb->options,
    ['option_value' => $new_url],
    ['option_name' => 'siteurl']
);
if ($result !== false) $updated_options++;

$result = $wpdb->update(
    $wpdb->options,
    ['option_value' => $new_url],
    ['option_name' => 'home']
);
if ($result !== false) $updated_options++;

echo "✓ Updated $updated_options core options\n";

echo "\n[2/4] Getting all database tables...\n";

// Get all tables
$tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
$table_count = count($tables);
echo "✓ Found $table_count tables\n";

echo "\n[3/4] Processing all tables...\n";

$total_replacements = 0;
$processed_tables = 0;

foreach ($tables as $table) {
    $table_name = $table[0];
    $processed_tables++;
    
    echo "  Processing table $processed_tables/$table_count: $table_name ... ";
    
    // Get all columns for this table
    $columns = $wpdb->get_results("DESCRIBE $table_name", ARRAY_A);
    
    $table_replacements = 0;
    
    foreach ($columns as $column) {
        $column_name = $column['Field'];
        $column_type = $column['Type'];
        
        // Only process text-based columns
        if (!preg_match('/(text|char|blob)/i', $column_type)) {
            continue;
        }
        
        // Get primary key for updates
        $primary_key = $wpdb->get_row("SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'", ARRAY_A);
        $primary_key_column = $primary_key['Column_name'] ?? null;
        
        if (!$primary_key_column) {
            continue;
        }
        
        // Get all rows for this column
        $rows = $wpdb->get_results("SELECT $primary_key_column, $column_name FROM $table_name", ARRAY_A);
        
        foreach ($rows as $row) {
            $old_value = $row[$column_name];
            
            if (empty($old_value) || !is_string($old_value)) {
                continue;
            }
            
            // Try to unserialize to check if it's serialized data
            $is_serialized = @unserialize($old_value) !== false || $old_value === 'b:0;';
            
            if ($is_serialized) {
                // Handle serialized data
                $new_value = maybe_unserialize($old_value);
                $new_value = domain_replace_recursive($new_value, $old_domain, $new_domain);
                $new_value = maybe_serialize($new_value);
            } else {
                // Simple string replacement for both http and https
                $new_value = str_replace('http://' . $old_domain, 'https://' . $new_domain, $old_value);
                $new_value = str_replace('https://' . $old_domain, 'https://' . $new_domain, $new_value);
                $new_value = str_replace('//' . $old_domain, '//' . $new_domain, $new_value);
            }
            
            // Update if changed
            if ($old_value !== $new_value) {
                $wpdb->update(
                    $table_name,
                    [$column_name => $new_value],
                    [$primary_key_column => $row[$primary_key_column]]
                );
                $table_replacements++;
                $total_replacements++;
            }
        }
    }
    
    echo "$table_replacements replacements\n";
}

echo "\n[4/4] Flushing caches...\n";

// Flush all caches
wp_cache_flush();

// Flush rewrite rules
flush_rewrite_rules(true);

// Clear object cache if available
if (function_exists('wp_cache_clear_cache')) {
    wp_cache_clear_cache();
}

echo "✓ Caches flushed\n";

echo "\n========================================\n";
echo "✓ Domain change complete!\n";
echo "========================================\n";
echo "Total replacements made: $total_replacements\n";
echo "Tables processed: $processed_tables\n";
echo "\nOld domain: $old_domain\n";
echo "New domain: $new_domain\n";
echo "========================================\n\n";

echo "Next steps:\n";
echo "1. Update wp-config.php if it contains domain-specific settings\n";
echo "2. Update your web server configuration (Nginx/Apache)\n";
echo "3. Update SSL certificates if needed\n";
echo "4. Clear browser cache and test the site\n";
echo "5. Update DNS records if moving to a new domain\n\n";

/**
 * Recursively replace domain in arrays and objects
 */
function domain_replace_recursive($data, $old_domain, $new_domain) {
    if (is_string($data)) {
        // Replace both http and https versions
        $data = str_replace('http://' . $old_domain, 'https://' . $new_domain, $data);
        $data = str_replace('https://' . $old_domain, 'https://' . $new_domain, $data);
        $data = str_replace('//' . $old_domain, '//' . $new_domain, $data);
        return $data;
    }
    
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = domain_replace_recursive($value, $old_domain, $new_domain);
        }
        return $data;
    }
    
    if (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = domain_replace_recursive($value, $old_domain, $new_domain);
        }
        return $data;
    }
    
    return $data;
}

echo "Done!\n";
