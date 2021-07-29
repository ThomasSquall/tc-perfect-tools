<?php

namespace TCPerfectTools\Core;

use ElementorPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Modules_Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function __construct() {
		$modules = [
			'custom-js',
			'tooltip',
			'restricted-content'
		];

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\TCPerfectTools\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}

			if (file_exists(TC_PERFECT_TOOLS_PATH . '/modules/' . $module_name . '/assets/style.css')) {
				wp_enqueue_style('tc_' . $module_name . '_style', TC_PERFECT_TOOLS_MODULES_URL . $module_name . '/assets/style.css');
			}

			if (file_exists(TC_PERFECT_TOOLS_PATH . '/modules/' . $module_name . '/assets/script.js')) {
				wp_enqueue_script(
					'tc_' . $module_name . '_script',
					TC_PERFECT_TOOLS_MODULES_URL . $module_name . '/assets/script.js',
					[
						'jquery'
					],
					'plugin_version',
					true // in_footer
				);
			}
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base[]|Module_Base
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}
