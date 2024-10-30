<?php
/**
 * @package Holy-Day-Off
 */
namespace TopwpHolyDayOff\Base;

/**
 * Class Shabbat_Off_Deactivator
 * @package TopwpHolyDayOff
 */
class Deactivate {

	public static function deactivate() {
    wp_clear_scheduled_hook('we_check_shabbat_next');
    wp_cache_flush();
    if ( function_exists( 'rocket_clean_domain' ) ) {
      rocket_clean_domain();
    }
    if ( function_exists( 'run_rocket_sitemap_preload' ) ) {
      run_rocket_sitemap_preload();
    }
	}

}
