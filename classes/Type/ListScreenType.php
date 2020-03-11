<?php

namespace AC\Type;

use LogicException;

class ListScreenType {

	/**
	 * @var string
	 */
	private $value;

	public function __construct( $value ) {
		if ( ! is_string( $value ) || ! $value ) {
			throw new LogicException( 'Invalid ListScreen type.' );
		}

		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

}