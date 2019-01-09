<?php

class MjdTable {

	public function getTableName() {
		global $wpdb;

		return $wpdb->prefix . "mjd_entries";
	}

	public function install() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		// @todo for testing
		$this->deleteTable();

		$sql = "CREATE TABLE " . $this->getTableName() . " (
				id INT(11) NOT NULL AUTO_INCREMENT,
				name VARCHAR(50) NOT NULL,
				birthday DATE NOT NULL,
 				PRIMARY KEY  (id)
 				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		// @todo for testing
		$this->insertDummyData();
	}

	private function deleteTable() {
		global $wpdb;

		$sql = "DROP TABLE IF EXISTS " . $this->getTableName() . ";";
		$wpdb->query( $sql );
	}

	private function insertDummyData() {
		global $wpdb;

		$wpdb->insert(
			$this->getTableName(),
			array(
				'name' => "Max Mustermann",
				'birthday' => "2013-04-03",
			)
		);
		$wpdb->insert(
			$this->getTableName(),
			array(
				'name' => "Mrs Wordpress",
				'birthday' => "2011-02-01",
			)
		);
	}

	public function plainSelectAllData() {
		global $wpdb;

		$sql = "SELECT * FROM " . $this->getTableName() . ";";
		return $wpdb->get_results( $sql, ARRAY_A );
	}

}