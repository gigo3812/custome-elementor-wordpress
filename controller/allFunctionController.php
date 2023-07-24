<?php

add_action('admin_post_code_trend_action', 'action');

/** action detector */
function action()
{
	$method = $_POST['method'];
	switch ($method) {
		case 'getCoins':
			$res = getCoins();
			break;
		case 'submit':
			$res = submit();
			break;
		default:
			$res = [
				'status' => 500,
				'message' => 'no command found'
			];
	}
    wp_send_json($res, $res['status']);
}

function getCoins()
{
	try {
		global $wpdb;
		$table_name = $wpdb->prefix . 'coins';
		$query = "SELECT * FROM $table_name";
		$results = $wpdb->get_results($query, ARRAY_A);
		return [
			'status' => 200,
			'message' => 'success',
			'coins' => $results
		];
	} catch (\Throwable $th) {
		return [
			'status' => 500,
			'message' => 'faild',
			'error' => $th
		];
	}
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
