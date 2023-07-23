<?php

// action submit
add_action('admin_post_code_trend_plugin', 'action');

/** action detector */
function action()
{
	$method = $_POST['method'];
	switch ($method) {
		case 'coins':
			$req = coins();
			break;
		case 'submit':
			$req = submit();
			break;
		default:
			$req = 'eror';
			break;
	}
	return var_dump($req);
}

function coins()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'coins';
	$query = "SELECT * FROM $table_name";
	$results = $wpdb->get_results($query, ARRAY_A);
	// Output the price data
	return $results;
}

/** -------------------------------------------- Start Submit form----------------------------- */
/** submit form */
function submit()
{
	if (isset($_POST['coin_input'])) {
		$coin_name = sanitize_text_field($_POST['coin_input']);

		global $wpdb;
		$table_name = $wpdb->prefix . 'coins';

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
