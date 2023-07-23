<?php

// when deActive plugin
register_deactivation_hook(__FILE__, 'code_trend_deactivate_plugin');



/** when deActive */
function code_trend_deactivate_plugin()
{
    wp_clear_scheduled_hook('code_trend_fetch_coin_data_event');
}
