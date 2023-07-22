<?php
class Elementor_coins_Widget extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'elementor-coins-widget';
	}

	public function get_title()
	{
		return __('Coins', 'code-trend');
	}

	public function get_icon()
	{
		return 'eicon-code';
	}

	public function get_categories()
	{
		return ['code-trend'];
	}

	public function get_keywords()
	{
		return ['code trend', 'coins'];
	}

	protected function render()
	{

		global $wpdb;
		$table_name = $wpdb->prefix . 'coins';

		$coins = $wpdb->get_results(
			"SELECT * FROM $table_name"
		);


?>
		<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
			<select name="coin" id="">
				<?php
				foreach ($coins as $coin) {
					echo '<option value=' . $coin->coin_name . '>' . $coin->coin_name . '</option>';
				}

				?>
			</select>
			<input type="text" name="coin_value" placeholder="Enter the coin name...">
			<button type="submit">Get Price</button>
			<input type="hidden" name="action" value="code_trend_form_submission">
		</form>
<?php
	}
}
