<?php

namespace AC\Column\Post;

use AC\Column;

class FeaturedImage extends Column implements Column\Renderable {

	public function __construct( array $data = [] ) {
		parent::__construct( 'column-featured_image', $data );
	}

	public function render( $id ) {
		return has_post_thumbnail( $id )
			? get_post_thumbnail_id( $id )
			: '';
	}

	public function get_witdh() {
		return ''; // width object
	}

}