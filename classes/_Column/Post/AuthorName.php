<?php

namespace AC\Column\Post;

use AC;
use AC\Column;

class AuthorName extends Column implements Column\Renderable {

	public function __construct( array $settings = [] ) {
		parent::__construct( 'author', $settings );
	}

	public function render( $id ) {
		return get_post( $id )->post_author;
	}

}