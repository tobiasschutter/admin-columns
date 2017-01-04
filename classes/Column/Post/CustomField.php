<?php

/**
 * @since NEWVERSION
 */
class AC_Column_Post_CustomField extends AC_Column_CustomField {

	/**
	protected function get_cache_key() {
		return $this->get_post_type();
	}
	 */

	/**
	 * @return array
	 */
	/**
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->get_post_type() ), ARRAY_N );
	}
	 */

	/**
	 * @return array
	 */
	/**
	public function get_meta_values( $ids ) {
		return ac_helper()->meta->get_values_by_ids( $ids, $this->get_field_key(), $this->get_list_screen()->get_meta_type() );
	}
	 */
}