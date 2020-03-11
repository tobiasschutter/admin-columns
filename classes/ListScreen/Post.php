<?php

namespace AC\ListScreen;

use AC\ListScreen;
use AC\MetaType;
use AC\Type\ListScreenId;
use AC\Type\ListScreenType;
use WP_Screen;

class Post extends ListScreen {

	const TYPE = 'post';

	/**
	 * @var object
	 */
	private $post_type;

	public function __construct( $post_type, $label, ListScreenId $id = null, array $columns = [], array $settings = [] ) {
		parent::__construct( new ListScreenType( self::TYPE ), new MetaType( 'post' ), $label, $id, $columns, $settings );

		$this->post_type = $post_type;
	}

	public function is_valid( WP_Screen $wp_screen ) {
		return 'edit' === $wp_screen->base && 'edit-' . $this->post_type === $wp_screen->id;
	}

	public function get_url( $is_network = false ) {
		$url = $is_network
			? network_admin_url( 'edit.php' )
			: admin_url( 'edit.php' );

		return add_query_arg( [ 'post_type' => $this->post_type ], $url );
	}
}