<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Type\ColumnId;

class FeaturedImage extends Column implements Column\Renderable {

	const NAME = 'column-featured_image';

	public function __construct( ColumnId $id, array $settings = [] ) {
		parent::__construct( self::NAME, 'Featured Image', $id, $settings );
	}

	public function render( $id ) {
		return has_post_thumbnail( $id )
			? get_post_thumbnail_id( $id )
			: '';
	}

}