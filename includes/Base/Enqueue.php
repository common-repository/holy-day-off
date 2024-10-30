<?php
/**
 * @package Holy-Day-Off
 */

namespace TopwpHolyDayOff\Base;
use \TopwpHolyDayOff\Base\BaseController;


class Enqueue extends BaseController {
	public function register() {
		add_action('admin_enqueue_scripts', array ( $this, 'enqueue' ) );
	}

	public function enqueue( ) {
		wp_enqueue_style('shabbatoffstyle',  "$this->plugin_url/assets/style.css");
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style('select2Style',  "$this->plugin_url/assets/select2.min.css");


    wp_enqueue_script( 'my-script-select2', "$this->plugin_url/assets/select2.min.js", array( 'jquery' ), true, null );
		wp_enqueue_script('shabbatoffscript',  "$this->plugin_url/assets/script.js", array('wp-color-picker'), true, null);
	}
}
