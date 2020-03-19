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
	 * @var Settings\Column[]
	 */
	protected $settings;

	public function __construct( $name, $label, ColumnId $id, array $settings = [] ) {
		$this->name = $name;
		$this->label = $label;
		$this->id = $id;

		$this->add_setting( new Settings\Column\Label( $label, $settings ) )
		     ->add_setting( new Settings\Column\Width( $settings ) );
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
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}


	/**
	 * @return Settings\Column[]
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * @param Settings\Column $setting
	 *
	 * @return $this
	 */
	protected function add_setting( Settings\Column $setting ) {
		$this->settings[ $setting->get_name() ] = $setting;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return Settings\Column
	 */
	public function get_setting( $name ) {
		return $this->settings[ $name ];
	}

	// todo: remove?
	public function is_original() {
		return true;
	}

}