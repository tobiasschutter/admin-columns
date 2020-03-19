<?php

namespace AC\Column\Post;

use AC;
use AC\Column;
use AC\Type\ColumnId;

class Author extends Column {

	const NAME = 'author';

	public function __construct( $label, ColumnId $id, array $settings = [] ) {
		parent::__construct( self::NAME, $label, $id, $settings );
	}

}