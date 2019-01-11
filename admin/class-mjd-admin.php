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
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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

	/**
	 * Add options page
	 */
	public function create_plugin_page() {
		add_options_page(
			'MonthlyJubileeDisplayer Plugin Settings',
			'MonthlyJubileeDisplayer Settings',
			'manage_options',
			__FILE__,
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'jubilee_options' );
		?>
		<div class="wrap">
			<h1>MonthlyJubileeDisplayer Plugin Settings</h1>
			<form method="post" action="<?php echo __FILE__; ?>">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'jubilee_options_group' );
				do_settings_sections( 'my-setting-admin' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			'jubilee_options_group',
			'jubilee_options',
			array( $this, 'sanitize' )
		);

		add_settings_section(
			'setting_section_query',
			'Query options',
			array( $this, 'print_section_info' ),
			'my-setting-admin'
		);

		add_settings_field(
			'min_age',
			'Minimum Age',
			array( $this, 'min_age_callback' ),
			'my-setting-admin',
			'setting_section_query'
		);

		add_settings_section(
			'setting_section_text',
			'Textblocks for display',
			array( $this, 'print_section_info' ),
			'my-setting-admin'
		);

		add_settings_field(
			'textblock',
			'Text to display',
			array( $this, 'text_callback' ),
			'my-setting-admin',
			'setting_section_text'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();
		if( isset( $input['id_number'] ) )
			$new_input['id_number'] = absint( $input['id_number'] );

		if( isset( $input['title'] ) )
			$new_input['title'] = sanitize_text_field( $input['title'] );

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Enter your settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function min_age_callback()
	{
		printf(
			'<input type="text" id="min_age" name="jubilee_options[min_age]" value="%s" />',
			isset( $this->options['min_age'] ) ? esc_attr( $this->options['min_age']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function text_callback()
	{
		printf(
			'<input type="text" id="textblock" name="jubilee_options[textblock]" value="%s" />',
			isset( $this->options['textblock'] ) ? esc_attr( $this->options['textblock']) : ''
		);
	}

}
