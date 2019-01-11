<?php

class SettingsPage {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

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