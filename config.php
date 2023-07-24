<?php
class Elementor_index extends \Elementor\Widget_Base
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
		// Get the path to the template.html file in your plugin's directory
		$template_path = plugin_dir_path(__FILE__) . '/resources/view/index.php';

		// Check if the template file exists
		if (file_exists($template_path)) {
			// Output the content of the template file
			include $template_path;
		}
	}
}
