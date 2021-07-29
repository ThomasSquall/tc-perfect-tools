<?php

namespace TCPerfectTools\Modules\Tooltip;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Section;
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
		return "tooltip";
	}

	/**
	 * @param $element Controls_Stack
	 * @param $section_id string
	 */
	public function register_controls( Controls_Stack $element, $section_id ) {
		if ( '_section_attributes' !== $section_id ) {
			return;
		}

		$this->tooltip_controls( $element );
	}

	/**
	 * @param Controls_Stack $controls_stack
	 */
	public function tooltip_controls( $controls_stack ) {
		$controls_stack->start_controls_section(
			'section_tooltip',
			[
				'label' => __( 'Tooltip', 'tc-perfect-tools' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$controls_stack->start_controls_tabs(
			'tooltip_tabs'
		);

		$controls_stack->start_controls_tab(
			'tooltip_content_tab',
			[
				'label' => __( 'Content', 'plugin-name' ),
			]
		); {
			// TOOLTIP CONTENT START
			$controls_stack->add_responsive_control(
				'tooltip_max_width',
				[
					'label' =>  __( 'Max width', 'tc-perfect-tools' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'default' => [
						'size' => 360,
					],
					'range' => [
						'px' => [
							'max' => 1400,
							'step' => 1,
						]
					]
				]
			);

			$controls_stack->add_control(
				'tooltip_text',
				[
					'label' =>  __( 'Text', 'tc-perfect-tools' ),
					'type' => Controls_Manager::TEXTAREA,
					'dynamic' => [
						'active' => true,
					]
				]
			);

			$controls_stack->add_control(
				'tooltip_position',
				[
					'label' => __( 'Position', 'tc-perfect-tools' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => __( 'Left', 'elementor-pro' ),
							'icon' => 'far fa-arrow-alt-circle-left',
						],
						'top' => [
							'title' => __( 'Top', 'elementor-pro' ),
							'icon' => 'far fa-arrow-alt-circle-up',
						],
						'right' => [
							'title' => __( 'Right', 'elementor-pro' ),
							'icon' => 'far fa-arrow-alt-circle-right',
						],
						'bottom' => [
							'title' => __( 'Bottom', 'elementor-pro' ),
							'icon' => 'far fa-arrow-alt-circle-down',
						],
					],
					'default' => 'top'
				]
			);
			// TOOLTIP CONTENT END
		} $controls_stack->end_controls_tab();

		$controls_stack->start_controls_tab(
			'tooltip_style_tab',
			[
				'label' => __( 'Style', 'plugin-name' ),
			]
		); {
			// TOOLTIP STYLE START
			$controls_stack->add_control(
				'tooltip_bg_color',
				[
					'label' => __( 'Background color', 'tc-perfect-tools' ),
					'type' => Controls_Manager::COLOR,
					'default' => 'black'
				]
			);

			$controls_stack->add_control(
				'tooltip_text_color',
				[
					'label' => __( 'Text color', 'tc-perfect-tools' ),
					'type' => Controls_Manager::COLOR,
					'default' => 'white'
				]
			);

			$controls_stack->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'tooltip_typography',
					'label' => __( 'Typography', 'tc-perfect-tools' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1
				]
			);
			// TOOLTIP STYLE END
		} $controls_stack->end_controls_tab();

		$controls_stack->start_controls_tab(
			'tooltip_advanced_tab',
			[
				'label' => __( 'Advanced', 'plugin-name' ),
			]
		); {
			// TOOLTIP ADVANCED START
			$controls_stack->add_control(
				'tooltip_padding',
				[
					'label' => __( 'Padding', 'tc-perfect-tools' ),
					'type' => Controls_Manager::DIMENSIONS,
					'default' => [
						'left' => '15',
						'top' => '15',
						'right' => '15',
						'bottom' => '15'
					]
				]
			);
			// TOOLTIP ADVANCED END
		} $controls_stack->end_controls_tab();

		$controls_stack->end_controls_tabs();

		$controls_stack->end_controls_section();
	}

	/**
	 * @param $widget Widget_Base|Element_Section
	 */
	function add_tooltip_class( $widget ) {
		$settings = $widget->get_settings();

		$css_classes = is_a( $widget, 'Elementor\Element_Section' ) ? 'css_classes' : '_css_classes';

		$name = $widget->get_unique_selector();

		if ( ! empty( $settings['tooltip_text'] ) ) {
			if ( empty( $settings[$css_classes] ) ) {
				$settings[$css_classes] = 'tc-tooltip tc-tooltip-' . $name;
			} else {
				$settings[$css_classes] .= ' tc-tooltip tc-tooltip-' . $name;
			}

			$widget->set_settings( $settings );
		}
	}

	/**
	 * @param $content string
	 * @param $widget Widget_Base|Element_Section
	 *
	 * @return string
	 */
	public function render_tooltip( $content, $widget ) {
        $settings = $widget->get_settings();

        if ( !empty( $settings['tooltip_text'] ) ) {
        	$padding = 'auto';

        	if ( !empty($settings['tooltip_padding']) ) {
        		$unit = $settings['tooltip_padding']['unit'];
        		unset( $settings['tooltip_padding']['unit'] );
        		unset( $settings['tooltip_padding']['isLinked'] );

        		$padding = implode( "$unit ", $settings['tooltip_padding'] ) . "$unit";
	        }

        	$name = $widget->get_unique_selector();

            $content .=
                '<span data-tooltip="' . $name . '" class="tc-tooltip-text tc-tooltip-' . $settings['tooltip_position'] . '" style="
                    background-color: ' . $settings['tooltip_bg_color'] . ';
                    color: ' . $settings['tooltip_text_color'] . ';
                    font-family: ' . $settings['tooltip_typography_font_family'] . ';
                    font-weight: ' . $settings['tooltip_typography_font_weight'] . ';
                    padding: ' . $padding . ';
                    max-width: ' . $settings['tooltip_max_width']['size'] . $settings['tooltip_max_width']['unit'] . '
                ">' . esc_textarea( $settings['tooltip_text'] ) . '</span>';

        	switch ( true ) {
		        case $settings['tooltip_position'] === 'top':
		        	$arrow_direction = $settings['tooltip_bg_color'] . ' transparent transparent transparent';
		        	break;
		        case $settings['tooltip_position'] === 'bottom':
			        $arrow_direction = 'transparent transparent ' . $settings['tooltip_bg_color'] . ' transparent';
			        break;
		        case $settings['tooltip_position'] === 'right':
			        $arrow_direction = 'transparent ' . $settings['tooltip_bg_color'] . ' transparent transparent';
			        break;
		        case $settings['tooltip_position'] === 'left':
			        $arrow_direction = 'transparent transparent transparent ' . $settings['tooltip_bg_color'];
			        break;
	        }

            $content .= '<span data-tooltip="' . $name . '" class="tc-tooltip-arrow" style="border-color: ' . $arrow_direction . ';"></span>';
        }

        return $content;
	}

	public function render_section_tooltip( Element_Section $section ) {
		echo $this->render_tooltip( "", $section );
	}

	protected function add_actions() {
	    // This registers the tooltip section in the advanced tab of any widget
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );

		// This is needed to add the class before the content is rendered
		add_action( 'elementor/widget/before_render_content', [ $this, 'add_tooltip_class' ], 10, 2 );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'add_tooltip_class' ], 10, 2 );

		// This is called called just before outputting the content, we use it to append the tooltip
		add_action( 'elementor/widget/render_content', [ $this, 'render_tooltip'], 10, 2 );
		add_action( 'elementor/frontend/section/after_render', [ $this, 'render_section_tooltip'], 10, 2 );
	}
}