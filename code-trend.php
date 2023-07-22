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

// set action
add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');
add_action('elementor/widgets/widgets_registered', 'register_coins');
add_action('admin_post_code_trend_form_submission', 'handle_form_submission');

// create table
register_activation_hook(__FILE__, 'code_trend_create_coins_table');
// fake data
register_activation_hook(__FILE__, 'code_trend_insert_sample_data');



/** create category */
function add_elementor_widget_categories($elements_manager)
{
	$elements_manager->add_category(
		'code-trend',
		[
			'title' => 'Code Trend',
			'icon' => 'fa fa-plug', // You can choose a suitable icon for the category
		]
	);
}

//  mount widget coins
function register_coins($widgets_manager)
{

	require_once(__DIR__ . '/widgets/coins.php');

	$widgets_manager->register_widget_type(new \Elementor_coins_Widget());
}

/** -------------------------------------------- Start data base----------------------------- */
// Create the custom table during plugin activation
function code_trend_create_coins_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'coins';

	// SQL query to create the table
	$sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        coin_name VARCHAR(100) NOT NULL,
        coin_value DECIMAL(12, 2) NOT NULL,
        PRIMARY KEY (id)
    ) $wpdb->get_charset_collate();";

	// Load the dbDelta() function to create the table
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

// Insert sample data during plugin activation
function code_trend_insert_sample_data()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'coins';

	$sample_data = array(
		array(
			'coin_name' => 'Bitcoin',
			'coin_value' => 50000.00,
		),
		array(
			'coin_name' => 'Ethereum',
			'coin_value' => 3000.00,
		),
		// Add more sample data here as needed
	);

	foreach ($sample_data as $data) {
		$wpdb->insert($table_name, $data);
	}
}
/** -------------------------------------------- End data base----------------------------- */

/** submit form */
function handle_form_submission()
{
	if (isset($_POST['coin_input'])) {
		$coin_name = sanitize_text_field($_POST['coin_input']);

		global $wpdb;
		$table_name = $wpdb->prefix . 'coin_data';

		$price_data = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT coin_value FROM $table_name WHERE coin_name = %s",
				$coin_name
			)
		);

		// Output the price data
		if ($price_data) {
			echo 'Price for ' . $coin_name . ': $' . $price_data;
		} else {
			echo 'Price data not available for ' . $coin_name;
		}
	}
}
