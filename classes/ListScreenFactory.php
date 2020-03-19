<?php

namespace AC;

use AC\ListScreen\Post;
use AC\Type\ListScreenType;
use RuntimeException;

class ListScreenFactory {

	public function create( ListScreenType $type, array $args = [] ) {

		switch ( $type->get_value() ) {

			case Post::TYPE :
				$post_type = get_post_type_object( $args['subtype'] );

				if ( ! $post_type ) {
					throw new RuntimeException( 'Invalid post type.' );
				}

				return new Post( $args['subtype'], $post_type->labels->singular_name );
		}

		return null;
	}

	public function create_by_key( $key, array $args = [] ) {
		$type = Post::TYPE;

		$args['subtype'] = $key;

		// todo: ms-users, taxonomy etc.

		return $this->create( new ListScreenType( $type ), $args );

	}

}