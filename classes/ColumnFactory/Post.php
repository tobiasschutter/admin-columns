<?php

namespace AC\ColumnFactory;

use AC\Column\Post\Author;
use AC\Column\Post\AuthorName;
use AC\Column\Post\Images;
use AC\Column\Post\Permalink;
use AC\ColumnFactory;
use AC\Type\ColumnId;

class Post implements ColumnFactory {

	public function create( $name, ColumnId $id, array $settings = [], $label = null ) {

		switch ( $name ) {
			case Author::NAME :
				return new Author( $label, $id, $settings );

			case AuthorName::NAME :
				return new AuthorName( $id, $settings );

			case Permalink::NAME :
				return new Permalink( $id, $settings );

			case Images::NAME :
				return new Images( $id, $settings );
		}

		// todo: add hook for more columns

		return null;
	}
}