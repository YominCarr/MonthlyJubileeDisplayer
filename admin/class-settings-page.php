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
			'Jubilee Settings',
			'manage_options',
			'my-setting-admin',
			array( $this, 'create_options_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_options_page() {
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
			'setting_section_display',
			'Display options',
			array( $this, 'print_display_info' ),
			'my-setting-admin'
		);

		add_settings_field(
			'date_format',
			'Date format',
			array( $this, 'date_format_callback' ),
			'my-setting-admin',
			'setting_section_display'
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

		add_settings_field(
			'textblock_alt',
			'Text to display without jubilees',
			array( $this, 'text_alt_callback' ),
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

		if( isset( $input['date_format'] ) ) {
			$new_input['date_format'] = sanitize_text_field( $input['date_format'] );
		}

		if( isset( $input['textblock_m'] ) ) {
			$new_input['textblock_m'] = sanitize_text_field( $input['textblock_m'] );
		}
		
		if( isset( $input['textblock_f'] ) ) {
			$new_input['textblock_f'] = sanitize_text_field( $input['textblock_f'] );
		}

		if( isset( $input['textblock_alt'] ) ) {
			$new_input['textblock_alt'] = sanitize_text_field( $input['textblock_alt'] );
		}

		return $new_input;
	}

	public function print_query_info()
	{
		print 'These options configure which birthday entries are queried:';
	}

	public function print_display_info()
	{
		print 'These options configure the formatting of certain output elements.
               For date formatting codes see e.g. <a target="_blank" href="https://www.w3schools.com/sql/func_mysql_date_format.asp">here</a>:<br>
               For example this can be set to: %d.%m.%Y';
	}

	public function print_textblock_info()
	{
		print 'These options configure the textblocks which are output in the frontend for each jubilee.<br>
			   Following identifiers are replaced by their according values: __name__, __birthday__, __day__, __age__, __residence__:';
	}

	public function min_age_callback()
	{
		printf(
			'<input type="text" id="min_age" name="jubilee_options[min_age]" value="%s" />',
			isset( $this->options['min_age'] ) ? esc_attr( $this->options['min_age']) : ''
		);
	}

	public function date_format_callback()
	{
		printf(
			'<input type="text" id="date_format" name="jubilee_options[date_format]" value="%s" />',
			isset( $this->options['date_format'] ) ? esc_attr( $this->options['date_format']) : ''
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

	public function text_alt_callback()
	{
		printf(
			'<textarea id="textblock_alt" name="jubilee_options[textblock_alt]" cols="150" rows="3">%s</textarea>',
			isset( $this->options['textblock_alt'] ) ? esc_attr( $this->options['textblock_alt']) : ''
		);
	}

}