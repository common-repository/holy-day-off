<?php
/**
 * @package Holy-Day-Off
 */

namespace TopwpHolyDayOff\Base;

class Activate {

	public static function activate() {
    flush_rewrite_rules();
	}

}
