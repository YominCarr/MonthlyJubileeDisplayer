<?php

require_once dirname( __FILE__ ) . '/../includes/class-mjd-table.php';

class Mjd_Frontend {

	function display_frontend( $atts ){
		$table = new MjdTable();
		return $table->getBirthdayHTML();
	}

}