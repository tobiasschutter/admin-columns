<?php

class AC_Admin_Page_Addons extends AC_Admin_Page {

	/**
	 * User meta key for hiding "Install addons" notice
	 *
	 * @since 2.4.9
	 */
	const OPTION_ADMIN_NOTICE_INSTALL_ADDONS_KEY = 'cpac-hide-install-addons-notice';

	/**
	 * @var AC_Addon[]
	 */
	private $addons;

	public function __construct() {
		$this
			->set_slug( 'addons' )
			->set_label( __( 'Add-ons', 'codepress-admin-columns' ) );

		add_action( 'admin_init', array( $this, 'handle_request' ) );
		add_filter( 'wp_redirect', array( $this, 'addon_plugin_statuschange_redirect' ) );
		add_action( 'admin_init', array( $this, 'handle_install_request' ) );
		add_action( 'admin_notices', array( $this, 'missing_addon_notices' ) );
		add_action( 'wp_ajax_cpac_hide_install_addons_notice', array( $this, 'ajax_hide_install_addons_notice' ) );
	}

	/**
	 * Display an activation/deactivation message on the addons page if applicable
	 *
	 * @since 2.2
	 */
	public function handle_request() {
		if ( ! $this->is_current_screen() ) {
			return;
		}

		$status = filter_input( INPUT_GET, 'status' );

		if ( ! $status ) {
			return;
		}

		$addon = $this->get_addon( dirname( filter_input( INPUT_GET, 'plugin' ) ) );

		if ( ! $addon ) {
			return;
		}

		switch ( $status ) {
			case 'activate' :
				AC()->notice( sprintf( __( '%s add-on successfully activated.', 'codepress-admin-columns' ), '<strong>' . $addon->get_title() . '</strong>' ) );
				break;
			case 'deactivate' :
				AC()->notice( sprintf( __( '%s add-on successfully deactivated.', 'codepress-admin-columns' ), '<strong>' . $addon->get_title() . '</strong>' ) );
				break;
		}
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-addons', AC()->get_plugin_url() . 'assets/css/admin-page-addons' . AC()->minified() . '.css', array(), AC()->get_version(), 'all' );
	}

	public function register_addon( AC_Addon $addon ) {
		$this->addons[] = $addon;
	}

	/**
	 * Register addon
	 */
	private function set_addons() {
		$classes = AC()->autoloader()->get_class_names_from_dir( AC()->get_plugin_dir() . 'classes/Addon', 'AC_' );

		foreach ( $classes as $class ) {
			$this->register_addon( new $class );
		}
	}

	/**
	 * @return AC_Addon[]
	 */
	public function get_addons() {
		if ( null === $this->addons ) {
			$this->set_addons();
		}

		return $this->addons;
	}

	/**
	 * @return AC_Addon[]
	 */
	public function get_addons_promo() {
		$addons = $this->get_addons();
		foreach ( $addons as $k => $addon ) {
			if ( ! $addon->is_plugin_active() || $addon->is_addon_active() ) {
				unset( $addons[ $k ] );
			}
		}

		return $addons;
	}

	/**
	 * All addons where 3d party is installed but integration is not installed
	 *
	 * @return AC_Addon[]
	 */
	public function get_missing_addons() {
		$missing = array();

		foreach ( $this->get_addons() as $k => $addon ) {
			if ( $addon->is_plugin_active() && ! $addon->is_addon_active() ) {
				$missing[] = $addon;
			}
		}

		return $missing;
	}

	/**
	 * Possibly adds an admin notice when a third party plugin supported by an addon is installed, but the addon isn't
	 *
	 * @since 2.4.9
	 */
	public function missing_addon_notices() {
		if ( AC()->suppress_site_wide_notices() ) {
			return;
		}

		if ( ac_helper()->user->get_meta_site( self::OPTION_ADMIN_NOTICE_INSTALL_ADDONS_KEY, true ) ) {
			return;
		}

		$plugins = array();

		foreach ( $this->get_addons() as $addon ) {
			if ( $addon->is_plugin_active() && ! $addon->is_addon_active() ) {
				$plugins[] = $addon->get_title();
			}
		}

		if ( $plugins ) {
			$num_plugins = count( $plugins );

			foreach ( $plugins as $index => $plugin ) {
				$plugins[ $index ] = '<strong>' . $plugin . '</strong>';
			}

			$plugins_list = $plugins[0];

			if ( $num_plugins > 1 ) {
				if ( $num_plugins > 2 ) {
					$plugins_list = implode( ', ', array_slice( $plugins, 0, $num_plugins - 1 ) );
					$plugins = array( $plugins_list, $plugins[ $num_plugins - 1 ] );
				}

				$plugins_list = sprintf( __( '%s and %s', 'codepress-admin-columns' ), $plugins[0], $plugins[1] );
			}

			// TODO: move to CSS/JS file
			?>
            <div class="cpac_message updated">
                <a href="#" class="hide-notice hide-install-addons-notice"></a>

                <p><?php printf( __( "Did you know Admin Columns Pro has an integration addon for %s? With the proper Admin Columns Pro license, you can download them from %s!", 'codepress-admin-columns' ), $plugins_list, ac_helper()->html->link( $this->get_link(), __( 'the addons page', 'codepress-admin-columns' ) ) ); ?>
            </div>
            <style type="text/css">
                body .wrap .cpac_message {
                    position: relative;
                    padding-right: 40px;
                }

                .cpac_message .spinner.right {
                    visibility: visible;
                    display: block;
                    right: 8px;
                    text-decoration: none;
                    text-align: right;
                    position: absolute;
                    top: 50%;
                    margin-top: -10px;
                }

                .cpac_message .hide-notice {
                    right: 8px;
                    text-decoration: none;
                    width: 32px;
                    text-align: right;
                    position: absolute;
                    top: 50%;
                    height: 32px;
                    margin-top: -16px;
                }

                .cpac_message .hide-notice:before {
                    display: block;
                    content: '\f335';
                    font-family: 'Dashicons', serif;
                    margin: .5em 0;
                    padding: 2px;
                }
            </style>
            <script type="text/javascript">
				jQuery( function( $ ) {
					$( document ).ready( function() {
						$( '.updated a.hide-install-addons-notice' ).click( function( e ) {
							e.preventDefault();

							var el = $( this ).parents( '.cpac_message' );
							var el_close = el.find( '.hide-notice' );

							el_close.hide();
							el_close.after( '<div class="spinner right"></div>' );
							el.find( '.spinner' ).show();

							$.post( ajaxurl, {
								'action' : 'cpac_hide_install_addons_notice'
							}, function() {
								el.find( '.spinner' ).remove();
								el.slideUp();
							} );

							return false;
						} );
					} );
				} );
            </script>
			<?php
		}
	}

	/**
	 * Ajax callback for hiding the "Missing addons" notice used for notifying users of available integration addons for plugins they have installed
	 *
	 * @since 2.4.9
	 */
	public function ajax_hide_install_addons_notice() {
		ac_helper()->user->update_meta_site( self::OPTION_ADMIN_NOTICE_INSTALL_ADDONS_KEY, '1', true );
	}

	/**
	 * Handles the installation of the add-on
	 *
	 * @since 2.2
	 */
	public function handle_install_request() {
		if ( ! wp_verify_nonce( filter_input( INPUT_GET, '_wpnonce' ), 'install-cac-addon' ) ) {
			return;
		}

		$addon = $this->get_addon( filter_input( INPUT_GET, 'plugin' ) );

		if ( ! $addon ) {
			AC()->notice( __( 'Addon does not exist.', 'codepress-admin-columns' ), 'error' );

			return;
		}

		if ( ! ac_is_pro_active() ) {
			AC()->notice( __( 'You need Admin Columns Pro.', 'codepress-admin-columns' ), 'error' );

			return;
		}

		// Hook: trigger possible warning message before running WP installer ( api errors etc. )
		if ( $error = apply_filters( 'ac/addons/install_request/maybe_error', false, $_GET['plugin'] ) ) {
			AC()->notice( $error, 'error' );

			return;
		}

		$install_url = add_query_arg( array(
			'action'        => 'install-plugin',
			'plugin'        => $addon->get_slug(),
			'ac-redirect' => true,
		), wp_nonce_url( network_admin_url( 'update.php' ), 'install-plugin_' . $addon->get_slug() ) );

		wp_redirect( $install_url );
		exit;
	}

	/**
	 * Redirect the user to the Admin Columns add-ons page after activation/deactivation of an add-on from the add-ons page
	 *
	 * @since 2.2
	 *
	 * @see   filter:wp_redirect
	 */
	public function addon_plugin_statuschange_redirect( $location ) {
		if ( ! is_admin() || ! filter_input( INPUT_GET, 'ac-redirect' ) ) {
			return $location;
		}

		$urlparts = parse_url( $location );

		if ( ! $urlparts ) {
			return $location;
		}

		if ( ! empty( $urlparts['query'] ) ) {
			$admin_url = $urlparts['scheme'] . '://' . $urlparts['host'] . $urlparts['path'];

			// activate or deactivate plugin
			if ( admin_url( 'plugins.php' ) == $admin_url ) {
				parse_str( $urlparts['query'], $request );

				if ( empty( $request['error'] ) ) {
					$location = add_query_arg( array(
						'status' => empty( $request['activate'] ) ? 'deactivate' : 'activate',
						'plugin' => isset( $_GET['plugin'] ) ? $_GET['plugin'] : false,
					), $this->get_link() );
				}
			}
		}

		return $location;
	}

	/**
	 * Addons are grouped into addon groups by providing the group an addon belongs to.
	 * @see   AC_Addons::get_addons
	 *
	 * @since 2.2
	 *
	 * @return array Available addon groups ([group_name] => [label])
	 */
	public function get_addon_groups() {
		$addon_groups = array(
			'recommended' => __( 'Recommended', 'codepress-admin-columns' ),
			'default'     => __( 'Available', 'codepress-admin-columns' ),
		);

		/**
		 * Filter the addon groups
		 *
		 * @since 2.2
		 *
		 * @param array $addon_groups Available addon groups ([group_name] => [label])
		 */
		return apply_filters( 'ac/addons/groups', $addon_groups );
	}

	/**
	 * @param string $name
	 *
	 * @return string|false
	 */
	public function get_group( $name ) {
		$groups = $this->get_addon_groups();

		if ( ! isset( $groups ) ) {
			return false;
		}

		return $groups[ $name ];
	}

	/**
	 * Group a list of add-ons
	 *
	 * @since NEWVERSION
	 *
	 * @return array A list of addons per group: [group_name] => (array) [group_addons], where [group_addons] is an array ([addon_name] => (array) [addon_details])
	 */
	private function get_grouped_addons() {
		$sorted = array();

		// By alphabet
		/* @var AC_Addon[] $addons */
		$addons = array_reverse( $this->get_addons() );

		foreach ( $addons as $addon ) {
			if ( $addon->is_addon_active() ) {
				array_unshift( $sorted, $addon );
			} else {
				array_push( $sorted, $addon );
			}
		}

		$grouped = array();
		foreach ( $this->get_addon_groups() as $group => $label ) {
			foreach ( $sorted as $addon ) {
				if ( $addon->get_group() === $group ) {
					$grouped[ $label ][] = $addon;
				}
			}
		}

		return $grouped;
	}

	/**
	 * Get add-on details from the available add-ons list
	 *
	 * @since 2.2
	 *
	 * @param string $slug Addon slug
	 *
	 * @return AC_Addon|false Returns addon details if the add-on exists, false otherwise
	 */
	public function get_addon( $slug ) {
		foreach ( $this->get_addons() as $addon ) {
			if ( $slug === $addon->get_slug() ) {
				return $addon;
			}
		}

		return false;
	}

	public function display() {

		foreach ( $this->get_grouped_addons() as $group => $addons ) : ?>
            <h2><?php echo esc_html( $group ); ?></h2>

            <ul class="ac-addons">
				<?php
				foreach ( $addons as $addon ) :
					/* @var AC_Addon $addon */ ?>
                    <li>
                        <div class="addon-header">
                            <div class="inner">
								<?php if ( $addon->get_logo() ) : ?>
                                    <img src="<?php echo esc_attr( $addon->get_logo() ); ?>"/>
								<?php else : ?>
                                    <h2><?php echo esc_html( $addon->get_title() ); ?></h2>
								<?php endif; ?>
                            </div>
                        </div>
                        <div class="addon-content">
                            <h3><?php echo esc_html( $addon->get_title() ); ?></h3>
                            <p><?php echo esc_html( $addon->get_description() ); ?></p>
                        </div>
                        <div class="addon-actions">
							<?php

							// Installed..
							if ( $addon->is_addon_installed() ) :

								// Active
								if ( $addon->is_addon_active() ) : ?>
                                    <span class="button active"><?php _e( 'Active', 'codepress-admin-columns' ); ?></span>
                                    <a href="<?php echo esc_url( $addon->get_deactivation_url() ); ?>" class="button right"><?php _e( 'Deactivate', 'codepress-admin-columns' ); ?></a>
									<?php
								// Installed
								else : ?>
                                    <a href="<?php echo esc_url( $addon->get_activation_url() ); ?>" class="button button-primary right"><?php _e( 'Activate', 'codepress-admin-columns' ); ?></a>
								<?php endif;

							// Not installed...
							else :

								if ( ac_is_pro_active() ) :
									$install_url = wp_nonce_url( add_query_arg( array( 'action' => 'install', 'plugin' => $addon->get_slug() ), $this->get_link() ), 'install-cac-addon' );
									?>
                                    <a href="<?php echo esc_url( $install_url ); ?>" class="button"><?php esc_html_e( 'Download & Install', 'codepress-admin-columns' ); ?></a>
								<?php else : ?>
                                    <a target="_blank" href="<?php echo esc_url( $addon->get_link() ); ?>" class="button"><?php esc_html_e( 'Get this add-on', 'codepress-admin-columns' ); ?></a>
								<?php endif;
							endif;
							?>
                        </div>
                    </li>
				<?php endforeach; // addons ?>
            </ul>
		<?php endforeach; // grouped_addons
	}

}