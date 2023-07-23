<?php
/*
Plugin Name: Code Trend
Plugin URI: Your Plugin URL
Description: Custom Elementor widget to display prices in the Code Trend category.
Version: 1.0.0
Author: Your Name
Author URI: Your Website URL
Text Domain: code-trend
*/
// when active plugin
register_activation_hook(__FILE__, 'code_trend_activate_plugin');



require_once plugin_dir_path(__FILE__) . 'controller/allFunctionController.php';
require_once plugin_dir_path(__FILE__) . 'controller/activeController.php';
require_once plugin_dir_path(__FILE__) . 'controller/inactiveController.php';
