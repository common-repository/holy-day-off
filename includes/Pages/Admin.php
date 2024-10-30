<?php
/**
 * @package  Holy-Day-Off
 */
namespace TopwpHolyDayOff\Pages;

use \TopwpHolyDayOff\Api\SettingsApi;
use \TopwpHolyDayOff\Base\BaseController;
use \TopwpHolyDayOff\Api\Callbacks\AdminCallbacks;
use \TopwpHolyDayOff\Api\Callbacks\ManagerCallBacks;
use \TopwpHolyDayOff\Base\Enqueue;

/**
*
*/
class Admin extends BaseController
{
	public $settings;

	public $callbacks;

  public $callbacks_mngr;

	public $pages = array();

	public $subpages = array();

	public function register()
	{

		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();
    $this->callbacks_mngr = new ManagerCallBacks();

		$this->setPages();

		$this->setSubpages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
    add_action('admin_notices', [$this, 'admin_notice']);
	}


  public function  admin_notice() {
    $screen = get_current_screen();
    // if (!$screen || 'sl_plugin_options' !== $screen->parent_base) {
    //   return;
    // }

    $errors = get_option('mms_error', false);
    $success = get_option('mms_success', false);
    // $mms_shabbat_close = get_option( 'mms_shabbat_close', 'no' );
    update_option('mms_error', false);
    update_option('mms_success', false);

    if($errors) {
      echo '<div class="notice notice-error "><p>'. esc_attr($errors).'</p></div>';
    }

    if($success) {
      echo '<div class="notice notice-success "><p>'. esc_attr($success).'</p></div>';
    }

    // if($mms_shabbat_close == "yes") {
    //   echo '<div class="notice notice-success "><p>'. __("Checkout is close") .'</p></div>';
    // }
  }

	public function setPages()
	{
		$this->pages = array(
			array(
				'page_title' => 'Holy Day Off Plugin',
				'menu_title' => 'Holy Day Off',
				'capability' => 'manage_options',
				'menu_slug' => 'shabbat_off_plugin',
				'callback' => array( $this->callbacks, 'adminDashboard' ),
				'icon_url' => 'dashicons-store',
				'position' => 110
			)
		);
	}

	public function setSubpages()
	{
		// $this->subpages = array(
		// 	array(
		// 		'parent_slug' => 'shabbat_off_plugin',
		// 		'page_title' => 'Api Settings',
		// 		'menu_title' => 'Api Settings',
		// 		'capability' => 'manage_options',
		// 		'menu_slug' => 'mm_api_settings_shabbat',
		// 		'callback' => array( $this->callbacks, 'adminApi' )
		// 	),
		// 	array(
		// 		'parent_slug' => 'shabbat_off_plugin',
		// 		'page_title' => 'Shop Settings',
		// 		'menu_title' => 'Shop Settings',
		// 		'capability' => 'manage_options',
		// 		'menu_slug' => 'mm_shop_settings_shabbat',
		// 		'callback' => array( $this->callbacks, 'adminShop' )
		// 	),
		// );
	}

	public function setSettings()
	{

		$args = array(
			array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_api_key',
				'callback' => array( $this->callbacks_mngr, 'apiKeySanitize' )
			),
			array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_city',
				'callback' => array( $this->callbacks_mngr, 'citySanitize' )
			),
      // array(
			// 	'option_group' => 'shabbat_off_options_group',
			// 	'option_name' => 'mms_plugin_enable',
			// 	'callback' => array( $this->callbacks_mngr, 'selectSanitize' )
			// ),
      // array(
			// 	'option_group' => 'shabbat_off_options_group',
			// 	'option_name' => 'mms_cronjob_enable',
			// 	'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			// ),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_bar_enable',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_bar_close_button',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_bar_message',
				'callback' => array( $this->callbacks_mngr, 'inputSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_hide_add_to_cart',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_hide_payment_options',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_bar_background_color',
				'callback' => array( $this->callbacks_mngr, 'inputSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_bar_text_color',
				'callback' => array( $this->callbacks_mngr, 'inputSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_alert_text_color',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_button_text',
				'callback' => array( $this->callbacks_mngr, 'inputSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_modal_enable',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_modal_page',
				'callback' => array( $this->callbacks_mngr, 'pageSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_shabbat_close',
				'callback' => array( $this->callbacks_mngr, 'inputSanitize' )
			),
      array(
				'option_group' => 'shabbat_off_options_group',
				'option_name' => 'mms_shabbat_force_close',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			),
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'shabbat_off_admin_index',
				'title' => __('Activate Pro Version'),
				'callback' => array( $this->callbacks_mngr, 'adminSectionManger' ),
				'page' => 'shabbat_off_plugin_activate_setting'
      ),

      array(
				'id' => 'shabbat_off_admin_index',
				'title' => __('Settings Manager'),
				'callback' => array( $this->callbacks_mngr, 'adminSectionManger' ),
				'page' => 'shabbat_off_plugin_activate_setting2'
      ),
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			array(
				'id' => 'mms_api_key',
				'title' => __("Api Key"),
				'callback' => array( $this->callbacks_mngr, 'inputField' ),
				'page' => 'shabbat_off_plugin_activate_setting',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_api_key',
				)
			),

      array(
				'id' => 'mms_shabbat_force_close',
				'title' => __("Force Close").'<br /> <span style="font-weight:300;">This option will force close the site</span>',
				'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_shabbat_force_close',
        ),
			),

      array(
				'id' => 'mms_city',
				'title' => __("City"),
				'callback' => array( $this->callbacks_mngr, 'selectCityField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_city',
          'class' => 'ui-toggle'
				)
			),


      array(
				'id' => 'mms_shabbat_next_shabbat_time',
				'title' => __("Next Holiday"),
				'callback' => array( $this->callbacks_mngr, 'shabbatTimeField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_shabbat_next_shabbat_time',
          'class' => 'ui-toggle'
				)
			),

      // array(
			// 	'id' => 'mms_plugin_enable',
			// 	'title' => __("Enable plugin functionality"),
			// 	'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
			// 	'page' => 'shabbat_off_plugin_activate_setting2',
			// 	'section' => 'shabbat_off_admin_index',
			// 	'args' => array(
			// 		'label_for' => 'mms_plugin_enable',
			// 	)
			// ),


      // array(
			// 	'id' => ' mms_cronjob_enable',
			// 	'title' => __("Cron job"),
			// 	'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
			// 	'page' => 'shabbat_off_plugin_activate_setting2',
			// 	'section' => 'shabbat_off_admin_index',
			// 	'args' => array(
			// 		'label_for' => ' mms_cronjob_enable',
			// 	)
			// ),

      array(
				'id' => 'mms_shabbat_close',
				'title' => __("Status"),
				'callback' => array( $this->callbacks_mngr, 'statusField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_shabbat_close',
				)
			),

      array(
				'id' => 'mms_modal_enable',
				'title' => __("Redirect on shabbat"),
				'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_modal_enable',
          'class' => 'redirect-checkbox'
				)
			),

      array(
				'id' => 'mms_modal_page',
				'title' => __("Redirect Page"),
				'callback' => array( $this->callbacks_mngr, 'pageField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_modal_page',
          'class' => 'redirect-option'
				)
			),

      array(
				'id' => 'mms_alert_bar_enable',
				'title' => __("Alert Bar Enable"),
				'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_alert_bar_enable',
          'class' => 'alert-option'
				)
			),

      array(
				'id' => 'mms_alert_bar_close_button',
				'title' => __("Alert Bar Close Button"),
				'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_alert_bar_close_button',
          'class' => 'alert-option'
				)
			),

      array(
				'id' => 'mms_alert_bar_background_color',
				'title' => __("Alert Bar Background Color"),
				'callback' => array( $this->callbacks_mngr, 'colorField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_alert_bar_background_color',
          'class' => 'alert-option'
				)
			),
      array(
				'id' => 'mms_alert_bar_text_color',
				'title' => __("Alert Bar Text Color"),
				'callback' => array( $this->callbacks_mngr, 'colorField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_alert_bar_text_color',
          'class' => 'alert-option'
				)
			),

      array(
				'id' => 'mms_alert_bar_message',
				'title' => __("Alert Bar Message"),
				'callback' => array( $this->callbacks_mngr, 'inputField' ),
				'page' => 'shabbat_off_plugin_activate_setting2',
				'section' => 'shabbat_off_admin_index',
				'args' => array(
					'label_for' => 'mms_alert_bar_message',
          'class' => 'alert-option'
				)
			),
		);
        $args[] = array(
      'id' => 'mms_alert_hide_add_to_cart',
      'title' => __("Hide Add To Cart"),
      'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
      'page' => 'shabbat_off_plugin_activate_setting2',
      'section' => 'shabbat_off_admin_index',
      'args' => array(
        'label_for' => 'mms_alert_hide_add_to_cart',
        'class' => 'mm_woocommerce'
      )
      );

        $args[] = array(
      'id' => 'mms_alert_hide_payment_options',
      'title' => __("Hide Payment"),
      'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
      'page' => 'shabbat_off_plugin_activate_setting2',
      'section' => 'shabbat_off_admin_index',
      'args' => array(
        'label_for' => 'mms_alert_hide_payment_options',
        'class' => 'mm_woocommerce'
      )
      );

        $args[] = array(
      'id' => 'mms_button_text',
      'title' => __("Button Text"),
      'callback' => array( $this->callbacks_mngr, 'inputField' ),
      'page' => 'shabbat_off_plugin_activate_setting2',
      'section' => 'shabbat_off_admin_index',
      'args' => array(
        'label_for' => 'mms_button_text',
        'class' => 'mm_woocommerce'
      )
      );


		$this->settings->setFields( $args );
	}
}
