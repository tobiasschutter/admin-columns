<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Comments extends AC_Column_Default {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'comments' );
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

}