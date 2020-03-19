<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\Type\ColumnId;
use AC\View;

class Label extends Settings\Column {

	/**
	 * @var string
	 */
	private $label;

	public function __construct( $label, array $values = [] ) {
		$this->label = $label;

		parent::__construct( $values );
	}

	protected function define_options() {
		return [
			'label'      => $this->label,
			'label_type' => 'text',
		];
	}

	public function create_view( ColumnId $id ) {

		$setting = $this
			->create_element( $id, 'text' )
			->set_attribute( 'placeholder', $this->label );

		$view = new View( [
			'label'   => __( 'Label', 'codepress-admin-columns' ),
			'tooltip' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'setting' => $setting,
		] );

		$view->set_template( 'settings/setting-label' );

		return $view;
	}

	/**
	 * Convert site_url() to [cpac_site_url] and back for easy migration
	 *
	 * @param string $label
	 * @param string $action
	 *
	 * @return string
	 */
	private function convert_site_url( $label, $action = 'encode' ) {
		return ac_convert_site_url( $label, $action );
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->convert_site_url( $this->label, 'decode' );
	}

	/**
	 * @param string $label
	 */
	public function set_label( $label ) {
		$this->label = $label;
	}

	/**
	 * Encode label with site_url.
	 * Used when loading the setting from PHP or when a site is migrated to another domain.
	 * @return string
	 */
	public function get_encoded_label() {
		return $this->convert_site_url( $this->label );
	}

}