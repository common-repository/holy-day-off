<?php

/**
 *
 *
 * @link              https://topwp.net
 * @since             1.0.0
 * @package           Holy Day Off
 *
 * @wordpress-plugin
 * Plugin Name:       Holy Day Off
 * Plugin URI:        https://topwp.net
 * Description:       Automatically close your Woocommerce website in shabbat times, based on Jewish calendar data.
 * Version:           1.1.5
 * Author:            Ran Tayar & Dor Meljon
 * Author URI:        https://media-maven.co.il
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mm_holiday_off
 *
 *
 */
if (!defined('ABSPATH')) {
	die;
}

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}

function deactivate_shabbat_off()
{
	TopwpHolyDayOff\Base\Deactivate::deactivate();
}

function activate_shabbat_off()
{
	TopwpHolyDayOff\Base\Activate::activate();
	do_action('activated_shabbat_off');
}

function uninstall_shabbat_off()
{
	TopwpHolyDayOff\Base\Uninstall::uninstall();
}

register_activation_hook(__FILE__, 'activate_shabbat_off');
register_deactivation_hook(__FILE__, 'deactivate_shabbat_off');
register_uninstall_hook(__FILE__, 'uninstall_shabbat_off');


require_once dirname(__FILE__) . '/includes/Init.php';
