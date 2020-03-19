<?php

namespace AC\Type;

use LogicException;

// todo: dry with ListScreenId
final class ColumnId {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @param string $id
	 */
	public function __construct( $id ) {
		if ( ! self::is_valid_id( $id ) ) {
			throw new LogicException( 'Found empty Column identity.' );
		}

		$this->id = $id;
	}

	public static function is_valid_id( $id ) {
		return is_string( $id ) && $id !== '';
	}

	/**
	 * @return self
	 */
	public static function generate() {
		return new self( uniqid() );
	}

	public function get_value() {
		return $this->id;
	}

	/**
	 * @param ColumnId $id
	 *
	 * @return bool
	 */
	public function equals( ColumnId $id ) {
		return $this->id === $id->get_value();
	}

}