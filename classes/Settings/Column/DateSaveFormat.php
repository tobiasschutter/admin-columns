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
		$field = $this->create_element( 'select', 'date_save_format' )
		              ->set_options( array(
			              ''            => 'Default',
			              'Y-m-d'       => 'Y-m-d',
			              'Y-m-d H:i:s' => 'Y-m-d H:i:s',
			              'd-m-Y'       => 'd-m-Y',
			              'd/m/Y'       => 'd/m/Y',
			              'm-d-Y'       => 'm-d-Y',
			              'm/d/Y'       => 'm/d/Y',
			              'Ymd'         => 'Ymd',
			              'U'           => 'Timestamp',
		              ) );

		$view = new View( array(
			'label'    => __( 'Date Save Format', 'codepress-admin-columns' ),
			'tooltip'  => __( 'Choose the date format that is used in the database for this field.', 'codepress-admin-columns' ),
			'setting'  => $field,
		) );

		$view->set_template( 'settings/setting-date-save-format' );

		return $view;
	}

	public function get_date_save_format() {
		return $this->date_save_format;
	}

	public function set_date_save_format( $format ) {
		$this->date_save_format = $format;
	}

}