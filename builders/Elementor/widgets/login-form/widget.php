<?php
class EfbLoginFormElementor extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'efbLoginFormElementor';
	}

	public function get_title(): string {
		return esc_html__( 'Login Form', 'elementor-addon' );
	}

	public function get_icon(): string {
		return 'eicon-code';
	}

	public function get_categories(): array {
		return [ 'basic' ];
	}

	public function get_keywords(): array {
		return [ 'login', 'form', 'emu' ];
	}

    public function get_script_depends(): array {
		return [ 'google-recaptcha','emu-form-js', 'emu-login-handler' ];
	}

    public function get_style_depends(): array {
		return [ 'emu-login-handler'];
	}

    protected function register_controls(): void {

        // Iniciando a sessão dos steps
        $this->start_controls_section(
			'steps_section',
			[
				'label' => esc_html__( 'Steps', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
       

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'steps_typography',
				'selector' => '{{WRAPPER}} .efb-steps li',
                'label' => 'Label typography',
			]
		);

        // iniciando o wrapper das tabs
        $this->start_controls_tabs(
            'style_tabs'
        );
        
        // iniciando uma opção (tab)
        $this->start_controls_tab(
            'steps_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'textdomain' ),
            ]
        );

        // controles
        $this->add_control(
			'step_color',
			[
				'label' => esc_html__( 'Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-steps li' => 'color: {{VALUE}}',
				],
                'default' => '#000'
			]
		);
        
        $this->add_control(
			'step_background_color',
			[
				'label' => esc_html__( 'Background Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-steps li' => 'background-color: {{VALUE}}',
				],
                'default' => '#00000050'
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'step_border',
				'selector' => '{{WRAPPER}} .efb-steps li',
			]
		);

        // finalizando uma opção (tab)
        $this->end_controls_tab();
        
        // iniciando outra tab
        $this->start_controls_tab(
            'steps_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'textdomain' ),
            ]
        );

        // controles
        $this->add_control(
			'step_hover_color',
			[
				'label' => esc_html__( 'Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-steps li:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'step_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-steps li:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'step_border_hover',
				'selector' => '{{WRAPPER}} .efb-steps li:hover',
			]
		);

        // finalizando outra tab
        $this->end_controls_tab();
        
        // iniciando outra tab
        $this->start_controls_tab(
            'steps_active_tab',
            [
                'label' => esc_html__( 'Active', 'textdomain' ),
            ]
        );

        // controles
        $this->add_control(
			'step_active_color',
			[
				'label' => esc_html__( 'Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-steps li.active' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'step_active_background_color',
			[
				'label' => esc_html__( 'Background Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-steps li.active' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'step_border_active',
				'selector' => '{{WRAPPER}} .efb-steps li.active',
			]
		);

        // finalizando outra tab
        $this->end_controls_tab();
        
        // finalizando wrapper das tabs
        $this->end_controls_tabs();

        // adicionando separador
        $this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

        // continuando controles
        $this->add_control(
			'steps_margin_bottom',
			[
				'label' => esc_html__( 'Bottom space', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 40,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .efb-steps' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'steps_gap',
			[
				'label' => esc_html__('Space between', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .efb-steps ul' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

        // finalizando a sessão dos steps
		$this->end_controls_section();

        // Iniciando a sessão dos titulos
        $this->start_controls_section(
			'titles_section',
			[
				'label' => esc_html__( 'Titles', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

         
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'step_label_typography',
				'selector' => '{{WRAPPER}} .efb-step-title',
                'label' => 'Title typography',
			]
		);

        $this->add_control(
			'step_title_color',
			[
				'label' => esc_html__( 'Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-step-title' => 'color: {{VALUE}}',
				],
                'default' => '#000'
			]
		);
        
        $this->add_control(
			'step_title_background_color',
			[
				'label' => esc_html__( 'Background Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .efb-step-title' => 'background-color: {{VALUE}}',
				],
                'default' => '#00000050'
			]
		);

        $this->add_control(
			'step_title_align',
			[
				'label' => esc_html__( 'Alignment', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .efb-step-title' => 'text-align: {{VALUE}};',
				],
			]
		);

        // adicionando separador
        $this->add_control(
            'hr1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
			'step_title_padding',
			[
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0.4,
					'right' => 0.4,
					'bottom' => 0.4,
					'left' => 0.4,
					'unit' => 'em',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .efb-step-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'step_title_margin',
			[
				'label' => esc_html__( 'Margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .efb-step-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'step_title_border',
				'selector' => '{{WRAPPER}} .efb-step-title',
			]
		);

        $this->add_control(
			'step_title_border-radius',
			[
				'label' => esc_html__( 'Border Radius', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .efb-step-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        // Iniciando a sessão dos campos
        $this->start_controls_section(
			'fields_section',
			[
				'label' => esc_html__( 'Fields', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'field_label_typography',
				'selector' => '{{WRAPPER}} .efb-form-group label',
                'label' => 'Label typography',
			]
		);

        $this->add_control(
            'field_label_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .efb-form-group label' => 'color: {{VALUE}}',
                ],
                'default' => '#000'
            ]
        );
                
        $this->add_control(
			'label_margin_bottom',
			[
				'label' => esc_html__('Bottom Space', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .efb-form-group label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    // este seletor é pro checkbox de lembrar-me
                    '{{WRAPPER}} [for="remember"]' => 'margin-bottom:0!important;'
				],
			]
		);

        // adicionando separador
        $this->add_control(
            'hr4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'fields_typography',
                'selector' => '{{WRAPPER}} :is(input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea)',
                'label' => 'Field typography',
            ]
        );

        $this->add_control(
            'field_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} :is(input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea)' => 'color: {{VALUE}}',
                ],
                'default' => '#000'
            ]
        );

        $this->add_control(
            'field_background_color',
            [
                'label' => esc_html__( 'Background Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} :is(input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea)' => 'background-color: {{VALUE}}',
                ],
                'default' => '#fff'
            ]
        );

        $this->add_control(
			'field_padding',
			[
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0.4,
					'right' => 0.4,
					'bottom' => 0.4,
					'left' => 0.4,
					'unit' => 'em',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} :is(input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'field_border-radius',
			[
				'label' => esc_html__( 'Border Radius', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} :is(input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'selector' => '{{WRAPPER}} :is(input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea)',
            ]
        );

        // finalizando a sessão dos campos
		$this->end_controls_section();

         // Iniciando a sessão dos botões
         $this->start_controls_section(
			'submit_section',
			[
				'label' => esc_html__( 'Submit', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} :is(.efb-form-group button)',
                'label' => 'Button typography',
            ]
        );

         // iniciando o wrapper das tabs
         $this->start_controls_tabs(
            'button_tabs'
        );
        
        // iniciando uma opção (tab)
        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'textdomain' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} :is(.efb-form-group button)' => 'color: {{VALUE}}',
                ],
                'default' => '#fff'
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} :is(.efb-form-group button)' => 'background-color: {{VALUE}}',
                ],
                'default' => '#000'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} :is(.efb-form-group button)',
            ]
        );

        $this->end_controls_tab();
        
        // iniciando uma opção (tab)
        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'textdomain' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} :is(.efb-form-group button):hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} :is(.efb-form-group button):hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border_hover',
                'selector' => '{{WRAPPER}} :is(.efb-form-group button):hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0.4,
					'right' => 1,
					'bottom' => 0.4,
					'left' => 1,
					'unit' => 'em',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} :is(.efb-form-group button)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'button_border-radius',
			[
				'label' => esc_html__( 'Border Radius', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} :is(.efb-form-group button)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
    
        // finalizando a sessão dos botões
		$this->end_controls_section();

    }

	protected function render(): void {

        $nonce = wp_create_nonce('wp_rest');

        wp_localize_script('emu-login-handler', 'apiData', [
            'nonce' => $nonce,
            'url'   => rest_url('emu_plugins/v1/'),
        ]);

        require_once( EFB_PLUGIN_PATH . '\inc\form-templates\login-register.php');
        
		$gSiteKey = get_option('efb_grecaptcha_key');
        $gSiteSecret = get_option('efb_grecaptcha_secret');

        if (!$gSiteKey || !$gSiteSecret){
        $gSiteKey = false;
        }
        echo '<div class="efb-multistep">';
        efb_login_register($gSiteKey);
        echo '</div>';
	}

	// protected function content_template(): void {
		
	// <!-- <p> Hello World </p> -->
	// }
}