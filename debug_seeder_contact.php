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
echo "Fetched HTML length: " . strlen($html) . "\n";

$dom = new DOMDocument();
libxml_use_internal_errors(true);
@$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
libxml_clear_errors();
$xpath = new DOMXPath($dom);

$main = $xpath->query('//main[@role="main"]')->item(0);
if ($main) {
    echo "Found main tag.\n";
    $content = extract_inner_html($main);
    echo "Extracted content length: " . strlen($content) . "\n";
    
    if (strpos($content, '<form') !== false) {
        echo "Form FOUND in extracted content.\n";
    } else {
        echo "Form NOT FOUND in extracted content.\n";
    }
} else {
    echo "Main tag NOT found.\n";
}
?>
