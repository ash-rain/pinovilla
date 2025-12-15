<?php
require_once('wp-load.php');

$form_shortcode = '[contact-form-7 id="47" title="Contact Form Main"]';

function replace_form_with_shortcode($page_slug, $shortcode) {
    $page = get_page_by_path($page_slug);
    if (!$page) {
        echo "Page $page_slug not found.\n";
        return;
    }

    $content = $page->post_content;
    
    // Use DOMDocument to find and replace the form
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    // Hack for UTF-8
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    
    $xpath = new DOMXPath($dom);
    $forms = $xpath->query('//form');
    
    if ($forms->length > 0) {
        foreach ($forms as $form) {
            // Check if it's the contact form (avoid search forms etc if any)
            // The contact form usually has inputs like 'FormName' or 'your-name'
            // Or we can just replace the first form found in the main content area.
            // In Contact page, it has id="contact_form".
            // In About page, it has action="/Contact".
            
            $id = $form->getAttribute('id');
            $action = $form->getAttribute('action');
            
            if ($id === 'contact_form' || $action === '/Contact' || strpos($action, 'Contact') !== false) {
                // Create a text node with the shortcode
                $newNode = $dom->createTextNode($shortcode);
                $form->parentNode->replaceChild($newNode, $form);
                echo "Replaced form in $page_slug.\n";
            }
        }
        
        $new_content = $dom->saveHTML();
        // saveHTML adds wrappers if not careful, but with NOIMPLIED it should be fine.
        // However, it might decode entities.
        
        // Let's try a simpler regex approach if DOM fails or is too complex with encoding
        // Regex is risky but for this specific task might be cleaner if the HTML is consistent.
    } else {
        echo "No form found in $page_slug.\n";
    }
    
    // Let's stick with DOM for now, but if it fails I'll use regex.
    // Actually, saveHTML might wrap things in <p> or change formatting.
    // Let's try regex first as it's less invasive for just replacing a block.
    
    // Regex to match <form ...> ... </form>
    // We need to be careful about nested tags.
    // But the form shouldn't have nested forms.
    
    $pattern = '/<form[^>]*>.*?<\/form>/is';
    // This is greedy.
    
    // Let's refine: find form with specific attributes
    // Contact page: <form id="contact_form" ...>
    if ($page_slug === 'contact') {
        $pattern = '/<form[^>]*id="contact_form"[^>]*>.*?<\/form>/is';
    } elseif ($page_slug === 'about') {
        $pattern = '/<form[^>]*action="\/Contact"[^>]*>.*?<\/form>/is';
    }
    
    if (preg_match($pattern, $content)) {
        $new_content_regex = preg_replace($pattern, $shortcode, $content);
        if ($new_content_regex !== $content) {
             $page->post_content = $new_content_regex;
             wp_update_post($page);
             echo "Updated $page_slug using regex.\n";
             return;
        }
    }
    
    echo "Regex failed for $page_slug. Trying DOM...\n";
    
    // If regex failed, maybe attributes didn't match exactly.
    // Fallback to DOM logic above (but I need to implement the save and update)
    if ($forms->length > 0) {
         // ... (DOM logic from above)
         // Since I didn't finish the DOM save part, let's just rely on regex for now
         // and print if it fails.
         echo "DOM replacement not fully implemented, please check regex.\n";
    }
}

replace_form_with_shortcode('contact', $form_shortcode);
replace_form_with_shortcode('about', $form_shortcode);

?>
