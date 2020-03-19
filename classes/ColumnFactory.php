<?php

namespace AC;

use AC\Column\Post;
use RuntimeException;

class ColumnFactory {

	public function create( $name, array $settings = [] ) {

		switch ( $name ) {
			case 'author' :
				return new Post\Author( $settings );

			case 'column-permalink' :
				return new Post\Permalink( $settings );
		}

		return new RuntimeException( 'Invalid column.' );
	}
}