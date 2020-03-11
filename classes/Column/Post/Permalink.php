<?php

namespace AC\Column\Post;

use AC;
use AC\Column;

class Permalink extends Column implements Column\Renderable {

	public function __construct( array $data = [] ) {
		parent::__construct( 'column-permalink', $data );
	}

	public function render( $id ) {
		return get_permalink( $id );
	}

}