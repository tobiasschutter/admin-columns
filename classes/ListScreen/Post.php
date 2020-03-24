<?php

namespace AC\ListScreen;

use AC\ColumnCollection;
use AC\ListScreen;
use AC\MetaType;
use AC\Type\ListScreenId;
use AC\Type\ListScreenTableId;
use AC\Type\ListScreenType;
use WP_Screen;

class Post extends ListScreen {

	const TYPE = 'post';

	/**
	 * @var object
	 */
	private $post_type;

	public function __construct( $post_type, $label, ColumnCollection $columns = null, array $settings = [], ListScreenId $id = null ) {
		parent::__construct( new ListScreenType( self::TYPE ), new MetaType( 'post' ), new ListScreenTableId( 'edit', 'edit-' . $post_type ), $label, $columns, $settings, $id );

		$this->post_type = $post_type;
	}

	public function is_valid( WP_Screen $wp_screen ) {
		return 'edit' === $wp_screen->base && 'edit-' . $this->post_type === $wp_screen->id;
	}

	public function get_url() {
		return add_query_arg( [ 'post_type' => $this->post_type ], admin_url( 'edit.php' ) );
	}
}