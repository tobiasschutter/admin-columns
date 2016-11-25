<?php

class AC_Settings_Setting_Width extends AC_Settings_SettingAbstract {

	/**
	 * @var integer
	 */
	private $width;

	/**
	 * @var string
	 */
	private $width_unit = '%';

	protected function set_managed_options() {
		$this->managed_options = array( 'width', 'width_unit' );
	}

	private function get_valid_width_units() {
		return array(
			'%'  => '%',
			'px' => 'px',
		);
	}

	protected function create_view() {
		$width = $this->create_element( $this->get_managed_option() )
		              ->set_attribute( 'placeholder', __( 'Auto', 'codepress-admin-columns' ) );

		$unit = $this->create_element( 'width_unit', 'radio' )
		             ->set_options( $this->get_valid_width_units() );

		$section = new AC_Settings_View( array(
			'width' => $width,
			'unit'  => $unit,
		) );
		$section->set_template( 'setting-width' );

		return new AC_Settings_View( array(
			'label'    => __( 'Width', 'codepress-admin-columns' ),
			'sections' => array( $section ),
			'name'     => $this->name,
		) );
	}

	/**
	 * @return int
	 */
	public function get_width() {
		return $this->width;
	}

	/**
	 * @param int $width
	 *
	 * @return $this
	 */
	public function set_width( $width ) {
		$width = absint( $width );

		if ( $width > 0 ) {
			$this->width = $width;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_width_unit() {
		return $this->width_unit;
	}
	
	/**
	 * @param string $width_unit
	 *
	 * @return $this
	 */
	public function set_width_unit( $width_unit ) {
		if ( array_key_exists( $width_unit, $this->get_valid_width_units() ) ) {
			$this->width_unit = $width_unit;
		}

		return $this;
	}

}