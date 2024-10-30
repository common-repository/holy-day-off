<?php
/**
 * @package Holy-Day-Off
 */

namespace TopwpHolyDayOff\Base;

class BaseController {

  public $plugin;
  public $plugin_path;
  public $plugin_name;
  public $plugin_slug;
  public $plugin_url;
  public $plugin_version;
  public $api_url;
  public $notices = array();
  public $notValidKey = false;

  public function __construct() {
    $this->plugin = 'Holy Day Off';
    $this->plugin_slug = 'topwp-holy-day-off';
    $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
    $this->plugin_name = plugin_basename(dirname(__FILE__, 3)) . '/topwp-holy-day-off.php';
    $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
    $this->api_url = 'https://shabbat.topwp.net';
    $this->plugin_version = '1.1.2';
  }

  public function fetchNextSchedule($city = false) {
    if (get_option('mms_city', false) || $city) {
      $city = $city ? $city : get_option('mms_city');
        $response = wp_remote_get(
            add_query_arg([
          'key'=> get_option('mms_api_key')
        ], $this->api_url. '/shabbat/'. $city)
        );

        if (is_wp_error($response)) {
            $error_code = array_key_first($response->errors);
            $error_message = $response->errors[$error_code][0];
            error_log($error_message);
            return [false, false];
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);



        if(get_option('mms_its_activate', false)){
          $result['havdalah'] = explode("+", $result['havdalah']);
          $result['havdalah'] = str_replace('T', ' ', $result['havdalah'][0]);
          $result['candles'] = explode("+", $result['candles']);
          $result['candles'] = str_replace('T', ' ', $result['candles'][0]);
          $result['candles'] = strtotime($result['candles'] ?? null);
          $result['havdalah'] = strtotime($result['havdalah'] ?? null);
        } else {
          $result['havdalah'] = $result['havdalah'];
          $result['candles'] = $result['candles'];
        }


        return [
          $result['candles'],
          $result['havdalah']
      ];
    }
  }

}
