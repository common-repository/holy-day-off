<?php
/**
 * @package Holy-Day-Off
 */

 namespace TopwpHolyDayOff\Base;
 use \TopwpHolyDayOff\Base\BaseController;

 class SettingsLinks extends BaseController {

  public function register() {
    add_filter( "plugin_action_links_$this->plugin_name" , array($this, 'settings_links') );
  }

  public function settings_links($links) {

    array_push($links, '<a href="admin.php?page=shabbat_off_plugin">'. __("Settings").'</a>');
    array_push($links, sprintf(
      '<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
      esc_url(
        network_admin_url(
          'plugin-install.php?tab=plugin-information&plugin=' . $this->plugin_name .
          '&TB_iframe=true'
        )
      ),
      /* translators: %s: Plugin name. */
      esc_attr( sprintf( __( 'More information about %s' ), $this->plugin_name  ) ),
      esc_attr( $this->plugin_name  ),
      __( 'View details' )
    ));
    // array_push($links, '<a href="admin.php?page=mm_shabbat_off_plugin">'. __("Register").'</a>');

    return $links;
  }
 }
