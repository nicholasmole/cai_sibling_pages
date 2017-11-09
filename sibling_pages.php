<?php
/**
 * Plugin Name: Sibling Pages
 * Description: A widget for showing sibling pages.
 * Version: 1.0
 * Author: James Boynton
 * Text Domain: spw-sibling-pages
 */

require_once plugin_dir_path(__FILE__) . 'src/classes/Activation.php';
require_once plugin_dir_path(__FILE__) . 'src/classes/Constants.php';
require_once plugin_dir_path(__FILE__) . 'src/classes/Helpers.php';
require_once plugin_dir_path(__FILE__) . 'src/classes/PageList.php';
require_once plugin_dir_path(__FILE__) . 'src/classes/SiblingPagesWidget.php';

use JB\SPW;
use JB\SPW\Activation;
use JB\SPW\Constants;
use JB\SPW\Helpers;
use JB\SPW\PageList;
use JB\SPW\SiblingPagesWidget;

add_action('widgets_init', function() {
  register_widget(Constants::$CLASS);
});

register_activation_hook(__FILE__, function() {
	Activation::init();
  flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function() {
  Activation::deactivate();
  flush_rewrite_rules();
});

new SiblingPagesWidget();

