<?php
require_once('wp-load.php');

$url = 'https://pinovilla.com/Contact';

function fetch_content($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

function extract_inner_html($node) {
    $innerHTML = '';
    $children = $node->childNodes;
    foreach ($children as $child) {
        $innerHTML .= $node->ownerDocument->saveHTML($child);
    }
    return $innerHTML;
}

$html = fetch_content($url);

$dom = new DOMDocument();
libxml_use_internal_errors(true);
@$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
libxml_clear_errors();
$xpath = new DOMXPath($dom);

$main = $xpath->query('//main[@role="main"]')->item(0);
$content = '';
if ($main) {
    $content .= extract_inner_html($main);
}

// Check if form is in content 1
if (strpos($content, '<form') !== false) {
    echo "Form FOUND in content 1.\n";
} else {
    echo "Form NOT FOUND in content 1.\n";
}

// Part 2
$dom2 = new DOMDocument();
libxml_use_internal_errors(true);
// Hack to handle UTF-8 correctly
// Note: loadHTML expects a full document or at least valid tags.
// If content is just fragments, it might wrap them.
@$dom2->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
libxml_clear_errors();

$body = $dom2->getElementsByTagName('body')->item(0);
$final_content = '';
if ($body) {
    $final_content = extract_inner_html($body);
} else {
    $final_content = $dom2->saveHTML();
}

if (strpos($final_content, '<form') !== false) {
    echo "Form FOUND in final content.\n";
} else {
    echo "Form NOT FOUND in final content.\n";
}
?>
