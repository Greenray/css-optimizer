<?php
/**
 * CSS prefixer and optimizer.
 *
 * Reads css code, automatically inserts browser-specific prefixes and compresses
 * the code with removing comments, two or more consecutive spaces,
 * newline characters and tabs, spaces, if a curly bracket, colon,
 * semicolon or comma is placed before or after them.
 * Replaces image references within CSS with base64_encoded data.
 * Replaces fonts (.woff, .woff2) references within CSS rule @font-face with base64_encoded data.
 * Optimizes the color settings (#00ff77 => #0f7) and property values (0px => 0, -0.5 => -.5).
 * Converts rgb(43, 92, 160), rgb(16.9%, 36.1%, 62.7%), hsl(214.9,57.6%,39.8%) to hex value (#2b5ca0).
 * There is a possibility of caching the result.
 * It is important to set the correct installation of access rights to the cache directory.
 * This program requires PHP 5.4+
 *
 * @program   CSS prefixer and optimizer.
 * @version   4.2
 * @package   Template
 * @file      css.class.php
 * @author    Victor Nabatov <greenray.spb@gmail.com>
 * @copyright (c) 2016 Victor Nabatov
 * @license   Creative Commons Attribution-ShareAlike 4.0 International Public License
 */

class CSS {

    /** @var array Configuration data */
    private $config = [];

    /** @var string CSS code that is executing */
	private $css = '';

    /**
     * The browser-specific prefixes.
     *
     * @var array
     */
    private $styles = [
        'align-content' => ['-webkit-', '-ms-', ''],
        'align-items'   => ['-webkit-', '-ms-', ''],
        'align-self'    => ['-webkit-', '-ms-', ''],

        'animation'                 => ['-webkit-', ''],
        'animation-delay'           => ['-webkit-', ''],
        'animation-direction'       => ['-webkit-', ''],
        'animation-duration'        => ['-webkit-', ''],
        'animation-fill-mode'       => ['-webkit-',''],
        'animation-iteration-count' => ['-webkit-',''],
        'animation-name'            => ['-webkit-', ''],
        'animation-play-state'      => ['-webkit-', ''],
        'animation-timing-function' => ['-webkit-', ''],

        'appearance' => ['-webkit-', '-moz-', ''],

        'backface-visibility' => ['-webkit-', ''],

        'border-end-color'    => ['-webkit-', '-moz-', ''],
        'border-end-style'    => ['-webkit-', '-moz-', ''],
        'border-end-width'    => ['-webkit-', '-moz-', ''],
        'border-start-color'  => ['-webkit-', '-moz-', ''],
        'border-start-style'  => ['-webkit-', '-moz-', ''],
        'border-start-width'  => ['-webkit-', '-moz-', ''],
        'border-image'        => ['-o-', ''],
        'border-image-outset' => ['-o-', ''],
        'border-image-repeat' => ['-o-', ''],
        'border-image-source' => ['-o-', ''],
        'border-image-width'  => ['-o-', ''],

        'box-align'            => ['-webkit-', '-moz-', '-ms-', ''],
        'box-decoration-break' => ['-webkit-', ''],
        'box-direction'        => ['-webkit-', '-moz-', '-ms-', ''],
        'box-flex'             => ['-webkit-', '-moz-', '-ms-', ''],
        'box-flex-group'       => ['-webkit-', ''],
        'box-lines'            => ['-webkit-', '-ms-', ''],
        'box-ordinal-group'    => ['-webkit-', '-moz-', '-ms-', ''],
        'box-orient'           => ['-webkit-', '-moz-', '-ms-', ''],
        'box-pack'             => ['-webkit-', '-moz-', '-ms-', ''],
        'box-reflect'          => ['-webkit-', ''],
        'box-shadow'           => ['-webkit-', ''],
        'box-sizing'           => ['-webkit-', ''],

        'column-count'      => ['-webkit-', '-moz-', ''],
        'column-fill'       => ['-moz-', ''],
        'column-gap'        => ['-webkit-', '-moz-', ''],
        'column-rule'       => ['-webkit-', '-moz-', ''],
        'column-rule-color' => ['-webkit-', '-moz-', ''],
        'column-rule-style' => ['-webkit-', '-moz-', ''],
        'column-rule-width' => ['-webkit-', '-moz-', ''],
        'column-span'       => ['-webkit-', ''],
        'column-width'      => ['-webkit-', '-moz-', ''],
        'columns'           => ['-webkit-', '-ms-', ''],

        'filter' => ['-webkit-', ''],

        'flex-basis'     => ['-webkit-', '-ms-', ''],
        'flex-direction' => ['-webkit-', '-ms-', ''],
        'flex-flow'      => ['-webkit-', '-ms-', ''],
        'flex-grow'      => ['-webkit-', '-ms-', ''],
        'flex-shrink'    => ['-webkit-', '-ms-', ''],
        'flex-wrap'      => ['-webkit-', '-ms-', ''],

        'font-kerning'           => ['-webkit-', ''],
        'font-variant-ligatures' => ['-webkit-', ''],

        'fullscreen' => ['-webkit-', '-moz-', '-ms-', ''],

        'grid'                  => ['-webkit-', ''],
        'grid-area'             => ['-webkit-', ''],
        'grid-column'           => ['-webkit-', ''],
        'grid-auto-columns'     => ['-webkit-', ''],
        'grid-auto-flow'        => ['-webkit-', ''],
        'grid-auto-rows'        => ['-webkit-', ''],
        'grid-column-end'       => ['-webkit-', ''],
        'grid-column-start'     => ['-webkit-', ''],
        'grid-row'              => ['-webkit-', ''],
        'grid-row-end'          => ['-webkit-', ''],
        'grid-row-start'        => ['-webkit-', ''],
        'grid-template-areas'   => ['-webkit-', ''],
        'grid-template-columns' => ['-webkit-', ''],
        'grid-template-rows'    => ['-webkit-', ''],

        'hyphens' => ['-webkit-', '-ms-', ''],

        'image-rendering' => ['-webkit-', '-moz-', '-ms-', '-o-', ''],

        'mask-clip'      => ['-webkit-', ''],
        'mask-composite' => ['-webkit-', ''],
        'mask-image'     => ['-webkit-', ''],
        'mask-origin'    => ['-webkit-', ''],
        'mask-size'      => ['-webkit-', ''],

        'object-fit' => ['-o-', ''],

        'opacity' => ['-khtml-', ''],

        'orient' => ['-moz-', ''],

        'perspective'        => ['-webkit-', ''],
        'perspective-origin' => ['-webkit-', '-moz-', ''],

        'ruby-position' => ['-webkit-', ''],

        'scroll-snap-coordinate'  => ['-webkit-', '-ms-', ''],
        'scroll-snap-destination' => ['-webkit-', '-ms-', ''],
        'scroll-snap-points-x'    => ['-webkit-', '-ms-', ''],
        'scroll-snap-points-y'    => ['-webkit-', '-ms-', ''],
        'shape-image-threshold'   => ['-webkit-', ''],
        'scroll-snap-type'        => ['-webkit-', '-ms-', ''],

        'tab-size'  => ['-moz-', ''],

        'text-align-last'       => ['-webkit-', '-moz-', ''],
        'text-decoration-color' => ['-webkit-', ''],
        'text-decoration-line'  => ['-webkit-', ''],
        'text-decoration-style' => ['-webkit-', ''],
        'text-justify'          => ['-webkit-', ''],
        'text-orientation'      => ['-epub-', ''],

        'transform'        => ['-webkit-', '-ms-', ''],
        'transform-origin' => ['-webkit-', '-ms-', ''],
        'transform-style'  => ['-webkit-', ''],

        'transition'                 => ['-webkit-', ''],
        'transition-delay'           => ['-webkit-', ''],
        'transition-duration'        => ['-webkit-', ''],
        'transition-property'        => ['-webkit-', ''],
        'transition-timing-function' => ['-webkit-', ''],

        'linear-gradient' => ['-webkit-', ''],
        'radial-gradient' => ['-webkit-', ''],

        'repeating-linear-gradient' => ['-webkit-', '-moz-', '-o-', ''],
        'repeating-radial-gradient' => ['-webkit-', '-moz-', '-o-', ''],

        'user-modify' => ['-webkit-', '-moz-', ''],
        'user-select' => ['-webkit-', '-moz-', '-ms-', ''],

        'writing-mode' => ['-webkit-', '-ms-', ''],

        'document'  => ['-moz-', ''],
        'keyframes' => ['-webkit-', '-moz-', '-o-', ''],
        'viewport'  => ['-ms-', '-o-', ''],

        'placeholder' => ['-webkit-input-', '-moz-', '-ms-input-', ''],
        'selection'   => ['-moz-', '']
    ];

	/**
     * Class constructor.
     *
     * @param array $options Additional options for optimizer
     */
	public function __construct($options = []) {
        $this->css    = '';
        $this->config = parse_ini_file(CONFIG);
        $this->config = array_replace($this->config, $options);
    }

    /**
     * Class main method.
     *
     * Handles directive "@import".
     * Generates browser-specific prefixes.
     * Replaces images and fonts references with base64_encoded data.
     * Optimizes the color settings (#00ff77 => #0f7) and property values (0px => 0, -0.5 => -.5).
     * Converts rgb(43, 92, 160), rgb(16.9%, 36.1%, 62.7%), hsl(214.9,57.6%,39.8%) to hex value (#2b5ca0).
     * Removes unneeded characters, see comments.
     *
     * @param  string $file CSS file
     * @return string       Prefixed and compressed CSS
     */
    public function compress($file) {
        if (is_file($file)) {
            if (!empty($this->config['cache_css'])) {
                $cached = str_replace('/', '.', $file);
                $this->css = $this->getFromCache($cached);
            }
        }
        if (empty($this->css)) {
            $pathinfo  = pathinfo($file);
            $this->css = is_file($file) ? file_get_contents($file) : $file;
            #
            # Processing rule @import
            #
            $this->import($pathinfo['dirname']);
            if (!empty($this->config['encode_colors'])) {
                $this->rgbToHex();
                $this->hslToHex();
            }
            if (!empty($this->config['encode_images'])) $this->images();
            if (!empty($this->config['encode_fonts']))  $this->fonts();
            #
            # Set the prefixes of browsers
            #
            $this->setPrefixes();
            #
            # Replace 0[type] values with 0
            #
            $this->css = preg_replace('#([^\\\\]\:|\s)0(?:em|ex|ch|rem|vw|vh|vm|vmin|cm|mm|in|px|pt|pc|%)#iS', '${1}0', $this->css);
            #
            # Replace 0 0; or 0 0 0; or 0 0 0 0; with 0
            #
            $this->css = preg_replace('#\:0(?: 0){1,3}(;|\}| \!)#', ':0$1', $this->css);
            #
            # Remove leading zeros from integer and float numbers preceded by : or a white-space
            # -0.5 to -.5; 1.050 to 1.05
            #
            $this->css = preg_replace('#((?<!\\\\)\:|\s)(\-?)0+(\.?\d+)#S', '$1$2$3', $this->css);
            #
            # Optimize hex colors: #999999 to #999; #ffdd88 to #fd8;
            #
            $this->css = preg_replace('#([^=])\#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])#i', '$1#$2$3$4$5', $this->css);
            #
            # Remove the spaces, if a curly bracket, colon, semicolon or comma is placed before or after them
            #
            $this->css = preg_replace('#\s*([\{:;,]) *#', '$1', $this->css);
            #
            # Remove newline characters and tabs
            #
            $this->css = str_replace(["\r\n", "\r", "\n", "\t"], '', $this->css);
            #
            # Remove last semicolon
            #
            $this->css = preg_replace('#\s*(\;\s*\})#', '}', $this->css);
            #
            # Remove two or more consecutive spaces
            #
            $this->css = preg_replace('# {2,}#', '', $this->css);
            #
            # Place the compiled data into cache
            # For clarity, a simple file name is used, but can be applied encoding
            #
            if (!empty($this->config['cache_css'])) {
                file_put_contents(CACHE.$cached, $this->css, LOCK_EX);
            }
        }
        return $this->css;
    }

    /**
     * Handles the rule "@import".
     * Recognizes the rules:
     * @import url("dir/style.css");
     * @import url('dir/style.css');
     * @import url(style.css);
     *
     * @param string $dir CSS file's directory
     */
    private function import($dir) {
        preg_match_all('#\@import url\(([\w\'\"\/.]*)\);#', $this->css, $match, PREG_SET_ORDER);
        if (!empty($match)) {
            foreach ($match as $key => $import) {
                $file = str_replace(['"', '\''], '', $import[1]);
                $file = file_get_contents($dir.DS.$file).PHP_EOL;
                $this->css = str_replace($import[0], $file, $this->css);
            }
        }
    }

    /** Converts rgb(43, 92, 160) or rgb(16.9%, 36.1%, 62.7%) to hex value (#2b5ca0). */
    protected function rgbToHex() {
        preg_match_all('#rgb\s*\(\s*([0-9%,\.\s]+)\s*\)#s', $this->css, $match);
        if (!empty($match[1])) {
            foreach ($match[1] as $key => $value) {
                $rgbcolors = explode(',', $value);
                $hexcolor  = '#';
                for ($i = 0; $i < 3; $i++) {
                    #
                    # Handling percentage values
                    #
                    if (strpos($rgbcolors[$i], '%') !== FALSE) {
                        $rgbcolors[$i] = substr($rgbcolors[$i], 0, -1);
                        $rgbcolors[$i] = (int) (256 * ($rgbcolors[$i] / 100));
                        $hexcolor .= str_pad(dechex($rgbcolors[$i]),  2, '0', STR_PAD_LEFT);
					} else {
                        #
                        # Process values in integers
                        #
                        $color = round($rgbcolors[$i]);
                        if ($color < 16) {
                            $hexcolor .= '0';
                        }
                        $hexcolor .= dechex($color);
                    }
                }
                $this->css = str_replace($match[0][$key], $hexcolor, $this->css);
            }
        }
    }

    /** Converts hsl(214.9,57.6%,39.8%) to hex value (#2b5ca0). */
    private function hslToHex() {
        preg_match_all('#hsl\s*\(\s*([0-9%,\.\s]+)\s*\)#s', $this->css, $match);
        foreach ($match[1] as $key => $hls) {
            $values = explode(',', str_replace('%', '', $hls));
            $h = floatval($values[0]);
            $s = floatval($values[1]);
            $l = floatval($values[2]);
            $h = ((($h % 360) + 360) % 360) / 360;
            $s = min(max($s, 0), 100) / 100;
            $l = min(max($l, 0), 100) / 100;
            if ($s === 0) {
                $red = $green = $blue = intval(floor(floatval(255 * $l) + 0.5), 10);
             } else {
                $v2 = $l < 0.5 ? $l * (1 + $s) : ($l + $s) - ($s * $l);
                $v1 = 2 * $l - $v2;
                $red   = intval(floor(floatval(255 * $this->toRgb($v1, $v2, $h + (1/3))) + 0.5), 10);
                $green = intval(floor(floatval(255 * $this->toRgb($v1, $v2, $h)) + 0.5), 10);
                $blue  = intval(floor(floatval(255 * $this->toRgb($v1, $v2, $h - (1/3))) + 0.5), 10);
            }
            $hexcolor = '#'.str_pad(dechex(round($red)), 2, '0', STR_PAD_LEFT).str_pad(dechex(round($green)), 2, '0', STR_PAD_LEFT).str_pad(dechex(round($blue)), 2, '0', STR_PAD_LEFT);
            $this->css = str_replace($match[0][$key], $hexcolor, $this->css);
        }
    }

    /**
     * Helper function to convert hsl to rgb.
     *
     * @param  integer $v1  Helper value
     * @param  integer $v2  Helper value
     * @param  integer $hue Value of hue
     * @return integer      The result
     */
    private function toRgb($v1, $v2, $hue) {
        $hue = $hue < 0 ? $hue + 1 : ($hue > 1 ? $hue - 1 : $hue);
        if ($hue * 6 < 1) return $v1 + ($v2 - $v1) * 6 * $hue;
        if ($hue * 2 < 1) return $v2;
        if ($hue * 3 < 2) return $v1 + ($v2 - $v1) * ((2/3) - $hue) * 6;
        return $v1;
    }

    /** Replace images references with base64_encoded data. */
    private function images() {
        preg_match_all('#background[\:|\-image\:]+[\s\w]*url\(([\w\'\"\/\.\-]+)\)#', $this->css, $match, PREG_SET_ORDER);
        if (!empty($match)) {
            foreach ($match as $key => $import) {
                $this->encode($import[1], 'image');
            }
        }
    }

    /** Replace fonts .woff and .woff2 references with base64_encoded data. */
    private function fonts() {
        preg_match_all('#[src:|| |\t]*url\(([\w\/\.\-\']+)([\#\w\?]*)\'\)#', $this->css, $match, PREG_SET_ORDER);
        if (!empty($match)) {
            foreach ($match as $key => $import) {
                $this->encode($import[1], 'font');
            }
        }
    }

    /**
     * Encodes image or font.
     *
     * @param array  $file Name of image or font file
     * @param string $mode What to encode: image or font
     */
    private function encode($file, $mode) {
        $file  = str_replace(['../', '\'', '"'], '', $file);
        $type  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (file_exists($file)) {
            $$mode = file_get_contents($file);
            if ($mode === 'image')
                 $this->css = str_replace($file, 'data:image/'.$type.';base64,'.base64_encode($$mode), $this->css);
            else $this->css = str_replace($file, 'data:application/font-'.$type.';charset=utf-8;base64,'.base64_encode($$mode), $this->css);
        }
    }

    /** Generates browser-specific prefixes. */
    private function setPrefixes() {
        #
        # Remove comments
        #
        $this->css = preg_replace('#(\/\*).*?(\*\/)#s', '', $this->css);
        $values    = [];
        foreach ($this->styles as $property => $styles) {
            preg_match('/[^-\{]'.$property.'/s', $this->css, $result);
            if (!empty($result)) {
                $values[] = array_unique($result);
            } else {
                #
                # Remove rules unnecessary for further work
                #
                unset($this->styles[$property]);
            }
        }
        $rules  = [];
        $pseudo = [];
        foreach ($values as $value) {
            $pos = strpos($value[0], '@');
            if ($pos === 0) {
                $rules[] = $value[0];
            }
            $pos = strpos($value[0], ':');
            if ($pos === 0) {
                $pseudo[] = $value[0];
                continue;
            }
            $value = trim($value[0]);
            #
            # Search properties from $this->styles list
            #
            preg_match_all('#'.$value.':[a-zA-Z0-9\.\-\#|\d\s][^\}]+?;|[a-zA-Z\-]+: '.$value.'[\S+].+?;#s', $this->css, $keys);
            foreach ($keys[0] as $property) {
                foreach ($this->styles as $style => $prefixes) {
                    if ($style === $value) {
                        $result = '';
                        foreach ($prefixes as $match) {
                            $pos = strpos($property, $value);
                            if ($pos === 0) {
                                $parts = explode(':', $property);
                                $parts[1] = ': '.$parts[1];
                                $parts[0] = $match.$parts[0];
                                $result  .= implode($parts);
                            } else {
                                $parts = explode(':', $property);
                                $parts[0] = $parts[0].':';
                                $parts[1] = trim($parts[1]);
                                $parts[1] = $match.$parts[1];
                                $result  .= implode($parts);
                            }
                        }
                        $this->css = str_replace($property, $result, $this->css);
                    }
                }
            }
        }
        $this->setPrefixesForRules($rules);
        $this->setPrefixesForPseudo($pseudo);
    }

    /**
     * Generates browser-specific prefixes for rules.
     *
     * @param array $rules Array of founded rules in css file
     */
    private function setPrefixesForRules($rules) {
        foreach ($rules as $key => $rule) {
            $rule = str_replace('@', '', $rule);
            preg_match_all('#'.$rule.'[a-zA-Z0-9_\s\{\}\-\;\:\.\"\%\(\)\*\#]+#s', $this->css, $keys);
            foreach ($keys[0] as $property) {
                foreach ($this->styles as $style => $prefixes) {
                    if ($style === $rule) {
                        $result = '';
                        foreach ($prefixes as $match) {
                            $pos = strpos($property, $rule);
                            if ($pos === 0) {
                                $parts    = explode(':', $property);
                                $parts[0] = '@'.$match.$parts[0];
                                $result  .= implode(':', $parts);
                            }
                        }
                        $this->css = str_replace('@'.$property, $result, $this->css);
                    }
                }
            }
        }
    }

    /**
     * Generates browser-specific prefixes for pseudoelements.
     *
     * @param array $pseudo Array of founded rules in css file
     */
    private function setPrefixesForPseudo($pseudo) {
        foreach ($pseudo as $key => $rule) {
            $rule = str_replace(':', '', $rule);
            preg_match_all('#[a-z0-9_\[\]\"\=\:]+'.$rule.'[a-zA-Z0-9_\s\{\-\;\:\.\"\%\(\)\*\#]+\}#s', $this->css, $keys);
            foreach ($keys[0] as $property) {
                foreach ($this->styles as $style => $prefixes) {
                    if ($style === $rule) {
                        $result = '';
                        foreach ($prefixes as $match) {
                            $pos = strpos($property, $rule);
                            if ($pos !== FALSE) {
                                $result .= str_replace($rule, $match.$rule, $property);
                            }
                        }
                        $this->css = str_replace($property, $result, $this->css);
                    }
                }
            }
        }
    }

    /**
	 * Gets a compiled file from the cache.
     *
     * @param  string $file CSS file
     * @return mixed        Data from cache or FALSE
	 */
	private function getFromCache($file) {
        return (file_exists(CACHE.$file)) ? file_get_contents(CACHE.$file) : '';
	}
}
