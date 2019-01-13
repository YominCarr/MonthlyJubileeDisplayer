<?php

class MjdTable {

	private function getTableName() {
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
				gender VARCHAR(1) NOT NULL,
				birthday DATE NOT NULL,
				residence VARCHAR(100) NOT NULL,
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
				'name'     => "Max Mustermann",
				'gender'   => "m",
				'birthday' => "2013-04-03",
				'residence' => "Somewhere",
			)
		);
		$wpdb->insert(
			$this->getTableName(),
			array(
				'name'     => "Mrs Wordpress",
				'gender'   => "f",
				'birthday' => "2011-01-01",
				'residence' => "Somewhere",
			)
		);
		$wpdb->insert(
			$this->getTableName(),
			array(
				'name'     => "Mrs Wordpress Jr.",
				'gender'   => "f",
				'birthday' => "2000-01-20",
				'residence' => "Somewhere",
			)
		);
		$wpdb->insert(
			$this->getTableName(),
			array(
				'name'     => "Mrs Wordpress Jr. Jr.",
				'gender'   => "f",
				'birthday' => "2018-01-01",
				'residence' => "Somewhere",
			)
		);
		$wpdb->insert(
			$this->getTableName(),
			array(
				'name'     => "Mr Wordpress",
				'gender'   => "m",
				'birthday' => "1950-01-31",
				'residence' => "Somewhere",
			)
		);
	}

	public function getBirthdayHTML() {
		$data = $this->plainSelectAllDataForCurrentMonth();
		$html = "";

		foreach ($data as $dataRow) {
			$html .= $this->getBirthdayText($dataRow) . "<br>";
		}

		return $html;
	}

	// @todo birthdays selected in the future have a year to small by one because it is rounding down
	// @todo select only day for birthday
	private function plainSelectAllDataForCurrentMonth() {
		global $wpdb;

		$options = get_option('jubilee_options');
		$min_age = $options['min_age'];
		if (empty($min_age)) {
			$min_age = 0;
		}

		$sql = "SELECT name,
				gender,
				birthday,
				DAY(birthday) as day,
				TIMESTAMPDIFF(YEAR, birthday, LAST_DAY(NOW())) AS age,
				residence
 				FROM " . $this->getTableName() . "
 				WHERE MONTH(birthday) = MONTH(NOW())
				HAVING age > $min_age;";

		return $wpdb->get_results( $sql, ARRAY_A );
	}

	private function getBirthdayText( $dataRow ) {
		$name = $dataRow["name"];
		$age  = $dataRow["age"];
		$birthday = $dataRow["birthday"];
		$day = $dataRow["day"];
		$residence = $dataRow["residence"];

		$options = get_option('jubilee_options');

		if ( $dataRow["gender"] == "f" ) {
			$textblock = $options['textblock_f'];
		} else {
			$textblock = $options['textblock_m'];
		}

		if (empty($textblock)) {
			$textblock = 'Congratulations %name% from %residence% for turning %age% this month on the %birthday%!';
		}

		$text = str_replace("%name%", $name, $textblock);
		$text = str_replace("%age%", $age, $text);
		$text = str_replace("%birthday%", $birthday, $text);
		$text = str_replace("%day%", $day, $text);
		$text = str_replace("%residence%", $residence, $text);

		return $text;
	}

}