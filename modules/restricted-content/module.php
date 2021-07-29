<?php

namespace TCPerfectTools\Modules\RestrictedContent;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;
use ElementorPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return "restricted-content";
	}

	/**
	 * @param $element Controls_Stack
	 * @param $section_id string
	 */
	public function register_controls( Controls_Stack $element, $section_id ) {
		if ( 'section_tooltip' !== $section_id ) {
			return;
		}

		$this->restricted_content_controls( $element );
	}

	/**
	 * @param Controls_Stack $controls_stack
	 */
	public function restricted_content_controls( $controls_stack ) {
		$controls_stack->start_controls_section(
			'section_restricted_content',
			[
				'label' => __( 'Restricted Content', 'tc-perfect-tools' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$controls_stack->add_control(
			'restricted_content_active',
			[
				'label' =>  __( 'Restrict Content?', 'tc-perfect-tools' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'tc-perfect-tools' ),
				'label_off' => __( 'Off', 'tc-perfect-tools' ),
				'return_value' => 'yes',
			]
		);

		$roles = [];

		foreach (WP_Roles()->roles as $index => $role) {
			$roles[$index] = $role['name'];
		}

		$controls_stack->add_control(
			'restricted_content_roles',
			[
				'label' =>  __( 'Permitted user roles', 'tc-perfect-tools' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $roles,
				'default' => [ 'administrator' ],
				'condition' => [
					'restricted_content_active' => 'yes',
				]
			]
		);

		$controls_stack->add_control(
			'restricted_content_content',
			[
				'label' =>  __( 'Message when not authorized', 'tc-perfect-tools' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'condition' => [
					'restricted_content_active' => 'yes',
				]
			]
		);

		$controls_stack->end_controls_section();
	}

	/**
	 * @param $content string
	 * @param $widget Widget_Base
	 *
	 * @return string
	 */
	public function render_restricted_content( $content, Widget_Base $widget ) {
		$settings = $widget->get_settings();

		if ( $settings['restricted_content_active'] !== 'yes' ) {
			return $content;
		}

		if ( !is_user_logged_in() ) {
			return $settings[ 'restricted_content_content' ];
		}

		foreach ($settings['restricted_content_roles'] as $permitted_role) {
			if ( in_array( $permitted_role, ( array ) wp_get_current_user()->roles ) ) {
				return $content;
			}
		}

		return $settings[ 'restricted_content_content' ];
	}

	/**
	 * @param $should_render
	 * @param $section
	 *
	 * @return bool
	 */
	public function should_render( $should_render, $section ) {
		$settings = $section->get_settings();

		if ( $settings['restricted_content_active'] !== 'yes' ) {
			return true;
		}

		if ( !is_user_logged_in() ) {
			return false;
		}

		foreach ( $settings['restricted_content_roles'] as $permitted_role ) {
			if ( in_array( $permitted_role, ( array ) wp_get_current_user()->roles ) ) {
				return true;
			}
		}

		return false;
	}

	public function after_render( $section ) {
		$settings = $section->get_settings();

		if ( $settings['restricted_content_active'] !== 'yes' ) {
			return;
		}

		if ( !is_user_logged_in() ) {
			echo $settings[ 'restricted_content_content' ];
		}

		foreach ( $settings['restricted_content_roles'] as $permitted_role ) {
			if ( in_array( $permitted_role, ( array ) wp_get_current_user()->roles ) ) {
				return;
			}
		}

		echo $settings[ 'restricted_content_content' ];
	}

	protected function add_actions() {
		// This registers the restricted content section in the advanced tab of any widget
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );

		// This is called called just before outputting the content, we use it to append the tooltip
		add_action( 'elementor/widget/render_content', [ $this, 'render_restricted_content'], 10, 2 );

		// This is for sections and columns
		add_action( 'elementor/frontend/section/should_render', [ $this, 'should_render' ], 10, 2  );
		add_action( 'elementor/frontend/column/should_render', [ $this, 'should_render' ], 10, 2  );

		add_action( 'elementor/frontend/section/after_render',  [ $this, 'after_render'], 10, 2 );
		add_action( 'elementor/frontend/column/after_render',  [ $this, 'after_render'], 10, 2 );
	}
}