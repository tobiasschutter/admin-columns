<?php

namespace AC;

use AC\Type\ListScreenTableId;
use WP_Post_Type;

class ListScreens implements Registrable {

	public function register() {
		add_action( 'init', [ $this, 'register_list_screens' ], 1000 ); // run after all post types are registered
	}

	public function register_list_screens() {
		$types = ListScreenTypes::instance();

		foreach ( $this->get_post_types() as $post_type ) {
			$table_id = new ListScreenTableId( 'edit', 'edit-' . $post_type->name );
			$types->add( $table_id, $post_type->label );
		}

		//		$list_screens[] = new ListScreen\Media();
		//		$list_screens[] = new ListScreen\Comment();
		//
		//		if ( ! is_multisite() ) {
		//			$list_screens[] = new ListScreen\User();
		//		}
		//
		//		foreach ( $list_screens as $list_screen ) {
		//			$this->register_list_screen( $list_screen );
		//		}

		do_action( 'ac/list_screens', $this );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return self
	 */
//	public function register_list_screen( ListScreen $list_screen ) {
//		ListScreenTypes::instance()->register_list_screen( $list_screen );
//
//		return $this;
//	}

	/**
	 * @return WP_Post_Type[]
	 */
	public function get_post_types() {
		$post_types = get_post_types( [
			'_builtin' => false,
			'show_ui'  => true,
		] );

		$post_types['page'] = 'page';
		$post_types['post'] = 'post';

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @param array $post_types List of active post type names
		 *
		 * @since 2.0
		 */
		$post_types = apply_filters( 'ac/post_types', $post_types );

		$objects = [];

		foreach ( $post_types as $post_type ) {
			if ( ! post_type_exists( $post_type ) ) {
				continue;
			}

			$objects[] = get_post_type_object( $post_type );;
		}

		return $objects;
	}

}