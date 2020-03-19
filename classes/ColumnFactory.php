<?php

namespace AC;

use AC\Type\ColumnId;

interface ColumnFactory {

	/**
	 * @param string   $name
	 * @param ColumnId $id
	 * @param array    $settings
	 *
	 * @return Column|null
	 */
	public function create( $name, ColumnId $id, array $settings = [] );

}