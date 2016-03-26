<?php
#
# Remove all files from cache/ directory and put your browser to http://localhost/css-optimizer/example.php
#
ini_set('display_errors', 1);
define ('DS', DIRECTORY_SEPARATOR);
define ('CACHE', 'cache'.DS);

require_once 'css.class.php';
$html = file_get_contents('example.html');

preg_match_all("#\<link rel=\"stylesheet\" type=\"text\/css\" href=\"(.*?)\" media=\"(.*?)\" \/\>#is", $html, $matches);

$cache = TRUE;
foreach($matches[1] as $key => $file) {
    $CSS  = new CSS($cache);
    $html = str_replace($matches[0][$key], '<style type="text/css">'.$CSS->compress($file).'</style>', $html);
}
echo $html;
