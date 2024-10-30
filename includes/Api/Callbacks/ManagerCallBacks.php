<?php
/**
 * @package  mm-shabbat-off
 */
namespace TopwpHolyDayOff\Api\Callbacks;

use TopwpHolyDayOff\Base\BaseController;

class ManagerCallBacks extends BaseController
{

  public function pageSanitize($input) {
    $enable = get_option('mms_modal_enable', false);
    $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    if($enable && (int)$input < 1)  {
      add_settings_error(
        'mms_api_key',
        esc_attr( 'settings_updated' ),
        'please choose page!',
        'error'
      );
    } else {
      return $input;
    }
  }

	public function inputSanitize($input)
	{
    return filter_var($input, FILTER_SANITIZE_STRING);
  }

  public function citySanitize($input) {
    $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);

    if($input) {
      $up = $this->fetchNextSchedule($input);
      update_option('mms_shabbat_next_shabbat_time', $up);
    }
    return $input;
  }

  public function apiKeySanitize($input)
	{
    $key = get_option('mms_its_the_key', '_');
    if(
      isset($input) &&
      $key != $input
    ){
        $response = wp_remote_get(add_query_arg([], $this->api_url. '/keys/validate/'.$input));

        if (is_wp_error($response)) {
            $error_code = array_key_first($response->errors);
            $error_message = $response->errors[$error_code][0];
            error_log($error_message);
            update_option('mms_its_activate', false);
            update_option('mms_its_the_key', '_');
            update_option('mms_error', __('Error in validating license key'));
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);
        if ($result["success"]) {
            $response = wp_remote_get(add_query_arg([], $this->api_url. '/keys/activate/'.$input));
            $result2 = json_decode(wp_remote_retrieve_body($response), true);
            if($result2["success"]) {
              update_option('mms_its_activate', true);
              update_option('mms_its_the_key', $input);
              update_option('mms_success', __('License key activated!'));
            }else {
              update_option('mms_its_activate', false);
              update_option('mms_its_the_key', '_');
              update_option('mms_error', __('License key is already activated! If license key isn\'t activated by you. Please contact plugin Author.'));
            }
        } else {
            update_option('mms_its_activate', false);
            update_option('mms_its_the_key', '_');
            update_option('mms_error', __('Error in validating license key'));
        }

    }

    return filter_var($input, FILTER_SANITIZE_STRING);
  }



  public function pageField($args) {
    $label = $args['label_for'];
    $pageval = get_option($label);
    $pages = get_pages('hide_empty=0');
    echo "<select name=\"".esc_attr($label)."\" class=\"js-select-2\" name=\"state\">";
    echo '<option value="-1">'.__("Choose A Page").'</option>';
    foreach ( $pages as $page ) {
      if ($page->post_title) {
          if ($page->ID == $pageval) {
              echo "<option value=\"$page->ID\" selected>".esc_attr($page->post_title)."</option>";
          } else {
              echo "<option value=\"$page->ID\">".esc_attr($page->post_title)."</option>";
          }
      }
    }
    echo "</select>";
    // die();
  }

  public function selectSanitize($input)
	{
    return filter_var($input, FILTER_SANITIZE_STRING);
  }

  public function checkboxSanitize($input)
	{
    // return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    return ( isset($input) ? true : false);
	}

  public function adminSectionManger() {
    return 'Manage the section and Features of this Plugin';
  }

  public function shabbatTimeField($args) {
    $label = $args['label_for'];
    $value = get_option($label);
    $activate = get_option('mms_its_activate', false);

    if(isset($value[0]) && isset($value[1])) {
      if($activate) {
        echo date_i18n('l, jS F Y, H:i:s', esc_attr($value[0]));
      echo "<br />";
      echo date_i18n('l, jS F Y, H:i:s', esc_attr($value[1]));
      } else {
        echo esc_attr($value[0]);
        echo "<br />";
        echo esc_attr($value[1]);
      }
    } else {
      echo __("Please Choose city");
    }
  }

  public function inputField($args) {
    $label = $args['label_for'];
    $value = get_option($label);
    $activate = get_option('mms_its_activate', false);
    $placeholder = $args['placeholder'] ?? null;

    if($activate || $label==="mms_api_key") {
      echo "<input type=\"text\" class=\"regular-text\" name=\"".esc_attr($label)."\" value=\"".esc_attr($value)."\" placeholder=\"".esc_attr($placeholder)."\" />";
    } else {
      echo "<input type=\"text\" class=\"regular-text\" disabled placeholder=\"".esc_attr($placeholder)."\" />";
    }
  }

  public function colorField($args) {
    $label = $args['label_for'];
    $value = get_option($label);
    $activate = get_option('mms_its_activate', false);

    if($activate) {
      echo "<input type=\"text\" name=\"".esc_attr($label)."\" value=\"".esc_attr($value)."\" class=\"my-color-field\" data-default-color=\"".esc_attr($value)."\" />";
    } else {
      echo "<input type=\"text\" class=\"my-color-field\" disabled />";
    }
  }

  public function statusField($args) {
    $label = $args['label_for'];
    $value = get_option($label, 'no');
    if(get_option("mms_shabbat_force_close")) {
      echo '<div class="status"><span class="yes">'.__("Shop is closed").'</span></div>';
    } else {
      echo '<div class="status"><span class="'.esc_attr($value).'">';
      if($value == "yes") {
        echo __("Shop is closed");
      } else {
        echo __("Shop is open");
      }
      echo "</span></div>";
    }
  }

  public function checkboxField($args) {
    $label = $args['label_for'];
    $activate = get_option('mms_its_activate', false);

    if(isset($args['class'])) {
      $classes = $args['class'];
    } else {
      $classes = '';
    }
    $checkbox = get_option($label);
    if($activate) {
      echo "<input  type=\"checkbox\" name=\"".esc_attr($label)."\" class=\"$classes\" value=\"1\" ". (esc_attr($checkbox) ? 'checked' : '') ." />";
    } else {
      echo "<input type=\"checkbox\" class=\"$classes\" disabled />";
    }
  }

  public function selectCityField($args) {
    $response = wp_remote_get(add_query_arg([], $this->api_url. '/shabbat/cities'));
    $label = esc_attr($args['label_for']);
    $val= get_option($label);

    if (is_wp_error($response)) {
      $error_code = array_key_first( $response->errors );
      $error_message = $response->errors[$error_code][0];
      error_log($error_message);
    } else {
      $result = json_decode(wp_remote_retrieve_body($response), true);
      if($result["success"]) {
        echo "<select name=\"".esc_attr($label)."\" class=\"js-select-2\" name=\"state\">";
        foreach ($result["cities"] as $key => $value) {
          if($key == $val) {
            echo "<option value=\"".esc_attr($key)."\" selected>".esc_attr($value)."</option>";
          } else {
            echo "<option value=\"".esc_attr($key)."\">".esc_attr($value)."</option>";
          }
        }
        echo "</select>";
      }
    }


  }
}
