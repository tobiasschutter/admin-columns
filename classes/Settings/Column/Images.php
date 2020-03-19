<?php

namespace AC\Settings\Column;

use AC\Collection;
use AC\Settings;

class Images extends Settings\Column\Image {

	public function __construct( array $values = [] ) {
		parent::__construct( $values );

		$this->set_dependent_setting( new Settings\Column\NumberOfItems( $values ) );
	}

	protected function set_name() {
		return $this->name = 'images';
	}

	public function format( $value, $original_value ) {
		$collection = new Collection( (array) $value );

		/** @var NumberOfItems $setting */
		$setting = $this->get_dependent_setting( 'number_of_items' );
		$removed = $collection->limit( $setting->get_value() );

		return ac_helper()->html->images( parent::format( $collection->all(), $original_value ), $removed );
	}

}