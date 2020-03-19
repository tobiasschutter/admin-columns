<?php

namespace AC\Column\Post;

use AC;
use AC\Column;
use AC\Type\ColumnId;

class AuthorName extends Column implements Column\Renderable {

	const NAME = 'column-author_name';

	public function __construct( ColumnId $id, array $settings = [] ) {
		parent::__construct( self::NAME, __( 'Author Name', 'codepress-admin-columns' ), $id, $settings );
	}

	public function render( $id ) {
		return 'Author name';
	}

}