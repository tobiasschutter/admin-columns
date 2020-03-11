<?php

namespace AC\Column\Post;

use AC\Column;

class Author extends Column {

	public function __construct( array $settings = [] ) {
		parent::__construct( 'author', $settings );
	}

}