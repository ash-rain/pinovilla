<?php
$file = 'wp-content/themes/pinovilla/front-page.php';
$content = file_get_contents($file);

// Regex replacements for internal links
$content = preg_replace('/href="\/Rooms([^"]*)"/', 'href="<?php echo home_url(\'/Rooms$1\'); ?>"', $content);
$content = preg_replace('/href="\/Villa([^"]*)"/', 'href="<?php echo home_url(\'/Villa$1\'); ?>"', $content);
$content = preg_replace('/href="\/Restaurant([^"]*)"/', 'href="<?php echo home_url(\'/Restaurant$1\'); ?>"', $content);
$content = preg_replace('/href="\/Halls([^"]*)"/', 'href="<?php echo home_url(\'/Halls$1\'); ?>"', $content);
$content = preg_replace('/href="\/About([^"]*)"/', 'href="<?php echo home_url(\'/About$1\'); ?>"', $content);
$content = preg_replace('/href="\/Contact([^"]*)"/', 'href="<?php echo home_url(\'/Contact$1\'); ?>"', $content);
$content = preg_replace('/href="\/Policy([^"]*)"/', 'href="<?php echo home_url(\'/Policy$1\'); ?>"', $content);
$content = preg_replace('/href="\/Terms([^"]*)"/', 'href="<?php echo home_url(\'/Terms$1\'); ?>"', $content);

// Home
$content = str_replace('href="/"', 'href="<?php echo home_url(\'/\'); ?>"', $content);

// Form actions
$content = str_replace('action="/Contact"', 'action="<?php echo home_url(\'/Contact\'); ?>"', $content);
$content = str_replace('action="/RoomAvalability"', 'action="<?php echo home_url(\'/RoomAvalability\'); ?>"', $content);

file_put_contents($file, $content);
echo "Updated front-page.php links.\n";
?>
