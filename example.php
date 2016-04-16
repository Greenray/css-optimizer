<?php
#
# Remove all files from CACHE directory and put your browser to http://localhost/css-optimizer/example.php
#
ini_set('display_errors', 1);
define ('DS', DIRECTORY_SEPARATOR);
define ('CACHE', 'cache'.DS);
define ('CONFIG', 'assets'.DS.'config.ini');

require_once 'css.class.php';
$html = file_get_contents('example.html');

preg_match_all("#\<link rel=\"stylesheet\" type=\"text\/css\" href=\"(.*?)\" media=\"(.*?)\" \/\>#is", $html, $matches);

foreach($matches[1] as $key => $file) {
    $CSS  = new CSS();
    $html = str_replace($matches[0][$key], '<style type="text/css">'.$CSS->compress($file).'</style>', $html);
}
echo $html;
