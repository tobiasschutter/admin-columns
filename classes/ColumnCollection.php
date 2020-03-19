<?php

namespace AC;

class ColumnCollection extends Collection {

	public function add( Column $column ) {
		$this->put( $column->get_id()->get_value(), $column );
	}

}