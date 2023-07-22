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

// action submit
add_action('admin_post_code_trend_form_submission', 'handle_form_submission');

/** Cron Job Callback Function */
add_action('code_trend_fetch_coin_data_event', 'code_trend_fetch_coin_data');

// when active plugin
register_activation_hook(__FILE__, 'code_trend_activate_plugin');
// when deActive plugin
register_deactivation_hook(__FILE__, 'code_trend_deactivate_plugin');



/** -------------------------------------------- Start Submit form----------------------------- */
/** submit form */
function handle_form_submission()
{

	if (isset($_POST['coin_input'])) {
		$coin_name = sanitize_text_field($_POST['coin_input']);

		global $wpdb;
		$table_name = $wpdb->prefix . 'coin_data';

		$price_data = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT price_usd FROM $table_name WHERE coin_name = %s",
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
/** -------------------------------------------- End Submit form----------------------------- */


// ----------------------------- Start mount when plugin actived -------------------------------
/**  when active */
function code_trend_activate_plugin()
{
	code_trend_create_coins_table();
	// code_trend_insert_sample_data();

	code_trend_fetch_coin_data();

	// Schedule the cron job to run code_trend_fetch_coin_data() function every hour
	if (!wp_next_scheduled('code_trend_fetch_coin_data_event')) {
		wp_schedule_event(time(), 'hourly', 'code_trend_fetch_coin_data_event');
	}
}
/** when deActive */
function code_trend_deactivate_plugin()
{
	wp_clear_scheduled_hook('code_trend_fetch_coin_data_event');
}
// ----------------------------- End mount when plugin actived -------------------------------



/** -------------------------------------------- Start make category code trend and widget----------------------------- */
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
/** -------------------------------------------- End make category code trend and widget----------------------------- */




/** -------------------------------------------- Start data base maker----------------------------- */
// Create the custom table during plugin activation
function code_trend_create_coins_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'coins';

	// Set the charset and collation for the table
	$charset_collate = $wpdb->get_charset_collate();

	// If get_charset_collate() is not available, you can use a default value
	if (empty($charset_collate)) {
		$charset_collate = 'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
	}

	// SQL query to create the table
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        coin_name VARCHAR(100) NOT NULL,
        symbol VARCHAR(100) NOT NULL,
        price_usd DECIMAL(12, 2) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

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
			'price_usd' => 50000.00,
		),
		array(
			'coin_name' => 'Ethereum',
			'price_usd' => 3000.00,
		),
		// Add more sample data here as needed
	);

	foreach ($sample_data as $data) {
		$wpdb->insert($table_name, $data);
	}
}
/** -------------------------------------------- End data base maker----------------------------- */




/** -------------------------------------------- Start Api and CronJob get data from api----------------------------- */
function code_trend_fetch_coin_data()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'coins';

	// Replace 'YOUR_API_KEY' with your actual API key if required
	$api_url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';

	// Set the custom headers with the API key
	$headers = array(
		'X-CMC_PRO_API_KEY' => 'b2f20b67-bba6-46b6-ad23-6e228ef4376b',
	);

	// Fetch data from the API with custom headers
	$response = wp_remote_get($api_url, array(
		'headers' => $headers,
	));

	if (is_wp_error($response)) {
		// Error handling, if any
		return;
	}

	// Decode the JSON response
	$data = json_decode(wp_remote_retrieve_body($response), true);

	// Process the data and update the "coins" table
	if (isset($data['data']) && is_array($data['data'])) {
		foreach ($data['data'] as $coin) {
			$coin_name = sanitize_text_field($coin['name']);
			$symbol = sanitize_text_field($coin['symbol']);
			$price_usd = floatval($coin['quote']['USD']['price']);

			// Check if the coin already exists in the table based on the coin name
			$existing_coin = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE coin_name = %s", $coin_name));

			if ($existing_coin) {
				// If the coin already exists, update the price
				$wpdb->update(
					$table_name,
					array('price_usd' => $price_usd),
					array('coin_name' => $coin_name),
					array('symbol' => $symbol),
					array('%f'),
					array('%s')
				);
			} else {
				// If the coin doesn't exist, insert a new row
				$wpdb->insert(
					$table_name,
					array('coin_name' => $coin_name, 'price_usd' => $price_usd, 'symbol' => $symbol),
					array('%s', '%f')
				);
			}
		}
	}
}

/** -------------------------------------------- End Api and CronJob get data from api----------------------------- */
