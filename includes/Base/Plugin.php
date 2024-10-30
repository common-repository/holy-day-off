<?php
/**
 * @package HolyDayOff
 */

namespace TopwpHolyDayOff\Base;
use \TopwpHolyDayOff\Base\BaseController;

class Plugin extends BaseController {

	public function register() {
    $hasCronjob = get_option('mms_cronjob_enable', false);
    if (!$hasCronjob) {
        add_filter('cron_schedules', function ($schedules) {
            $schedules['5min'] = array(
          'interval' => 60 * 5,
          'display' => __('Once every 5 minutes')
      );
            return $schedules;
        });
        add_action('init', array($this, 'shabbat_check_schedule'));
        add_action('we_check_shabbat_next', array($this, 'shabbat_check'));

        $opt = get_option('mms_shabbat_close', 'no');
        if ($opt == "yes") {
            update_option('mms_shabbat_close', 'yes');
        } else {
            update_option('mms_shabbat_close', 'no');
        }
        add_action('wp_footer', [$this, 'get_alert_bar']);
    } else {
      $this->disable_woocommerce_sho_function();
    }
	}


  public function shabbat_check_schedule() {
    $this->disable_woocommerce_sho_function();
    if( ! wp_next_scheduled( 'we_check_shabbat_next' ) ) {
      wp_schedule_event( time(), '5min', 'we_check_shabbat_next' );
    }
  }

  public function shabbat_check() {
      error_log("check shababt");
      $fetchNextSchedule = $this->fetchNextSchedule();
      list($startTime, $endTime) = $fetchNextSchedule;

      update_option('mms_shabbat_next_shabbat_time', $fetchNextSchedule);

      $currentTime =  strtotime(date_i18n('Y-m-d h:i:sa'));
      if ($startTime < $currentTime && $currentTime < $endTime) {
          update_option('mms_shabbat_close', 'yes');
          wp_cache_flush();
          if ( function_exists( 'rocket_clean_domain' ) ) {
            rocket_clean_domain();
          }
          if ( function_exists( 'run_rocket_sitemap_preload' ) ) {
            run_rocket_sitemap_preload();
          }
      } else {
          update_option('mms_shabbat_close', 'no');
          wp_cache_flush();
          if ( function_exists( 'rocket_clean_domain' ) ) {
            rocket_clean_domain();
          }
          if ( function_exists( 'run_rocket_sitemap_preload' ) ) {
            run_rocket_sitemap_preload();
          }
      }
  }


  public function get_current_status_of_site() {
    $status = get_option('mms_shabbat_close', 'no');
    if ($status == 'yes') {
        return true;
    }
    return false;
  }

  public function disable_woocommerce_sho_function() {
    if (!get_option('mms_shabbat_force_close', false)) {
        // if (!get_option('mms_plugin_enable', false)) {
        //     return;
        // }
        if (!$this->get_current_status_of_site()) {
            return;
        }
    }
    if ($this->is_hide_add_to_cart()) {
      remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
      remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
    }

    if($this->is_hide_payment()) {
        add_filter( 'woocommerce_cart_needs_payment', '__return_false' );
        add_filter( 'woocommerce_order_button_html',  array($this, 'replace_order_button_html') );
    }

    $this->openModal();
  }

  public function is_hide_add_to_cart() {
    if (get_option('mms_alert_hide_add_to_cart', false)) {
      return true;
    }
    return false;
  }

  public function is_hide_payment() {
    if (get_option('mms_alert_hide_payment_options', false)) {
      return true;
    }
    return false;
  }

  public function openModal(){
    if(get_option( 'mms_modal_enable', false ) && (int)get_option( 'mms_modal_page', 0 ) > 0 && !is_admin()) {
      return require_once( "$this->plugin_path/templates/popup.php" );
    }
  }


  public function replace_order_button_html($button) {
    return '<button disabled class="button alt" name="woocommerce_checkout_place_order" id="place_order">'.get_option( 'mms_button_text', 'close' ).'</button>';
  }

  public function get_alert_bar() {
    if (!get_option('mms_shabbat_force_close', false)) {
      // if (!get_option('mms_plugin_enable', false)) {
      //     return;
      // }
      if (!$this->get_current_status_of_site()) {
          return;
      }
    }
    $hide_alert_bar = get_option( 'mms_alert_bar_enable', false );
    if (!$hide_alert_bar || isset($_COOKIE['holydayoff_alertbox'])) {
      return;
    }


    $message = get_option( 'mms_alert_bar_message', "" );
    $color = get_option( 'mms_alert_bar_text_color', "#ffffff" );
    $bg_color = get_option( 'mms_alert_bar_background_color', "#000000" );
    // $alert_position = $this->options['shl_alert_bar_pos'];
    ?>
    <style>
            .zhours_alertbar {
                bottom: 0;
                z-index: 9999999999;
                position: fixed;
                width: 100%;
                color: <? echo esc_attr($color); ?>;
                background-color: <? echo esc_attr($bg_color); ?>;
                line-height: 1;
                text-align: center;
            }
        </style>
        <div class="zhours_alertbar-space"></div>
        <div class="zhours_alertbar">
            <div class="zhours_alertbar-message">
                <? echo esc_attr($message); ?>
            </div>

            <div class="zhours_alertbar-close-box">
                <span class="close-box-icon">X</span>
            </div>
        </div>
        <style>
            .zhours_alertbar {
                display: flex;
            }
            .zhours_alertbar-close-box {
                display: inline-block;
            }
            .close-box-icon {
                position: relative;
                right: 5px;
            }
            .zhours_alertbar-close-box {
              margin-left:15px;
                cursor: pointer;
                flex-grow: 1;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
            }
            .zhours_alertbar-branding {
                display: flex;
            }
            .zhours_alertbar-branding a {
                display: flex;
                align-items: center;
                color: <? echo esc_attr($color); ?>;
            }
            .zhours_alertbar-close-box img{
                cursor: pointer;
                width: 20px;
                display: inline-block !important;
            }
            .zhours_alertbar-message {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-grow: 300;
                padding: 10px 20px;
            }
            .zhours_alertbar-branding img {
                margin: 0 0.3rem;
                display: inline-block;
                background-color: #ffffff;
                padding: 2px;
                border-radius: 50%;
            }
            @media (max-width: 600px) {
                .zhours_alertbar-branding-label {
                    display: none;
                }
            }
        </style>
        <script>
            jQuery(document).ready(function ($) {
                $('.zhours_alertbar-close-box').on('click', function () {
                    $('.zhours_alertbar').fadeOut();
                    $('.zhours_alertbar-space').fadeOut();
                    var now = new Date();
                    now.setTime(now.getTime() + 60 * 60 * 24 * 2);
                    document.cookie = "holydayoff_alertbox=true; expires=" + now.toUTCString() + "; domain=<? echo esc_attr($this->get_formatted_site_url()) ?>;path=/";
                });
            });
        </script>
    <?php
  }

  public function get_formatted_site_url() {
    $url = get_site_url();
    $host = parse_url($url, PHP_URL_HOST);
    $names = explode(".", $host);

    if (count($names) == 1) {
        return $names[0];
    }

    $names = array_reverse($names);
    return $names[1] . '.' . $names[0];
  }

}
