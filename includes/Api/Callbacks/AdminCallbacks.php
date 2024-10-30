<?php
/**
 * @package  Holy-Day-Off
 */
namespace TopwpHolyDayOff\Api\Callbacks;

use TopwpHolyDayOff\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function adminDashboard()
	{
		return require_once( "$this->plugin_path/templates/admin.php" );
	}

	public function adminApi()
	{
		return require_once( "$this->plugin_path/templates/admin/api.php" );
	}

	public function adminShop()
	{
		return require_once( "$this->plugin_path/templates/admin/shop.php" );
	}

	public function OptionsGroup( $input )
	{
		return $input;
	}


	public function ApiKey()
	{
		$value = get_option( 'api_key' );
		echo '<input type="text" class="regular-text" id="api_key" name="api_key" value="' . esc_attr($value) . '" placeholder="Write your api key" />';
	}


	public function ApiKeyEnable()
	{
		$value = esc_attr( get_option( 'api_key_enable', false ) );
    if($value) {
      echo '<span class="true">True</span>';
    } else {
      echo '<span class="false">False</span>';
    }

	}


	public function FirstName()
	{
		$value =  get_option( 'first_name' );
		echo '<input type="text" class="regular-text" name="first_name" value="' . esc_attr($value) . '" placeholder="Write your First Name" />';
	}
}
