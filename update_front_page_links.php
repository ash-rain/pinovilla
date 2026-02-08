<?php
$file = 'wp-content/themes/pinovilla/front-page.php';
$content = file_get_contents($file);

// Regex replacements for internal links
$content = preg_replace('/href="\/Rooms([^"]*)"/', 'href="<?php echo home_url(\'/rooms$1\'); ?>"', $content);
$content = preg_replace('/href="\/Villa([^"]*)"/', 'href="<?php echo home_url(\'/villa$1\'); ?>"', $content);
$content = preg_replace('/href="\/Restaurant([^"]*)"/', 'href="<?php echo home_url(\'/restaurant$1\'); ?>"', $content);
$content = preg_replace('/href="\/Halls([^"]*)"/', 'href="<?php echo home_url(\'/halls$1\'); ?>"', $content);
$content = preg_replace('/href="\/About([^"]*)"/', 'href="<?php echo home_url(\'/about$1\'); ?>"', $content);
$content = preg_replace('/href="\/Contact([^"]*)"/', 'href="<?php echo home_url(\'/contact$1\'); ?>"', $content);
$content = preg_replace('/href="\/Policy([^"]*)"/', 'href="<?php echo home_url(\'/policy$1\'); ?>"', $content);
$content = preg_replace('/href="\/Terms([^"]*)"/', 'href="<?php echo home_url(\'/terms$1\'); ?>"', $content);

// Home
$content = str_replace('href="/"', 'href="<?php echo home_url(\'/\'); ?>"', $content);

// Form actions
$content = str_replace('action="/Contact"', 'action="<?php echo home_url(\'/contact\'); ?>"', $content);
$content = str_replace('action="/RoomAvalability"', 'action="<?php echo home_url(\'/RoomAvalability\'); ?>"', $content);

file_put_contents($file, $content);
echo "Updated front-page.php links.\n";
?>
