<?php

/**
  Plugin Name: Linklay Embed
  Plugin URI:  https://linklay.com/plugin/wp
  Description: Adds a shortcoce to embed a Linklay shoppable image in posts and pages
  Version:     1.0
  Author:      Linklay
  Author URI:  https://linklay.com/
  License: GPL2
  Requirements: PHP <= 5.4
 */
/**
  USAGE:
  - use _[linklay]linklay_hash[/linklay]_ to embed a shoppable image using the default parameters
  - use _[linklay class="your_class"]linklay_hash[/linklay]_ to wrap the shoppable image's container inside a custom CSS class 
  - use _[linklay align="left|center|right"]linklay_hash[/linklay]_ to align the shoppable image horizontally within the parent container
 */
defined('ABSPATH') or exit;

define('linklayembed_pluginpath', dirname(__FILE__) . '/');
define('linklayembed_pluginname', 'linklayembed');
require_once(linklayembed_pluginpath . linklayembed_pluginname . '.class.php');

/**
 * Returns the markup for the iframe and wrapper elements
 * @param misc $atts Shortcode attributes
 * @param string $content The Linklay ID
 * @return string The HTML displayed
 */
// example: linklay59fff9229e4fe6.97620297
function linklayembed_shortcode($atts, $content = null) {
  if (!(is_single() || is_page() || !is_admin() || !empty($content))) {
    return;
  }
  extract(shortcode_atts(['class' => '', 'align' => ''], $atts), EXTR_PREFIX_ALL, 'atts');
  $image = "https://images.linklay.com/" . $content;
  $linklay = "https://assets.linklay.com/embed_cloud.html?uuid_hash=" . $content;
  $base_url = "https://linklay.com/app/";
  $script_src = "https://assets.linklay.com/responsive.js";
  
  if ($imgsize = linklayembed_imgsize($image)) {
    list($imgwidth, $imgheight) = $imgsize;
  } else {
    $imgwidth = $imgheight = 0;
  }
  $text_align = null;
  if (!empty($atts_align)) {
    switch (strtolower($atts_align)) {
      case 'center':
        $text_align = 'text-align:center;';
        break;
      case 'right':
        $text_align = 'text-align:right;';
        break;
      case 'left':
        $text_align = 'text-align:left;';
        break;
    }
  }
  $class = !empty($atts_class) ? 'class="' . $atts_class . '" ' : '';
  $markup = <<<EOD
    <div {$class}style="{$text_align}margin:0px; padding:0px; flex: 1 1 0%; position:relative;">
      <iframe width="{$imgwidth}" height="{$imgheight}" style="overflow:hidden;margin-top:-0px;" src="{$linklay}&base_url={$base_url}" allowtransparency="true" frameborder="0">
      </iframe>
      <script src="{$script_src}" onerror="javascript:var el = this.parentElement; el.innerHTML=''; var imgEl = document.createElement( 'img' ); imgEl.src='{$image}'; imgEl.style.width='100%'; imgEl.style.maxWidth='{$imgwidth}px'; el.appendChild( imgEl );window.dispatchEvent(new Event('resize'));" >
      </script>
    </div>
EOD;
  return $markup;
}

// <div style="margin:0px; padding:0px; flex: 1;"><iframe style="width: 100%; overflow:hidden; margin-top:-0px;" width="800" height="800" src="https://assets.linklay.com/embed_cloud.html?uuid_hash=linklay5b2cf1b508e982.11932139&base_url=https://www.linklay.com/app/" allowtransparency="true" frameborder="0"></iframe><script src="https://assets.linklay.com/responsive.js" onerror="javascript:var el = this.parentElement; el.innerHTML =  ''; var imgEl = document.createElement( 'img' ); imgEl.src = 'https://images.linklay.com/linklay5b2cf1b508e982.11932139'; imgEl.style.width = '100%'; imgEl.style.maxWidth = '800px'; el.appendChild( imgEl );" ></script></div>

function linklayembed_imgsize($image) {
  return getimagesize($image);
}

new linklayembed();
