<?php

class linklayembed {

  function __construct() {
    self::init();
  }

  function init() {
    add_shortcode('linklay', 'linklayembed_shortcode');
  }
}
