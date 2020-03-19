<?php

namespace AC;

use AC\Column\Post;
use AC\Type\ColumnId;

class ColumnFactory {

	public function create( $name, ColumnId $id, array $settings = [] ) {

		switch ( $name ) {
			case Post\Author::NAME :
				// todo: inject name
				return new Post\Author( 'Author', $id, $settings );

			case Post\AuthorName::NAME :
				return new Post\AuthorName( $id, $settings );

			case 'column-permalink' :
				return new Post\Permalink( $id, $settings );
		}

		// todo: add hook for registering columns

		return null;
	}
}