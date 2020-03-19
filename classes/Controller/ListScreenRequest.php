<?php

namespace AC\Controller;

use AC\ColumnFactory\Post;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\Preferences;
use AC\Request;
use AC\Settings\Column\Images;
use AC\Type\ColumnId;
use AC\Type\ListScreenId;

class ListScreenRequest {

	/** @var Request */
	private $request;

	/** @var Storage */
	private $storage;

	/** @var Preferences */
	private $preference;

	/** @var bool */
	private $is_network;

	/**
	 * @var ListScreenFactory
	 */
	private $list_screen_factory;

	public function __construct( Request $request, Storage $storage, Preferences $preference, $is_network = false ) {
		$this->request = $request;
		$this->storage = $storage;
		$this->preference = $preference;
		$this->is_network = (bool) $is_network;
		$this->list_screen_factory = new ListScreenFactory();
	}

	/**
	 * @param string $list_key
	 *
	 * @return bool
	 */
	private function exists_list_screen( $list_key ) {
		return true;

		return null !== $this->list_screen_factory->create_by_key( $list_key );
		//		return null !== ListScreenTypes::instance()->get_list_screen_by_key( $list_key, $this->is_network );
	}

	/**
	 * @param string $list_key
	 *
	 * @return ListScreen|null
	 */
	private function get_first_available_list_screen( $list_key ) {
		$list_screens = $this->storage->find_all( [ 'key' => $list_key ] );

		if ( $list_screens->count() < 1 ) {
			return null;
		}

		return $list_screens->current();
	}

	/**
	 * @return ListScreen
	 */
	public function get_list_screen() {

		// todo test
		$factory = new Post();
		$column = $factory->create( 'column-images', ColumnId::generate(), [
			'width'           => '202',
			'width_unit'      => 'px',
			'label'           => 'Images Test',
			'number_of_items' => 2,
			'image_size'      => 'cpac-custom',
			'image_size_w'    => 34,
			'image_size_h'    => 50,
		] );

		/** @var Images $images */
		$images = $column->get_setting( 'images' );
		$value = $images->format( [ 3152, 3153, 3155, 3156 ], '' );

		echo '<pre>'; print_r( $value ); echo '</pre>'; exit;


		echo '<pre>'; print_r( $column ); echo '</pre>'; exit;
		
		$id = new ListScreenId( '5e738d0e36e2d' );

		$list_screen = $this->storage->find( $id );

		$column = $list_screen->get_column( new ColumnId( '5e738d28500c2' ) );

		echo '<pre>a';
		print_r( $column );
		echo '</pre>';
		exit;

		return;
		// Requested list ID
		$list_id = ListScreenId::is_valid_id( filter_input( INPUT_GET, 'layout_id' ) )
			? new ListScreenId( filter_input( INPUT_GET, 'layout_id' ) )
			: null;

		if ( $list_id && $this->storage->exists( $list_id ) ) {
			$list_screen = $this->storage->find( $list_id );

			if ( $list_screen && $this->exists_list_screen( $list_screen->get_key() ) ) {
				$this->preference->set( 'list_id', $list_screen->get_layout_id() );
				$this->preference->set( 'list_key', $list_screen->get_key() );

				return $list_screen;
			}
		}

		// Requested list type
		$list_key = filter_input( INPUT_GET, 'list_screen' );

		if ( $list_key && $this->exists_list_screen( $list_key ) ) {
			$this->preference->set( 'list_key', $list_key );

			$list_screen = $this->get_first_available_list_screen( $list_key );

			if ( $list_screen ) {
				$this->preference->set( 'list_id', $list_screen->get_layout_id() );

				return $list_screen;
			}

			// Initialize new
			return $this->create_list_screen( $list_key );
		}

		// Last visited ID
		$list_id_pref = $this->preference->get( 'list_id' );
		$list_id = ListScreenId::is_valid_id( $list_id_pref )
			? new ListScreenId( $list_id_pref )
			: null;

		if ( $list_id && $this->storage->exists( $list_id ) ) {
			$list_screen = $this->storage->find( $list_id );

			if ( $list_screen && $this->exists_list_screen( $list_screen->get_key() ) ) {
				return $list_screen;
			}
		}

		// Last visited Key
		$list_key = $this->preference->get( 'list_key' );

		// Load first available ID
		if ( $list_key && $this->exists_list_screen( $list_key ) ) {
			$this->preference->set( 'list_key', $list_key );

			$list_screen = $this->get_first_available_list_screen( $list_key );

			if ( $list_screen ) {
				$this->preference->set( 'list_id', $list_screen->get_layout_id() );

				return $list_screen;
			}

			// Initialize new
			return $this->create_list_screen( $list_key );
		}

		// First visit to settings page
		$list_key = $this->get_first_available_list_screen_key();

		$this->preference->set( 'list_key', $list_key );

		$list_screen = $this->get_first_available_list_screen( $list_key );

		if ( $list_screen ) {
			$this->preference->set( 'list_id', $list_screen->get_layout_id() );

			return $list_screen;
		}

		// Initialize new
		return $this->create_list_screen( $list_key );
	}

	private function create_list_screen( $key ) {
		$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $key );

		if ( ! $list_screen ) {
			return null;
		}

		return $list_screen->set_layout_id( ListScreenId::generate()->get_id() );
	}

	/**
	 * @return string
	 */
	private function get_first_available_list_screen_key() {
		if ( $this->is_network ) {
			$list_screens = ListScreenTypes::instance()->get_list_screens( [ 'network_only' => true ] );
		} else {
			$list_screens = ListScreenTypes::instance()->get_list_screens( [ 'site_only' => true ] );
		}

		return current( $list_screens )->get_key();
	}

}