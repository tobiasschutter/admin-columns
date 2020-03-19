<?php

namespace AC\ColumnFactory;

use AC\Column\Post\Author;
use AC\Column\Post\AuthorName;
use AC\Column\Post\Permalink;
use AC\ColumnFactory;
use AC\Type\ColumnId;

class Post implements ColumnFactory {

	public function create( $name, ColumnId $id, array $settings = [] ) {

		switch ( $name ) {
			case Author::NAME :
				// todo: inject name
				return new Author( 'Author', $id, $settings );

			case AuthorName::NAME :
				return new AuthorName( $id, $settings );

			case Permalink::NAME :
				return new Permalink( $id, $settings );
		}

		// todo: add hook for more columns

		return null;
	}
}