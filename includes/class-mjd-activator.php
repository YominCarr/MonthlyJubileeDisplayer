<?php

require_once dirname( __FILE__ ) . '/class-mjd-table.php';

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/YominCarr/
 * @since      1.0.0
 *
 * @package    Mjd
 * @subpackage Mjd/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mjd
 * @subpackage Mjd/includes
 * @author     Alexander Arth <alex.arth@googlemail.com>
 */
class Mjd_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$table = new MjdTable();
		$table->install();
	}

}
