<?php

namespace AC\ListScreenRepository;

interface SourceAware {

	/**
	 * @param string $id
	 *
	 * @return string
	 */
	public function get_source( $id );

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function has_source( $id );

}