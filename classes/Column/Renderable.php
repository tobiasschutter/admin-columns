<?php

namespace AC\Column;

interface Renderable {

	/**
	 * @param int $id
	 *
	 * @return string
	 */
	public function render( $id );

}