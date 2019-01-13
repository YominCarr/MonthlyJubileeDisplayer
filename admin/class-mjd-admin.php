<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/YominCarr/
 * @since      1.0.0
 *
 * @package    Mjd
 * @subpackage Mjd/admin
 */

require_once dirname( __FILE__ ) . '/../includes/class-mjd-table.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mjd
 * @subpackage Mjd/admin
 * @author     Alexander Arth <alex.arth@googlemail.com>
 */
class Mjd_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mjd_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mjd_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mjd-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mjd_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mjd_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mjd-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function create_plugin_page() {
		add_menu_page(
			'MonthlyJubileeDisplayer Admin Page',
			'Jubilee Admin',
			'manage_options',
			'my-admin',
			array( $this, 'create_admin_page' )
		);
	}

	public function create_admin_page() {
		$table = new MjdTable();

		$action = filter_input( INPUT_POST, "action", FILTER_SANITIZE_STRING );
		if ( ! empty( $action ) ) {
			if ( $action == "insert" ) {
				$this->insertPostData( $table );
			} else if ( substr( $action, 0, 6 ) == "delete" ) {
				$id = substr( $action, 6, strlen( $action ) );

				$this->removeRow( $table, $id );
			}
		}

		echo "<br>" . $table->getStoredDataAsHTMLTableWithControls();
	}

	private function insertPostData( $table ) {
		$name      = filter_input( INPUT_POST, "name", FILTER_SANITIZE_STRING );
		$gender    = filter_input( INPUT_POST, "gender", FILTER_SANITIZE_STRING );
		$birthday  = filter_input( INPUT_POST, "birthday", FILTER_SANITIZE_STRING );
		$birthday  = date( "Y-m-d", strtotime( $birthday ) );
		$residence = filter_input( INPUT_POST, "residence", FILTER_SANITIZE_STRING );

		$ret = $table->insertEntry( $name, $gender, $birthday, $residence );

		if ( $ret ) {
			$response = "Entry inserted successfully";
		} else {
			$response = "Error inserting entry";
		}
		echo "<div id='jubileeResponse'>$response</div><br/><br/>";
	}

	private function removeRow( $table, $id ) {
		$ret = $table->removeEntry( $id );

		if ( $ret ) {
			$response = "Entry $id removed successfully";
		} else {
			$response = "Error removed entry $id";
		}
		echo "<div id='jubileeResponse'>$response</div><br/><br/>";
	}

}
