<?php

namespace AC\Type;

use LogicException;

class ListScreenTableId {

	/**
	 * @var string
	 */
	private $screen_base;

	/**
	 * @var string
	 */
	private $screen_id;

	/**
	 * @var string
	 */
	private $screen_extra;

	public function __construct( $screen_base, $screen_id, $screen_extra = null ) {
		if ( ! $this->validate( $screen_base ) || ! $this->validate( $screen_id ) ) {
			throw new LogicException( 'Invalid screen argument.' );
		}

		$this->screen_base = $screen_base;
		$this->screen_id = $screen_id;
		$this->screen_extra = $screen_extra;
	}

	/**
	 * @param string $var
	 *
	 * @return bool
	 */
	private function validate( $var ) {
		return is_string( $var ) && $var;
	}

	/**
	 * @return string
	 */
	public function get_screen_base() {
		return $this->screen_base;
	}

	/**
	 * @return string
	 */
	public function get_screen_id() {
		return $this->screen_id;
	}

	/**
	 * @return string
	 */
	public function get_screen_extra() {
		return $this->screen_extra;
	}

}