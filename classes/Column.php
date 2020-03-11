<?php

namespace AC;

class Column {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var array
	 */
	protected $settings;

	public function __construct( $name, array $data, array $settings = [] ) {
		$this->name = $name;
		$this->data = $data;
		$this->settings = $settings;
	}

}