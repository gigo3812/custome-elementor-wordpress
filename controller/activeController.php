<?php
// set action
add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');
add_action('elementor/widgets/widgets_registered', 'register_coins');

/** vue file added */
add_action('wp_enqueue_scripts', 'loadAssetJsAndCss');

/** Cron Job Callback Function */
add_action('code_trend_fetch_coin_data_event', 'code_trend_fetch_coin_data');


// ----------------------------- Start plugin actived -------------------------------
/**  when active */
function code_trend_activate_plugin()
{
	code_trend_create_coins_table();

	code_trend_fetch_coin_data();

	// Schedule the cron job to run code_trend_fetch_coin_data() function every hour
	if (!wp_next_scheduled('code_trend_fetch_coin_data_event')) {
		wp_schedule_event(time(), 'hourly', 'code_trend_fetch_coin_data_event');
	}
}
// ----------------------------- End plugin actived -------------------------------



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
	require_once plugin_dir_path(__FILE__) . '../index.php';

	$widgets_manager->register_widget_type(new \Elementor_index());
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

/** -------------------------------------------- Start Added Vue 3 ----------------------------- */
function loadAssetJsAndCss()
{
	$path = plugin_dir_url(__FILE__);
	$path = str_replace('controller/', '', $path);
	wp_enqueue_script('code-trend-vue3', $path . '/assets/js/app.js', array(), '3.0.0', true);
	wp_enqueue_style('code-trend-style',  $path . '/assets/css/public.css', array(), '1.0.0');
	wp_enqueue_style('multi-select-style',  $path . '/assets/css/multi-select.css', array(), '1.0.0');
}
/** -------------------------------------------- End Added Vue 3 ----------------------------- */
