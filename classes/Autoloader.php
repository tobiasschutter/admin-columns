<?php

namespace AC;

use DirectoryIterator;
use FilesystemIterator;

class Autoloader {

	/**
	 * @var self;
	 */
	protected static $instance;

	/**
	 * Prefixes and their path
	 *
	 * @var string[]
	 */
	protected $prefixes;

	/**
	 *
	 * @var array
	 */
	protected $map = [];

	protected function __construct() {
		$this->prefixes = [];

		spl_autoload_register( [ $this, 'autoload' ] );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a prefix that should autoload
	 *
	 * @param $prefix string Unique prefix to this set of classes
	 * @param $dir    string Path to directory where classes are stored
	 *
	 * @return $this
	 */
	public function register_prefix( $prefix, $dir ) {
		$this->prefixes[ $prefix ] = trailingslashit( $dir );

		// make sure that more specific prefixes are checked first
		krsort( $this->prefixes );

		return $this;
	}

	/**
	 * @param array $map
	 */
	public function register_map( array $map ) {
		$this->map = array_merge( $map, $this->map );
	}

	/**
	 * @param $class
	 *
	 * @return false|string
	 */
	protected function get_prefix( $class ) {
		foreach ( array_keys( $this->prefixes ) as $prefix ) {
			if ( 0 === strpos( $class, $prefix ) ) {
				return $prefix;
			}
		}

		return false;
	}

	/**
	 * @param $prefix
	 *
	 * @return false|string
	 */
	protected function get_path( $prefix ) {
		if ( ! isset( $this->prefixes[ $prefix ] ) ) {
			return false;
		}

		return $this->prefixes[ $prefix ];
	}

	/**
	 * Get the path from a given namespace that has a registered prefix
	 *
	 * @param string $class
	 *
	 * @return false|string
	 */
	protected function get_path_from_namespace( $class ) {
		$prefix = $this->get_prefix( $class );

		if ( ! $prefix ) {
			return false;
		}

		$path = $this->get_path( $prefix ) . substr( $class, strlen( $prefix ) );
		$path = str_replace( '\\', '/', $path );

		return $path;
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	public function autoload( $class ) {
		// TODO David Should be a 100% hit for our shipped code, do we need manual PSR-4?
		if ( isset( $this->map[ $class ] ) ) {
			$path = $this->map[ $class ];
		} else {
			$path = $this->get_path_from_namespace( $class ) . '.php';
		}

		$file = realpath( $path );

		if ( ! $file ) {
			return false;
		}

		require_once $file;

		return true;
	}

	public function get_class_names_from_namespace( $namespace ) {
		$classes = [];

		foreach ( $this->map as $class => $path ) {
			if ( 0 === strpos( $class, $namespace ) ) {
				$classes[] = $class;
			}
		}

		return $classes;
	}

	/**
	 * Get list of all auto-loadable class names from a directory
	 *
	 * @param $namespace
	 *
	 * @return array
	 */
	public function get_class_names_from_dir( $namespace ) {
		$path = $this->get_path_from_namespace( $namespace );
		$path = realpath( $path );

		if ( ! $path ) {
			return [];
		}

		$iterator = new FilesystemIterator( $path, FilesystemIterator::SKIP_DOTS );
		$classes = [];

		/* @var DirectoryIterator $leaf */
		foreach ( $iterator as $leaf ) {
			// Exclude system files
			if ( 0 === strpos( $leaf->getBasename(), '.' ) ) {
				continue;
			}

			if ( 'php' === $leaf->getExtension() ) {
				$classes[] = $namespace . '\\' . pathinfo( $leaf->getBasename(), PATHINFO_FILENAME );
			}
		}

		return $classes;
	}
}