CSS parser and optimizer.
=========================

Reads css code, automatically inserts browser-specific prefixes and compresses the code.
There is a possibility of caching the result.

It is important to follow the correct installation of access rights to the data directory (the cache).

## Feachures

* Handles css @import directive thus multiple files can be combined into one.
* Automatically inserts browser-specific prefixes for defined css properties.
* Replaces image references within CSS with base64_encoded data.
* Removes two or more consecutive spaces.
* Removes the spaces, if a curly bracket, colon, semicolon or comma is placed before or after them.
* Removes newline characters and tabs.
* Writes and reads the compiled data into/from cache.

## Requirements

This program requires PHP 5.4+

## Example

    /* File styles.css */

    input[type="search"]::placeholder {
        color: #ffdd55;
    }
    .some_class {
        background: #1e5799;
        background: linear-gradient(to bottom, #1e5799 0%, #2989d8 50%, #207cca 51%, #7db9e8 100%);

        border: #000 solid 1px;
        border-radius: 5px;
        box-sizing: content-box;

        margin: 0em 5px;  /* This will be replaced with 0 ( 0em; )*/
        padding: 5px 0px; /* This will be replaced with 0 ( 0px; )*/

        transition: height .25s ease .1s;
    }

## Result

input[type="search"]::-webkit-input-placeholder{
color:#fd5;
}
input[type="search"]::-moz-placeholder{
color:#fd5;
}
input[type="search"]::-ms-input-placeholder{
color:#fd5;
}
input[type="search"]::placeholder{
color:#fd5;
}

.some_class{
background:#1e5799;
background:-webkit-linear-gradient(to bottom,#1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
background:-moz-linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
background:-o-linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
background:linear-gradient(to bottom,#1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
borrder:#000 solid 1px;
-webkit-border-radius:5px;
-moz-border-radius:5px;
border-radius:5px;
-webkit-box-sizing:content-box;
-moz-box-sizing:content-box;
box-sizing:content-box;
margin:0 5px;
padding:5px 0;
-webkit-transition:height .25s ease .1s;
-moz-transition:height .25s ease .1s;
-o-transition:height .25s ease .1s;
transition:height .25s ease .1s;
}

After finishing (removing newlines) the data file will be prepared, placed in one line.

The original code: (https://github.com/Greenray/css-optimizer).
Copyright (C) 2016 Victor Nabatov <greenray.spb@gmail.com>