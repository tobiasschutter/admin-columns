<?php

namespace AC;

use AC\Type\ColumnId;
use AC\Type\ListScreenId;
use AC\Type\ListScreenTableId;
use AC\Type\ListScreenType;
use WP_Screen;

abstract class ListScreen {

	/**
	 * @var ListScreenId
	 */
	private $id;

	/**
	 * @var ListScreenType
	 */
	// todo: remove in favor of table_id?
	private $type;

	/**
	 * @var MetaType
	 */
	private $meta_type;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var ColumnCollection
	 */
	private $columns;

	/**
	 * @var array
	 */
	private $settings;

	public function __construct(
		ListScreenType $type,
		MetaType $meta_type,
		$label,
		ColumnCollection $columns = null,
		array $settings = [],
		ListScreenId $id = null
	) {
		if ( null === $columns ) {
			$columns = new ColumnCollection();
		}

		$this->type = $type;
		$this->meta_type = $meta_type;
		$this->table_id = $table_id;
		$this->label = $label;
		$this->columns = $columns;
		$this->settings = $settings;
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	abstract public function get_url();

	/**
	 * @return ListScreenType
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @return ListScreenId
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function has_id() {
		return null !== $this->id;
	}

	/**
	 * @return MetaType
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @return ListScreenTableId
	 */
	public function get_table_id() {
		return $this->table_id;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @return ColumnCollection
	 */
	public function get_columns() {
		return $this->columns;
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * @param ColumnId $id
	 *
	 * @return Column|null
	 */
	public function get_column( ColumnId $id ) {
		return $this->columns->get( $id->get_value() );
	}

	// todo: maybe remove?
	public function is_read_only() {
		return false;
	}

}