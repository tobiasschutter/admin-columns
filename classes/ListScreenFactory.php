<?php

namespace AC;

use AC\ListScreen\Post;
use AC\Type\ColumnId;
use AC\Type\ListScreenId;
use AC\Type\ListScreenTableId;
use RuntimeException;

class ListScreenFactory {

	public function create( ListScreenTableId $id, array $args = [] ) {

		switch ( $id->get_screen_base() ) {

			case 'edit' :
				if ( ! isset( $args['post_type'] ) ) {
					throw new RuntimeException( 'Missing post type argument.' );
				}

				$post_type = get_post_type_object( $args['post_type'] );

				if ( ! $post_type ) {
					throw new RuntimeException( 'Invalid post type.' );
				}

				$id = isset( $args['id'] ) && ListScreenId::is_valid_id( $args['id'] )
					? new ListScreenId( $args['id'] )
					: null;

				// todo; what to do with Product columns?
				$column_factory = isset( $args['column_factory'] )
					? $args['column_factory']
					: new ColumnFactory\Post();

				$columns = isset( $args['columns'] )
					? $this->get_columns( $column_factory, $args['columns'] )
					: new ColumnCollection();

				$settings = isset( $args['settings'] )
					? $args['settings']
					: [];

				return new Post(
					$args['post_type'],
					$post_type->labels->singular_name,
					$columns,
					$settings,
					$id
				);
		}

		return null;
	}

	public function create_by_key( $key, array $args = [] ) {
		$type = Post::TYPE;

		$args['post_type'] = $key;

		// todo convert key to TableId
		// todo: ms-users, taxonomy etc.

		$table_id = new ListScreenTableId(
			'edit',
			'edit-' . $key
		);

		return $this->create( $table_id, $args );
	}

	/**
	 * @param ColumnFactory $factory
	 * @param array         $columns_data
	 *
	 * @return ColumnCollection
	 */
	protected function get_columns( ColumnFactory $factory, array $columns_data = [] ) {
		$columns = new ColumnCollection();

		foreach ( $columns_data as $id => $settings ) {
			$type = $settings['type'];

			unset( $settings['type'] );

			// todo: fetch original label for native columns

			$column = $factory->create( $type, new ColumnId( $id ), $settings );

			if ( ! $column ) {
				continue;
			}

			$columns->add( $column );
		}

		return $columns;
	}

}