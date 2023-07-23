<?php

// action submit
add_action('admin_post_code_trend_form_submission', 'handle_form_submission');


/** -------------------------------------------- Start Submit form----------------------------- */
/** submit form */
function handle_form_submission()
{
	return print(__FILE__);
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
