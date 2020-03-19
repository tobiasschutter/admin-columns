<?php

namespace AC;

use AC\Type\ColumnId;

class Column {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var ColumnId
	 */
	private $id;

	/**
	 * @var array
	 */
	protected $settings;

	public function __construct( $name, $label, ColumnId $id, array $settings = [] ) {
		$this->name = $name;
		$this->label = $label;
		$this->id = $id;
		$this->settings[] = new Settings\Column\Width( $settings );
		$this->settings[] = new Settings\Column\Label( $this->label, $settings );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return ColumnId
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return Settings\Column[]
	 */
	public function get_settings() {
		return $this->settings;
	}

}