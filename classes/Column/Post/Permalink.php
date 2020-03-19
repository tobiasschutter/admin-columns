<?php

namespace AC\Column\Post;

use AC;
use AC\Column;
use AC\Type\ColumnId;

class Permalink extends Column implements Column\Renderable {

	const NAME = 'column-permalink';

	public function __construct( ColumnId $id, array $settings = [] ) {
		parent::__construct( self::NAME, $id, $settings );
	}

	public function render( $id ) {
		return get_permalink( $id );
	}

}