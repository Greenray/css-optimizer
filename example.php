<?php
#
# Remove all files from CACHE directory and put your browser to http://localhost/css-optimizer/example.php
#
ini_set('display_errors', 1);
define ('DS', DIRECTORY_SEPARATOR);
define ('CACHE',  'cache'.DS);
define ('CONFIG', 'assets'.DS.'config.ini');

require_once 'css.class.php';
$html = file_get_contents('example.html');

preg_match_all("#\<link rel=\"stylesheet\" type=\"text\/css\" href=\"(.*?)\" media=\"(.*?)\" \/\>#is", $html, $match, PREG_SET_ORDER);

foreach($match as $key => $css) {
    $CSS  = new CSS();
    $html = str_replace($css[0], '<style type="text/css">'.$CSS->compress($css[1]).'</style>', $html);
}

preg_match_all("#\s*<style\b[^>]*?>\s*<!--\s*([\s\S]*?)\s*-->\s*<\/style>\s*#i", $html, $match, PREG_SET_ORDER);
foreach($match as $key => $css) {
    $CSS  = new CSS(['cache_css' => FALSE]);
    $html = str_replace($css[0], '<style type="text/css">'.$CSS->compress($css[1]).'</style>', $html);
}

echo $html;
