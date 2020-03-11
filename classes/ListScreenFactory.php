<?php

namespace AC;

use AC\ListScreen\Post;
use AC\Type\ListScreenType;

class ListScreenFactory {

	public function create( ListScreenType $type, $id = null, $columns = [], $settings = [], $subtype = null ) {

		switch ( $type ) {

			case Post::TYPE :
				$post_type = get_post_type_object( $subtype );

				return new Post( $subtype, $post_type->labels->singular_label, $id, $columns, $settings );
		}

		return null;
	}

}