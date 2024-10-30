<?php
/**
 * @package HolyDayOff
 */
namespace TopwpHolyDayOff;

final class TopwpHolyDayOff {

	/**
	 * Store All the classes inside an array
	 * @return array  Full list of classes
	 */
	public static function get_services() {
		return array(
      Pages\Admin::class,
			Base\Enqueue::class,
      Base\SettingsLinks::class,
      Base\Plugin::class,
		);
	}

	/**
	 * Loop through the classes, initialize them
	 * @return
	 */
	public static function register_services() {
		foreach ( self::get_services() as $class) {
			$service = self::instantiate($class);
      if( method_exists($service, 'register')) {
        $service->register();
      }
		}
	}

	/**
	 * Initialize the class
	 * @param class $class  class from the services array
	 *
	 * @return class instance  new instance of the class
	 */
	private static function instantiate($class) {
		return new $class();
	}

}

TopwpHolyDayOff::register_services();
