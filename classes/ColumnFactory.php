<?php

namespace AC;

use AC\Column\Post;

class ColumnFactory {

	public function create( $name, array $settings = [] ) {

		switch ( $name ) {
			case 'author' :
				return new Post\Author( $settings );

			case 'column-permalink' :
				return new Post\Permalink( $settings );
		}

	}
}