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
			'my-setting-admin',
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
			<form method="post" action="options.php">
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
			array( $this, 'print_query_info' ),
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
			array( $this, 'print_textblock_info' ),
			'my-setting-admin'
		);

		add_settings_field(
			'textblock_m',
			'Text to display for male',
			array( $this, 'text_m_callback' ),
			'my-setting-admin',
			'setting_section_text'
		);

		add_settings_field(
			'textblock_f',
			'Text to display for female',
			array( $this, 'text_f_callback' ),
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
		if( isset( $input['min_age'] ) ) {
			$new_input['min_age'] = absint( $input['min_age'] );
		}

		if( isset( $input['textblock_m'] ) ) {
			$new_input['textblock_m'] = sanitize_text_field( $input['textblock_m'] );
		}
		if( isset( $input['textblock_f'] ) ) {
			$new_input['textblock_f'] = sanitize_text_field( $input['textblock_f'] );
		}

		return $new_input;
	}

	public function print_query_info()
	{
		print 'These options configure which birthday entries are queried:';
	}

	public function print_textblock_info()
	{
		print 'These options configure the textblocks which are output in the frontend for each jubilee.<br>
			   Following identifiers are replaced by their according values: %name%, %birthday%, %day%, %age%:';
	}

	public function min_age_callback()
	{
		printf(
			'<input type="text" id="min_age" name="jubilee_options[min_age]" value="%s" />',
			isset( $this->options['min_age'] ) ? esc_attr( $this->options['min_age']) : ''
		);
	}

	public function text_m_callback()
	{
		printf(
			'<textarea id="textblock_m" name="jubilee_options[textblock_m]" cols="150" rows="3">%s</textarea>',
			isset( $this->options['textblock_m'] ) ? esc_attr( $this->options['textblock_m']) : ''
		);
	}
	
	public function text_f_callback()
	{
		printf(
			'<textarea id="textblock_f" name="jubilee_options[textblock_f]" cols="150" rows="3">%s</textarea>',
			isset( $this->options['textblock_f'] ) ? esc_attr( $this->options['textblock_f']) : ''
		);
	}

}