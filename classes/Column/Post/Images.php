<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;
use AC\Type\ColumnId;

class Images extends Column {

	const NAME = 'column-images';

	public function __construct( ColumnId $id, array $settings = [] ) {
		parent::__construct( self::NAME, 'Images', $id, $settings );

		$this->add_setting( new Settings\Column\Images( $settings ) );
	}

	public function render( $id ) {
		return 'images';
	}

}