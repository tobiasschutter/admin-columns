<?php

namespace AC;

use AC\Type\ListScreenTableId;

class ListScreenTypes {

	/** @var ListScreenTypes */
	private static $instance = null;

	/** @var array */
	private $types = [];

	/**
	 * @return ListScreenTypes
	 */
	static public function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function add( ListScreenTableId $table_id, $label ) {
		$id = sprintf( '%s/%s/%s', $table_id->get_screen_base(), $table_id->get_screen_id(), $table_id->get_screen_extra() );
		$this->types[ $id ] = $label;

		return $this;
	}

	public function all() {
		return $this->types;
	}

}