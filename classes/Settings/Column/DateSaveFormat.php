<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class DateSaveFormat extends Settings\Column {

	private $date_save_format;

	protected function define_options() {
		return array(
			'date_save_format' => '',
		);
	}

	/**
	 * @return View
	 */
	public function create_view() {
		$field = $this->create_element( 'text', 'date_save_format' )->set_attribute( 'placeholder', __( 'Date save format', 'codepress-admin-columns' ) );

		$view = new View( array(
			'label'   => __( 'Date Save Format', 'codepress-admin-columns' ),
			'setting' => $field,
		) );

		return $view;
	}

	public function get_date_save_format() {
		return $this->date_save_format;
	}

	public function set_date_save_format( $format ) {
		$this->date_save_format = $format;
	}

}