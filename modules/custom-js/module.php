<?php

namespace TCPerfectTools\Modules\CustomJs;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use ElementorPro\Base\Module_Base;
use TCPerfectTools\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return "custom-js";
	}

	/**
	 * @param $element    Controls_Stack
	 * @param $section_id string
	 */
	public function register_controls( Controls_Stack $element, $section_id ) {
		// Remove Custom CSS Banner (From free version)
		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		$this->custom_js_controls( $element );
	}

	public function add_page_js() {
		$document = Plugin::elementor()->documents->get( get_the_ID() );
		$custom_js = $document->get_settings( 'custom_js' );

		$custom_js = trim( $custom_js );

		if ( empty( $custom_js ) ) {
			return;
		}

		wp_enqueue_script(
			'tc-perfect-tools-custom-js',
			TC_PERFECT_TOOLS_MODULES_URL . 'custom-js/assets/empty.js',
			[
				'elementor-frontend', // dependency
				'jquery',
			],
			'plugin_version',
			true // in_footer
		);

		wp_add_inline_script( 'tc-perfect-tools-custom-js', esc_js( $custom_js ) );
	}

	/**
	 * @param $element Element_Base
	 */
	public function add_element_js( $element ) {
		$element_settings = $element->get_settings();

		if ( empty( $element_settings['custom_js'] ) ) {
			return;
		}

		$custom_js = trim( $element_settings['custom_js'] );

		if ( empty( $custom_js ) ) {
			return;
		}

		$handle = 'tc-perfect-tools-custom-js-' . $element->get_unique_selector();

		wp_enqueue_script(
			$handle,
			TC_PERFECT_TOOLS_MODULES_URL . 'custom-js/assets/empty.js',
			[
				'elementor-frontend', // dependency
				'jquery',
			],
			'plugin_version',
			true // in_footer
		);

		wp_add_inline_script( $handle, $custom_js );
	}

	/**
	 * @param Controls_Stack $controls_stack
	 */
	public function custom_js_controls( $controls_stack ) {
		$controls_stack->start_controls_section(
			'section_custom_js',
			[
				'label' => __( 'Custom JS', 'tc-perfect-tools' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$controls_stack->add_control(
			'custom_js_title',
			[
				'raw' => __( 'Add your own custom JS here', 'tc-perfect-tools' ),
				'type' => Controls_Manager::RAW_HTML,
			]
		);

		$controls_stack->add_control(
			'custom_js',
			[
				'type' => Controls_Manager::CODE,
				'label' => __( 'Custom JS', 'tc-perfect-tools' ),
				'language' => 'javascript',
				'render_type' => 'ui',
				'show_label' => false,
				'separator' => 'none',
			]
		);

		$controls_stack->end_controls_section();
	}

	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'add_page_js' ] );
		add_action( 'elementor/frontend/after_render',  [ $this, 'add_element_js' ], 10, 1 );
	}
}