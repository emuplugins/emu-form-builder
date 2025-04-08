<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BricksEfbLoginForm extends \Bricks\Element {

  // Element properties
  public $category     = 'general'; // Use predefined element category 'general'
  public $name         = 'efb-login-form'; // Make sure to prefix your elements
  public $icon         = 'ti-bolt-alt'; // Themify icon font class
  public $css_selector = '.efb-multistep'; // Default CSS selector
  public $scripts      = ['changeInputsByClass','EmuFormReady' ]; // Script(s) run when element is rendered on frontend or updated in builder

  // Return localised element label
  public function get_label() {
    return esc_html__( 'Login Form', 'bricks' );
  }

  // Set builder control groups
  public function set_control_groups() {
    $this->control_groups['Steps'] = [
      'title' => esc_html__( 'Steps', 'bricks' ),
      'tab' => 'content',
    ];
    $this->control_groups['Fields'] = [
      'title' => esc_html__( 'Fields', 'bricks' ),
      'tab' => 'content',
    ];
    $this->control_groups['Buttons'] = [
      'title' => esc_html__( 'Buttons', 'bricks' ),
      'tab' => 'content',
    ];
    $this->control_groups['StepTitles'] = [
      'title' => esc_html__( 'Step Titles', 'bricks' ),
      'tab' => 'content',
    ];
  }
 
  // Set builder controls
  public function set_controls() {

    // STEPS

    $this->controls['stepText'] = [
      'tab' => 'content',
      'group' => 'Steps',
      'label' => esc_html__( 'Step typography', 'bricks' ),
      'type' => 'typography',
      'css' => [
        [
          'property' => 'typography',
          'selector' => '.efb-steps li',

        ],
      ],
      'inline' => true,
    ];
    
    $this->controls['ActiveStepText'] = [
      'tab' => 'content',
      'group' => 'Steps',
      'label' => esc_html__( 'Active step typography', 'bricks' ),
      'type' => 'typography',
      'css' => [
        [
          'property' => 'typography',
          'selector' => '.efb-steps li.active',
        ],
      ],
      'inline' => true,
    ];

    $this->controls['stepBackgroundColor'] = [
      'tab' => 'content',
      'group' => 'Steps',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type' => 'color',
      'inline' => true,
      'css' => [
        [
          'property' => 'background-color',
          'selector' => '.efb-steps li',
        ]
      ],
      'default' => [
        'hex' => 'rgba(0, 0, 0, 0.56)',
      ],
    ];
  
    $this->controls['stepActiveBackgroundColor'] = [
      'tab' => 'content',
      'group' => 'Steps',
      'label' => esc_html__( 'Active background color', 'bricks' ),
      'type' => 'color',
      'inline' => true,
      'css' => [
        [
          'property' => 'background-color',
          'selector' => '.efb-steps li.active',
        ]
      ],
      'default' => [
        'hex' => ' #000000',
      ],
    ];

    $this->controls['stepBorder'] = [
      'tab' => 'content',
      'group' => 'Steps',
      'label' => esc_html__( 'Border', 'bricks' ),
      'type' => 'border',
      'css' => [
        [
          'property' => 'border',
          'selector' => '.efb-steps li',
        ],
      ],
      'inline' => true,
      'small' => true,
      'default' => [
        'width' => [
          'top' => 0,
          'right' => 0,
          'bottom' => 0,
          'left' => 0,
        ],
        'style' => 'none',
      ],

    ];
    $this->controls['stepBorderActive'] = [
      'tab' => 'content',
      'group' => 'Steps',
      'label' => esc_html__( 'Active Border', 'bricks' ),
      'type' => 'border',
      'css' => [
        [
          'property' => 'border',
          'selector' => '.efb-steps li.active',
        ],
      ],
      'inline' => true,
      'small' => true,
      'default' => [
        'width' => [
          'top' => 0,
          'right' => 0,
          'bottom' => 0,
          'left' => 0,
        ],
        'style' => 'none',
      ],

    ];

    $this->controls['stepsDimensions'] = [
      'tab' => 'content',
      'group' => 'Steps',
      'label' => esc_html__( 'Padding', 'bricks' ),
      'type' => 'dimensions',
      'css' => [
        [
          'property' => 'padding',
          'selector' => '.efb-steps li',
        ]
      ],
      'default' => [
        'top' => '0.8em',
        'right' => '0.8em',
        'bottom' => '0.8em',
        'left' => '0.8em',
      ],
    ];

    // ENDSTEPS

    // FIELDS
    $this->controls['labelTypography'] = [
      'tab' => 'content',
      'group' => 'Fields',
      'label' => esc_html__( 'Label typography', 'bricks' ),
      'type' => 'typography',
      'css' => [
        [
          'property' => 'typography',
          'selector' => '.efb-multistep label',
        ],
      ],
      'inline' => true,
    ];

    $this->controls['fieldTypography'] = [
      'tab' => 'content',
      'group' => 'Fields',
      'label' => esc_html__( 'Field typography', 'bricks' ),
      'type' => 'typography',
      'css' => [
        [
          'property' => 'typography',
          'selector' => '.efb-multistep input, .efb-multistep textarea',
        ],
      ],
      'inline' => true,
    ];

    $this->controls['fieldBackgroundColor'] = [
      'tab' => 'content',
      'group' => 'Fields',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type' => 'color',
      'inline' => true,
      'css' => [
        [
          'property' => 'background-color',
          'selector' => '.efb-multistep input, .efb-multistep textarea',
        ]
      ],
      'default' => [
        'hex' => '#deeaf5',
      ],
    ];

    $this->controls['fieldBorder'] = [
      'tab' => 'content',
      'group' => 'Fields',
      'label' => esc_html__( 'Border', 'bricks' ),
      'type' => 'border',
      'css' => [
        [
          'property' => 'border',
          'selector' => '.efb-multistep input, .efb-multistep textarea',
        ],
      ],
      'inline' => true,
      'small' => true,
      'default' => [
        'width' => [
          'top' => 1,
          'right' => 1,
          'bottom' => 1,
          'left' => 1,
        ],
        'style' => 'solid',
      ],

    ];

    $this->controls['fieldDimensions'] = [
      'tab' => 'content',
      'group' => 'Fields',
      'label' => esc_html__( 'Padding', 'bricks' ),
      'type' => 'dimensions',
      'css' => [
        [
          'property' => 'padding',
          'selector' => '.efb-multistep input, .efb-multistep textarea',
        ]
      ],
      'default' => [
        'top' => '0.8em',
        'right' => '0.8em',
        'bottom' => '0.8em',
        'left' => '0.8em',
      ],
    ];

    // ENDFIELDS

        // BUTTONS

        $this->controls['buttonText'] = [
          'tab' => 'content',
          'group' => 'Buttons',
          'label' => esc_html__( 'Typography', 'bricks' ),
          'type' => 'typography',
          'css' => [
            [
              'property' => 'typography',
              'selector' => '.emu-btn',
            ],
          ],
          'inline' => true,
        ];
        
        $this->controls['buttonTextHover'] = [
          'tab' => 'content',
          'group' => 'Buttons',
          'label' => esc_html__( 'Hover typography', 'bricks' ),
          'type' => 'typography',
          'css' => [
            [
              'property' => 'typography',
              'selector' => '.emu-btn:hover',
            ],
          ],
          'inline' => true,
        ];
    
        $this->controls['buttonBackgroundColor'] = [
          'tab' => 'content',
          'group' => 'Buttons',
          'label' => esc_html__( 'Background color', 'bricks' ),
          'type' => 'color',
          'inline' => true,
          'css' => [
            [
              'property' => 'background-color',
              'selector' => '.emu-btn',
            ]
          ],
          'default' => [
            'hex' => 'rgba(0, 0, 0, 0.56)',
          ],
        ];
      
        $this->controls['buttonBackgroundColorHover'] = [
          'tab' => 'content',
          'group' => 'Buttons',
          'label' => esc_html__( 'Hover background color', 'bricks' ),
          'type' => 'color',
          'inline' => true,
          'css' => [
            [
              'property' => 'background-color',
              'selector' => '.emu-btn:hover',
            ]
          ],
          'default' => [
            'hex' => 'rgb(41, 41, 41)',
          ],
        ];
    
        $this->controls['buttonBorder'] = [
          'tab' => 'content',
          'group' => 'Buttons',
          'label' => esc_html__( 'Border', 'bricks' ),
          'type' => 'border',
          'css' => [
            [
              'property' => 'border',
              'selector' => '.emu-btn',
            ],
          ],
          'inline' => true,
          'small' => true,
          'default' => [
            'width' => [
              'top' => 0,
              'right' => 0,
              'bottom' => 0,
              'left' => 0,
            ],
            'style' => 'none',
          ],
    
        ];
        $this->controls['buttonBorderHover'] = [
          'tab' => 'content',
          'group' => 'Buttons',
          'label' => esc_html__( 'Hover Border', 'bricks' ),
          'type' => 'border',
          'css' => [
            [
              'property' => 'border',
              'selector' => '.emu-btn:hover',
            ],
          ],
          'inline' => true,
          'small' => true,          
        ];

        $this->controls['buttonDimensions'] = [
          'tab' => 'content',
          'group' => 'Buttons',
          'label' => esc_html__( 'Button padding', 'bricks' ),
          'type' => 'dimensions',
          'css' => [
            [
              'property' => 'padding',
              'selector' => '.emu-btn',
            ]
          ],
          'default' => [
            'top' => '0.8em',
            'right' => '0.8em',
            'bottom' => '0.8em',
            'left' => '0.8em',
          ],
        ];
    
        // ENDBUTTONS

        // Step Titles
    $this->controls['stepTitleTypography'] = [
      'tab' => 'content',
      'group' => 'StepTitles',
      'label' => esc_html__( 'Typography', 'bricks' ),
      'type' => 'typography',
      'css' => [
        [
          'property' => 'typography',
          'selector' => '.efb-step-title',
        ],
      ],
      'inline' => true,
    ];

    $this->controls['stepTitleBackgroundColor'] = [
      'tab' => 'content',
      'group' => 'StepTitles',
      'label' => esc_html__( 'Background color', 'bricks' ),
      'type' => 'color',
      'inline' => true,
      'css' => [
        [
          'property' => 'background-color',
          'selector' => '.efb-step-title',
        ]
      ],
      'default' => [
        'hex' => '#deeaf5',
      ],
    ];

    $this->controls['stepTitleBorder'] = [
      'tab' => 'content',
      'group' => 'StepTitles',
      'label' => esc_html__( 'Border', 'bricks' ),
      'type' => 'border',
      'css' => [
        [
          'property' => 'border',
          'selector' => '.efb-step-title',
        ],
      ],
      'inline' => true,
      'small' => true,
      'default' => [
        'width' => [
          'top' => 1,
          'right' => 1,
          'bottom' => 1,
          'left' => 1,
        ],
        'style' => 'solid',
      ],

    ];

    $this->controls['stepTitleDimensions'] = [
      'tab' => 'content',
      'group' => 'StepTitles',
      'label' => esc_html__( 'Padding', 'bricks' ),
      'type' => 'dimensions',
      'css' => [
        [
          'property' => 'padding',
          'selector' => '.efb-step-title',
        ]
      ],
      'default' => [
        'top' => '0.8em',
        'right' => '0.8em',
        'bottom' => '0.8em',
        'left' => '0.8em',
      ],
    ];

    // ENDStepTitles
    
  }

  // Enqueue element styles and scripts
  public function enqueue_scripts() {

  $gSiteKey = get_option('efb_grecaptcha_key');
  $gSiteSecret = get_option('efb_grecaptcha_secret');

  if (!empty($gSiteKey) && !empty($gSiteSecret)) {
      wp_enqueue_script(
          'google-recaptcha',
          'https://www.google.com/recaptcha/api.js',
          [],
          null,
      );
  }
    
    wp_enqueue_style(
      'emu-login-handler',
      EFB_PLUGIN_URL . 'assets/css/form.css',
      array(),
      time()
  );

    wp_enqueue_script(
        'emu-form-js',
        EFB_PLUGIN_URL . 'assets/js/form.js',
        array(),
        time() 
    );

    wp_enqueue_script(
        'emu-login-handler',
        EFB_PLUGIN_URL . 'assets/js/login.js',
        array(),
        time() 
    );

    $nonce = wp_create_nonce( 'wp_rest' );
    wp_localize_script( 'emu-login-handler', 'apiData', [
        'nonce' => $nonce,
        'url'   => rest_url( 'emu_plugins/v1/' ),
    ]); 

  }

  // Render element HTML
  public function render() {
    // Set element attributes
    $root_classes[] = 'efb-multistep-wrapper';

    // Add 'class' attribute to element root tag
    $this->set_attribute( '_root', 'class', $root_classes );
    $this->set_attribute( 'child', 'class', 'efb-multistep' );

    // Render element HTML
    
    // '_root' attribute is required (contains element ID, class, etc.)
    echo "<div {$this->render_attributes( '_root' )}>"; // Element root attributes
    echo "<div {$this->render_attributes('child')}>";

    $gSiteKey = get_option('efb_grecaptcha_key');
    $gSiteSecret = get_option('efb_grecaptcha_secret');

    if (!$gSiteKey || !$gSiteSecret){
      $gSiteKey = false;
    }

    efb_login_register($gSiteKey);

    echo '</div></div>';
  }
}