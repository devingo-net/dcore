<?php

/**
 *  Customize Elementor Widget Base
 *
 * @category    Elementor
 * @version     1.4.5
 * @since       1.0.0
 */

namespace DCore\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use DCore\Configs;
use DCore\Elementor;
use DCore\Helper;
use DCore\Theme;

/**
 * Class DC_Widget
 *
 * @package DCore\Elementor
 */
class Widget extends Widget_Base {
	protected string $class         = '';
	protected string $name          = '';
	protected string $title         = '';
	protected string $icon          = '';
	protected array  $categories    = [];
	protected        $widgetStyles  = [];
	protected array  $jsTemplates   = [];
	public           $cardStyle     = false;
	public array     $styles        = [];
	public           $widgetConfigs = [];

	/**
	 * DC_Widget constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct (array $data = [], $args = null) {
		$this->class         = strtolower(substr(strrchr(static::class, "\\"), 1));
		$this->widgetConfigs = Configs::$shortcodes[$this->class] ?? false;
		if ( $this->widgetConfigs !== false ) {
			$this->widgetStyles = Elementor::getWidgetStyles($this->widgetConfigs);
		}

		if ( $this->widgetConfigs !== false && isset($this->widgetConfigs['js-file']) ) {
			wp_enqueue_script(THEME_PREFIX . '-widget-' . $this->class, get_template_directory_uri() . $this->widgetConfigs['js-file']);
		}

		if ( $this->widgetConfigs !== false && isset($this->widgetConfigs['css-file']) ) {
			wp_enqueue_style(THEME_PREFIX . '-widget-' . $this->class, get_template_directory_uri() . $this->widgetConfigs['css-file']);
		}


		parent::__construct($data, $args);
	}

	/**
	 * @inheritDoc
	 *
	 * @return bool
	 */
	public function show_in_panel () : bool {
		return $this->widgetStyles !== false;
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function get_name () : string {
		return Theme::getShortCodeConfig($this->class, 'widgetName', $this->name);
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function get_title () : string {
		return Theme::getShortCodeConfig($this->class, 'widgetTitle', $this->title);
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function get_icon () : string {
		return Theme::getShortCodeConfig($this->class, 'widgetIcon', $this->icon);
	}

	/**
	 * @inheritDoc
	 *
	 * @return array
	 */
	public function get_categories () : array {
		$categories = [];
		foreach ( $this->categories as $category ) {
			$categories[] = str_replace('prefix::', THEME_PREFIX . '_', $category);
		}

		return $categories;
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls () : void {
		if ( $this->widgetStyles !== false ) {
			$this->start_controls_section('widget_style_settings_section', [
				'label' => __('Widget Designs', THEME_TEXTDOMAIN),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]);

			$widgetStyleOptions = [];
			$widgetColorOptions = [];

			foreach ( $this->widgetStyles as $widget_style ) {
				$widgetStyleOptions[$widget_style['name']] = $widget_style['title'];
				if ( Helper::isSet($widget_style, 'colors') ) {
					$widgetColorOptions[$widget_style['name']] = $widget_style['colors'];
				}
			}

			$this->add_control('widgetStyle', [
				'label'   => __('Widget Style', THEME_TEXTDOMAIN),
				'type'    => Controls_Manager::SELECT,
				'default' => array_key_first($widgetStyleOptions),
				'options' => $widgetStyleOptions,
			]);

			$this->end_controls_section();

			if ( !empty($widgetColorOptions) ) {
				$parsedOption = [];
				$this->start_controls_section('widget_style_colors_section', [
					'label' => __('Colors', THEME_TEXTDOMAIN),
					'tab'   => Controls_Manager::TAB_STYLE,
				]);

				foreach ( $widgetColorOptions as $key => $colors ) {
					foreach ( $colors as $colorName => $colorOpts ) {
						if ( !Helper::isSet($colorOpts, 'name') ) {
							continue;
						}

						if ( isset($parsedOption[$colorName]['conditions']['widgetStyle']) ) {
							$parsedOption[$colorName]['conditions']['widgetStyle'][] = $key;
							continue;
						}

						$parsedOption[$colorName] = [
							'name'       => $colorOpts['name'],
							'conditions' => [
								                'widgetStyle' => [$key]
							                ] + ($colorOpts['conditions'] ?? [])
						];

					}
				}
				if ( !empty($parsedOption) ) {
					foreach ( $parsedOption as $key => $item ) {
						$this->add_control($key, [
							'label'     => $item['name'] ?? $key,
							'type'      => Controls_Manager::COLOR,
							'condition' => $item['conditions'] ?? []
						]);
					}
				}

				$this->end_controls_section();
			}
		}


		$this->start_controls_section('widget_visibility_settings_section', [
			'label' => __('Visibility', THEME_TEXTDOMAIN),
			'tab'   => Controls_Manager::TAB_ADVANCED,
		]);


		$this->add_control('hideBeforeLogin', [
			'label'        => __('Hide Before Login', THEME_TEXTDOMAIN),
			'desc'         => __('The html would not be rendered', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'yes',
			'default'      => 'no',
		]);
		$this->add_control('hideAfterLogin', [
			'label'        => __('Hide After Login', THEME_TEXTDOMAIN),
			'desc'         => __('The html would not be rendered', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'yes',
			'default'      => 'no',
		]);

		$this->add_control('hideInMobile', [
			'label'        => __('Hide in Mobile', THEME_TEXTDOMAIN),
			'desc'         => __('The html would not be rendered', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'yes',
			'default'      => 'no',
		]);

		$this->add_control('hideInDesktop', [
			'label'        => __('Hide in Desktop', THEME_TEXTDOMAIN),
			'desc'         => __('The html would not be rendered', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'yes',
			'default'      => 'no',
		]);

		$this->end_controls_section();

		parent::register_controls();
	}

	/**
	 * settings parse
	 *
	 * @param array $settings
	 *
	 * @return void
	 */
	protected function settingsParse (array &$settings) : void {
	}

	/**
	 * manage render method
	 *
	 * @return bool
	 */
	protected function renderIf () : bool {
		return true;
	}


	/**
	 * @inheritDoc
	 */
	protected function render () : void {
		if ( !$this->renderIf() ) {
			return;
		}

		$settings             = $this->get_settings_for_display();
		$settings['widgetID'] = $this->get_id();

		if ( !is_admin() && $settings['hideInMobile'] === 'yes' && wp_is_mobile() ) {
			return;
		}
		if ( !is_admin() && $settings['hideInDesktop'] === 'yes' && !wp_is_mobile() ) {
			return;
		}
		if ( !is_admin() && $settings['hideBeforeLogin'] === 'yes' && !is_user_logged_in() ) {
			return;
		}
		if ( !is_admin() && $settings['hideAfterLogin'] === 'yes' && is_user_logged_in() ) {
			return;
		}

		if ( isset($settings['cardStyle']) && $this->cardStyle !== false ) {
			$settings['cardStyle'] = THEME_TEMPLATES_DIR . DSP . 'globals' . DSP . 'cards' . DSP . $this->cardStyle . DSP . $settings['cardStyle'];
		}

		$this->settingsParse($settings);

		$widgetClasses = [
			THEME_PREFIX . '-widget',
			THEME_PREFIX . '-' . strtolower($this->class) . '-widget'
		];

		if ( $this->widgetStyles !== false && count($this->widgetStyles) > 1 ) {
			$widgetClasses[] = THEME_PREFIX . '-' . strtolower($this->class) . '-widget-style-' . $settings['widgetStyle'];
		}
		if ( Helper::isSet($settings, 'widgetClasses') ) {
			$widgetClasses = array_merge($widgetClasses, $settings['widgetClasses']);
		}

		$templateFile = '';

		if ( $this->widgetStyles !== false ) {
			foreach ( $this->widgetStyles as $widget_style ) {
				if ( isset($settings['widgetStyle']) && $widget_style['name'] === $settings['widgetStyle'] ) {
					$templateFile = $widget_style['template'];
					break;
				}
			}
			if ( empty($templateFile) ) {
				$templateFile = $this->widgetStyles[0]['template'] ?? '';
			}
		}

		echo '<div class="' . implode(' ', $widgetClasses) . '">';

		Theme::getTemplatePart(get_template_directory() . $templateFile . 'index.php', $settings, true);
		$this->afterRender($settings);

		echo '</div>';

		if ( !empty($this->jsTemplates) ) {
			$this->registerJSTemplate();
		}

		$styleConfigs = Theme::getShortcodeFileContent($this->class, 'style.json');
		if ( $styleConfigs !== false && !empty($styleConfigs) ) {
			try {
				$styleConfigs = json_decode($styleConfigs, true, 512, JSON_THROW_ON_ERROR);
			} catch ( \JsonException $e ) {
				$styleConfigs = [];
			}
		}
		if ( !empty($styleConfigs) ) {
			foreach ( $styleConfigs as $selector => $property ) {
				if ( preg_match_all('/\[(.*?)\]/', $property, $matchedSettings, PREG_SET_ORDER) > 0 ) {
					foreach ( $matchedSettings as $matchedSetting ) {
						if ( !isset($matchedSetting[1]) ) {
							continue;
						}

						$matchedSetting[1] = explode(':', $matchedSetting[1]);
						$optKey            = $matchedSetting[1][0];
						$colorAlpha        = $matchedSetting[1][1] ?? 100;

						if ( !Helper::isSet($settings, $optKey) ) {
							continue 2;
						}

						$property = str_replace($matchedSetting[0], Helper::hexToRgba($settings[$optKey], $colorAlpha), $property);
					}
				}

				if ( strpos('[', $property) !== false || strpos(']', $property) !== false ) {
					continue;
				}

				$this->styles['{{WRAPPER}} ' . $selector] = $property;
			}
		}


		if ( !empty($this->styles) ) {
			$this->inlineStyles($this->styles);
		}
		wp_reset_postdata();
	}

	/**
	 * @param string $title
	 * @param string $selector
	 * @param string $prefix
	 * @param array  $condition
	 * @param array  $excludes
	 * @param array  $modes
	 */
	protected function addStyleOptions (
		string $title, string $selector, string $prefix = '', array $condition = [], array $excludes = [], array $modes = []
	) : void {
		$selector = '{{WRAPPER}} ' . $selector;

		$this->start_controls_section($prefix . '_styles', [
			'label'     => $title,
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => $condition
		]);
		$modesDefault = [
			':normal' => __('Normal', THEME_TEXTDOMAIN),
			':hover'  => __('Hover', THEME_TEXTDOMAIN),
			':focus'  => __('Focus', THEME_TEXTDOMAIN)
		];

		if ( empty($modes) ) {
			$modes = $modesDefault;
		}
		if ( count($modes) > 1 ) {
			$this->start_controls_tabs($prefix . '_styles_section_tabs');
		}

		$excludes = empty($excludes) ? ['flex'] : $excludes;


		foreach ( $modes as $key => $val ) {
			$keyName = str_replace([':', '.', '#'], '', $key);

			$modOptionsSelector = '{{WRAPPER}} .' . $prefix . '-' . $keyName . '-';
			$modSelector        = $selector;


			if ( strpos($modSelector, '{{mode}}') === false ) {
				if ( $keyName !== 'normal' ) {
					$modSelector .= $key;
				}
			} else if ( $keyName === 'normal' ) {
				$modSelector = str_replace('{{mode}}', '', $modSelector);
			} else {
				$modSelector = str_replace('{{mode}}', $key, $modSelector);
			}


			if ( count($modes) > 1 ) {
				$this->start_controls_tab($prefix . '_styles_section_' . $keyName, [
					'label' => $val,
				]);
			}


			if ( !in_array('opacity', $excludes, false) ) {
				$this->add_responsive_control($prefix . 'Opacity' . $keyName, [
					'label'          => __('Opacity', THEME_TEXTDOMAIN),
					'type'           => Controls_Manager::SLIDER,
					'default'        => [
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'size_units'     => ['%'],
					'range'          => [
						'%' => [
							'min' => 0,
							'max' => 100,
						]
					],
					'selectors'      => [
						$modSelector             => 'opacity: {{SIZE}}{{UNIT}};',
						$modOptionsSelector . 'opacity' . (in_array($keyName, [
							'hover',
							'focus',
							'active'
						]) ? ":{$keyName}" : '') => 'opacity: {{SIZE}}{{UNIT}};',
					],
				]);
			}

			if ( !in_array('flex', $excludes, false) ) {

				$this->add_responsive_control($prefix . 'FlexAlign' . $keyName, [
					'label'     => __('Box Alignment', THEME_TEXTDOMAIN),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'         => [
							'title' => __('Left', THEME_TEXTDOMAIN),
							'icon'  => 'eicon-text-align-left',
						],
						'center'       => [
							'title' => __('Center', THEME_TEXTDOMAIN),
							'icon'  => 'eicon-text-align-center',
						],
						'right'        => [
							'title' => __('Right', THEME_TEXTDOMAIN),
							'icon'  => 'eicon-text-align-right',
						],
						'space-around' => [
							'title' => __('Justified', THEME_TEXTDOMAIN),
							'icon'  => 'eicon-text-align-justify',
						],
					],
					'selectors' => [
						$modSelector                            => 'justify-content: {{VALUE}};',
						$modOptionsSelector . 'justify-content' => 'justify-content: {{VALUE}};',
					],
				]);
			}


			if ( !in_array('font', $excludes, false) ) {

				if ( !in_array('typography', $excludes, false) ) {
					$this->add_group_control(Group_Control_Typography::get_type(), [
						'name'     => $prefix . 'Typography' . $keyName,
						'label'    => __('Typography', THEME_TEXTDOMAIN),
						'selector' => $modSelector . ' , ' . $modOptionsSelector . 'font',
						'exclude'  => [
							'font_size',
						],
					]);
				}

				if ( !in_array('fontsize', $excludes, false) ) {
					$this->add_responsive_control($prefix . 'FontSize' . $keyName, [
						'label'          => __('Font Size', THEME_TEXTDOMAIN),
						'type'           => Controls_Manager::SLIDER,
						'default'        => [
							'unit' => '%',
						],
						'tablet_default' => [
							'unit' => '%',
						],
						'mobile_default' => [
							'unit' => '%',
						],
						'size_units'     => ['%', 'px'],
						'range'          => [
							'%'  => [
								'min' => 1,
								'max' => 100,
							],
							'px' => [
								'min' => 1,
								'max' => 1000,
							]
						],
						'selectors'      => [
							$modSelector                               => 'font-size: {{SIZE}}{{UNIT}};',
							$modOptionsSelector . 'font-size'          => 'font-size: {{SIZE}}{{UNIT}};',
							$modSelector . ' svg'                      => 'height: {{SIZE}}{{UNIT}};',
							$modOptionsSelector . 'font-size' . ' svg' => 'height: {{SIZE}}{{UNIT}};',
						],
					]);
				}

				if ( !in_array('textalign', $excludes, false) ) {
					$this->add_responsive_control($prefix . 'Align' . $keyName, [
						'label'     => __('Alignment', THEME_TEXTDOMAIN),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => [
							'left'    => [
								'title' => __('Left', THEME_TEXTDOMAIN),
								'icon'  => 'eicon-text-align-left',
							],
							'center'  => [
								'title' => __('Center', THEME_TEXTDOMAIN),
								'icon'  => 'eicon-text-align-center',
							],
							'right'   => [
								'title' => __('Right', THEME_TEXTDOMAIN),
								'icon'  => 'eicon-text-align-right',
							],
							'justify' => [
								'title' => __('Justified', THEME_TEXTDOMAIN),
								'icon'  => 'eicon-text-align-justify',
							],
						],
						'selectors' => [
							$modSelector                       => 'text-align: {{VALUE}};',
							$modOptionsSelector . 'text-align' => 'text-align: {{VALUE}};',
						],
					]);
				}

				if ( !in_array('textcolor', $excludes, false) ) {
					$this->add_responsive_control($prefix . 'TextColor' . $keyName, [
						'label'     => __('Text Color', THEME_TEXTDOMAIN),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							$modSelector                  => 'color: {{VALUE}}',
							$modSelector . ' svg'         => 'fill: {{VALUE}}; border-color: {{VALUE}}',
							$modOptionsSelector . 'color' => 'color: {{VALUE}}',
						],
					]);
				}

				if ( !in_array('textshadow', $excludes, false) ) {
					$this->add_group_control(Group_Control_Text_Shadow::get_type(), [
						'name'     => $prefix . 'TextShadow' . $keyName,
						'label'    => __('Text Shadow', THEME_TEXTDOMAIN),
						'selector' => $modSelector . ' , ' . $modOptionsSelector . 'text-shadow',
					]);
				}

			}

			if ( !in_array('border', $excludes, false) ) {
				$this->add_group_control(Group_Control_Box_Shadow::get_type(), [
					'name'     => $prefix . 'BoxShadow' . $keyName,
					'label'    => __('Box Shadow', THEME_TEXTDOMAIN),
					'selector' => $modSelector . ' , ' . $modOptionsSelector . 'box-shadow',
				]);
				$this->add_group_control(Group_Control_Border::get_type(), [
					'name'     => $prefix . 'Border' . $keyName,
					'label'    => __('Border', THEME_TEXTDOMAIN),
					'selector' => $modSelector . ' , ' . $modOptionsSelector . 'border',
				]);
				$this->add_responsive_control($prefix . 'BorderRadius' . $keyName, [
					'label'      => __('Border Radius', THEME_TEXTDOMAIN),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'selectors'  => [
						$modSelector                          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						$modOptionsSelector . 'border-radius' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]);
			}


			if ( !in_array('width', $excludes, false) ) {
				$this->add_responsive_control($prefix . 'Width' . $keyName, [
					'label'          => __('Width', THEME_TEXTDOMAIN),
					'type'           => Controls_Manager::SLIDER,
					'default'        => [
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'size_units'     => ['%', 'px', 'vw'],
					'range'          => [
						'%'  => [
							'min' => 1,
							'max' => 100,
						],
						'px' => [
							'min' => 1,
							'max' => 1000,
						],
						'vw' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'selectors'      => [
						$modSelector                  => 'width: {{SIZE}}{{UNIT}};',
						$modOptionsSelector . 'width' => 'width: {{SIZE}}{{UNIT}};',
					],
				]);
			}
			if ( !in_array('height', $excludes, false) ) {
				$this->add_responsive_control($prefix . 'Height' . $keyName, [
					'label'          => __('Height', THEME_TEXTDOMAIN),
					'type'           => Controls_Manager::SLIDER,
					'default'        => [
						'unit' => 'px',
					],
					'tablet_default' => [
						'unit' => 'px',
					],
					'mobile_default' => [
						'unit' => 'px',
					],
					'size_units'     => ['px', 'vh'],
					'range'          => [
						'px' => [
							'min' => 1,
							'max' => 500,
						],
						'vh' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'selectors'      => [
						$modSelector                   => 'height: {{SIZE}}{{UNIT}} !important; line-height: {{SIZE}}{{UNIT}};',
						$modOptionsSelector . 'height' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					],
				]);
			}

			if ( !in_array('padding', $excludes, false) ) {
				$this->add_responsive_control($prefix . 'Padding' . $keyName, [
					'label'      => __('Padding', THEME_TEXTDOMAIN),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'selectors'  => [
						$modSelector                    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
						$modOptionsSelector . 'padding' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]);
			}
			if ( !in_array('margin', $excludes, false) ) {
				$this->add_responsive_control($prefix . 'Margin' . $keyName, [
					'label'      => __('Margin', THEME_TEXTDOMAIN),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => ['px', '%'],
					'selectors'  => [
						$modSelector                   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
						$modOptionsSelector . 'margin' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]);
			}

			if ( !in_array('background', $excludes, false) ) {
				$this->add_group_control(Group_Control_Background::get_type(), [
					'name'     => $prefix . 'Background' . $keyName,
					'label'    => __('Background', THEME_TEXTDOMAIN),
					'types'    => ['classic', 'gradient'],
					'selector' => $modSelector . ' , ' . $modOptionsSelector . 'background',
				]);
			}

			if ( count($modes) > 1 ) {
				$this->end_controls_tab();
			}
		}

		if ( count($modes) > 1 ) {
			$this->end_controls_tabs();
		}

		$this->end_controls_section();
	}

	/**
	 * @inheritDoc
	 *
	 * @param       $section_id
	 * @param array $args
	 */
	public function start_controls_section ($section_id, array $args = []) : void {
		$condition = [];
		if ( !empty($this->widgetStyles) && $this->widgetStyles !== false ) {
			foreach ( $this->widgetStyles as $widgetStyle ) {
				if ( !Helper::isSet($widgetStyle, 'disabledOptions') ) {
					continue;
				}
				if ( !in_array($section_id, $widgetStyle['disabledOptions'], true) ) {
					continue;
				}
				$condition['widgetStyle!'][] = $widgetStyle['name'];
			}
		}

		if ( !empty($condition) ) {
			$args['condition'] = Helper::isSet($args, 'condition') ? array_merge($args['condition'], $condition) : $condition;
		}

		if ( Helper::isSet($args, 'hasStyle', true) ) {
			$this->addStyleOptions($args['label'] ?? '', $args['selector'] ?? '', $args['prefix'] ?? '', $args['condition'] ?? [], $args['excludes'] ?? [], $args['modes'] ?? []);
		}

		parent::start_controls_section($section_id, $args);
	}

	/**
	 * @param string $id
	 * @param array  $args
	 * @param array  $options
	 *
	 * @return bool|void
	 */
	public function add_control ($id, array $args, $options = []) {

		if ( Helper::isSet($args, 'default') ) {
			$args['default'] = Theme::getShortCodeConfig($this->class, $id, $args['default']);
		}

		parent::add_control($id, $args, $options);
	}

	/**
	 * register js templates
	 */
	public function registerJSTemplate () : void {
		foreach ( $this->jsTemplates as $name ) {
			$template = Theme::getShortcodeFileContent($this->class, $name);
			if ( $template === false ) {
				return;
			}

			$filename = explode('.', $name);
			$id       = 'tmpl-theme-widget-' . $filename[0];
			if ( isset($GLOBALS[$id]) ) {
				return;
			}
			$GLOBALS[$id] = 1;
			echo '<script type="text/html" id="' . $id . '">' . PHP_EOL;
			echo $template;
			echo PHP_EOL . '</script>';
		}
	}

	/**
	 * @param $settings
	 */
	public function afterRender ($settings) : void {

	}

	/**
	 * @param string $id
	 * @param array  $args
	 */
	public function addCardStyles (string $id = 'cardStyle', array $args = []) : void {
		if ( $this->cardStyle === false ) {
			return;
		}

		$templates = Configs::$globalCards[$this->cardStyle] ?? [];
		if ( empty($templates) ) {
			return;
		}

		$this->add_control($id, [
			'label'   => __('Card Style', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::SELECT,
			'default' => array_key_first($templates),
			'options' => $templates,
		]);
	}

	public function addWidgetStyles () : void {

		$this->start_controls_section('Widget_Style_settings', [
			'label' => __('Widget Colors', THEME_TEXTDOMAIN),
			'tab'   => Controls_Manager::TAB_STYLE,
		]);


		$this->add_control('primaryColor', [
			'label' => __('Primary Color', THEME_TEXTDOMAIN),
			'type'  => Controls_Manager::COLOR,
		]);

		$this->add_control('secondaryColor', [
			'label' => __('Secondary Color', THEME_TEXTDOMAIN),
			'type'  => Controls_Manager::COLOR,
		]);

		$this->end_controls_section();
	}

	/**
	 * add Swiper carousel options controllers
	 *
	 * @param array $defaults
	 */
	public function addSwiperCarouselOptions (array $defaults = []) : void {

		$this->start_controls_section('Slider_settings', [
			'label'    => __('Slider', THEME_TEXTDOMAIN),
			'tab'      => Controls_Manager::TAB_CONTENT,
			'hasStyle' => true,
			'prefix'   => 'slider',
			'selector' => '.slider-wrapper .swiper-slide{{mode}} .slide-item',
			'modes'    => [
				':normal'              => __('Normal', THEME_TEXTDOMAIN),
				'.swiper-slide-active' => __('Active', THEME_TEXTDOMAIN)
			],
			'excludes' => [
				'font',
				'width',
				'height',
				'flex',
			]
		]);
		$this->add_control('sliderDirection', [
			'label'   => __('Slider Direction', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::SELECT,
			'default' => 'horizontal',
			'options' => [
				'horizontal' => __('Horizontal', THEME_TEXTDOMAIN),
				'vertical'   => __('Vertical', THEME_TEXTDOMAIN),
			]
		]);
		$this->add_responsive_control('verticalSliderHeight', [
			'label'           => __('Slider Height', THEME_TEXTDOMAIN),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => ['px', '%', 'vh'],
			'range'           => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'vh' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop_default' => $defaults['verticalSliderHeight_desktop'] ?? [
					'unit' => 'px',
					'size' => 300,
				],
			'tablet_default'  => $defaults['verticalSliderHeight_tablet'] ?? [
					'unit' => 'px',
					'size' => 300,
				],
			'mobile_default'  => $defaults['verticalSliderHeight_mobile'] ?? [
					'unit' => 'px',
					'size' => 300,
				],
			'selectors'       => [
				'{{WRAPPER}} .swiper-container' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				'sliderDirection' => 'vertical'
			]
		]);
		$this->add_control('slideAnimation', [
			'label'   => __('Slide Animation', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::SELECT,
			'default' => $defaults['slideAnimation'] ?? 'none',
			'options' => [
				'none'      => __('None', THEME_TEXTDOMAIN),
				'fade'      => __('Fade', THEME_TEXTDOMAIN),
				'coverflow' => __('CoverFlow', THEME_TEXTDOMAIN),
				'flip'      => __('Flip Effect', THEME_TEXTDOMAIN),
				'cube'      => __('Cube Effect', THEME_TEXTDOMAIN),
			]
		]);
		$this->add_control('slidesCountMode', [
			'label'        => __('Slides Count Mode', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('Count', THEME_TEXTDOMAIN),
			'label_off'    => __('Auto', THEME_TEXTDOMAIN),
			'return_value' => 'count',
			'default'      => $defaults['slidesCountMode'] ?? 'count',
		]);
		$this->add_responsive_control('slidesPerView', [
			'label'           => __('Slides Per View', THEME_TEXTDOMAIN),
			'type'            => Controls_Manager::NUMBER,
			'min'             => 1,
			'max'             => 20,
			'step'            => 1,
			'desktop_default' => $defaults['slidesPerView_desktop'] ?? 5,
			'tablet_default'  => $defaults['slidesPerView_tablet'] ?? 3,
			'mobile_default'  => $defaults['slidesPerView_mobile'] ?? 2,
			'condition'       => [
				'slidesCountMode' => 'count'
			]
		]);
		$this->add_control('slidesWidth', [
			'label'      => __('Slides Width', THEME_TEXTDOMAIN),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => ['px', '%'],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 300,
			],
			'selectors'  => [
				'{{WRAPPER}} .swiper-wrapper .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'slidesCountMode!' => 'count',
				'sliderDirection'  => 'horizontal'
			]
		]);
		$this->add_responsive_control('verticalSlidesHeight', [
			'label'           => __('Slides Height', THEME_TEXTDOMAIN),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => ['px', '%'],
			'range'           => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop_default' => $defaults['verticalSlidesHeight_desktop'] ?? [
					'unit' => 'px',
					'size' => 300,
				],
			'tablet_default'  => $defaults['verticalSlidesHeight_tablet'] ?? [
					'unit' => 'px',
					'size' => 300,
				],
			'mobile_default'  => $defaults['verticalSlidesHeight_mobile'] ?? [
					'unit' => 'px',
					'size' => 300,
				],
			'selectors'       => [
				'{{WRAPPER}} .swiper-wrapper .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				'slidesCountMode!' => 'count',
				'sliderDirection'  => 'vertical'
			]
		]);

		$this->add_control('slidesColumn', [
			'label'        => __('Slides Column', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'true',
			'default'      => 'false',
			'condition'    => [
				'slidesCountMode' => 'count'
			]
		]);
		$this->add_control('slidesPerColumn', [
			'label'     => __('Slides Per Column', THEME_TEXTDOMAIN),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 2,
			'max'       => 20,
			'step'      => 1,
			'default'   => 2,
			'condition' => [
				'slidesCountMode' => 'count',
				'slidesColumn'    => 'true'
			]
		]);
		$this->add_responsive_control('slidesHeight', [
			'label'           => __('Slides Height', THEME_TEXTDOMAIN),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => ['px', '%'],
			'range'           => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop_default' => $defaults['slidesHeight_desktop'] ?? [
					'unit' => 'px',
					'size' => 500,
				],
			'tablet_default'  => $defaults['slidesHeight_tablet'] ?? [
					'unit' => 'px',
					'size' => 500,
				],
			'mobile_default'  => $defaults['slidesHeight_mobile'] ?? [
					'unit' => 'px',
					'size' => 500,
				],
			'selectors'       => [
				'{{WRAPPER}} .swiper-wrapper' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				'slidesCountMode' => 'count',
				'slidesColumn'    => 'true'
			]
		]);
		$this->add_control('slidesPerGroup', [
			'label'   => __('Slides Per Group', THEME_TEXTDOMAIN),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1,
			'max'     => 20,
			'step'    => 1,
			'default' => 1
		]);
		$this->add_responsive_control('spaceBetween', [
			'label'           => __('Space Between', THEME_TEXTDOMAIN),
			'type'            => Controls_Manager::NUMBER,
			'min'             => 1,
			'max'             => 500,
			'step'            => 5,
			'desktop_default' => 10,
			'tablet_default'  => 10,
			'mobile_default'  => 10,
		]);
		$this->add_control('centeredSlides', [
			'label'        => __('Centered Slides', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'true',
			'default'      => 'false'
		]);
		$this->add_control('allowTouchMove', [
			'label'        => __('Allow Touch Move', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'true',
			'default'      => $defaults['allowTouchMove'] ?? 'true'
		]);
		$this->add_control('freeMode', [
			'label'        => __('Free Mode', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'true',
			'default'      => 'false',
			'condition'    => [
				'allowTouchMove' => 'true'
			]
		]);
		$this->add_control('infiniteLoop', [
			'label'        => __('Infinite Loop', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'true',
			'default'      => $defaults['infiniteLoop'] ?? 'false'
		]);
		$this->add_control('autoPlay', [
			'label'        => __('Auto Play', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'true',
			'default'      => $defaults['autoPlay'] ?? 'false'
		]);
		$this->add_control('autoPlayDuration', [
			'label'     => __('Auto Play Duration', THEME_TEXTDOMAIN),
			'type'      => Controls_Manager::NUMBER,
			'default'   => $defaults['autoPlayDuration'] ?? 6000,
			'condition' => [
				'autoPlay' => 'true'
			]
		]);
		$this->add_responsive_control('sliderNavs', [
			'label'           => __('Slider Navigations', THEME_TEXTDOMAIN),
			'type'            => Controls_Manager::SWITCHER,
			'label_on'        => __('True', THEME_TEXTDOMAIN),
			'label_off'       => __('False', THEME_TEXTDOMAIN),
			'return_value'    => 'true',
			'desktop_default' => $defaults['sliderNavs'] ?? 'true',
			'tablet_default'  => $defaults['sliderNavs_tablet'] ?? 'true',
			'mobile_default'  => $defaults['sliderNavs_mobile'] ?? 'true',
		]);


		$this->add_responsive_control('sliderPagination', [
			'label'           => __('Slider Pagination', THEME_TEXTDOMAIN),
			'type'            => Controls_Manager::SWITCHER,
			'label_on'        => __('True', THEME_TEXTDOMAIN),
			'label_off'       => __('False', THEME_TEXTDOMAIN),
			'return_value'    => 'true',
			'desktop_default' => $defaults['sliderPagination'] ?? 'true',
			'tablet_default'  => $defaults['sliderPagination_tablet'] ?? 'true',
			'mobile_default'  => $defaults['sliderPagination_mobile'] ?? 'true'
		]);


		$this->add_control('sliderScrollbar', [
			'label'        => __('Slider Scroll Bar', THEME_TEXTDOMAIN),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __('True', THEME_TEXTDOMAIN),
			'label_off'    => __('False', THEME_TEXTDOMAIN),
			'return_value' => 'true',
			'default'      => $defaults['sliderScrollbar'] ?? 'false'
		]);
		$this->end_controls_section();

		$this->addStyleOptions(__('Slider Pagination', THEME_TEXTDOMAIN), '.slider-pagination>span{{mode}}', 'sliderDots', [
			'sliderPagination' => 'true'
		], ['font', 'flex', 'padding'], [
			':normal'                          => __('Normal', THEME_TEXTDOMAIN),
			':hover'                           => __('Hover', THEME_TEXTDOMAIN),
			'.swiper-pagination-bullet-active' => __('Active', THEME_TEXTDOMAIN)
		]);
		$this->addStyleOptions(__('Slider Navigations', THEME_TEXTDOMAIN), '.slider-navs', 'sliderNavs', [
			'sliderNavs' => 'true'
		], ['typography', 'flex'], [
			':normal'                 => __('Normal', THEME_TEXTDOMAIN),
			':hover'                  => __('Hover', THEME_TEXTDOMAIN),
			'.swiper-button-disabled' => __('Disabled', THEME_TEXTDOMAIN)
		]);

	}

	/**
	 * get Swiper carousel options
	 *
	 * @param array $settings
	 *
	 * @return string
	 */
	public function getSwiperCarouselOptions (array $settings) : string {
		$carouselSettings = [
			'breakpoints' => [
				0   => [],
				361 => [],
				769 => []
			]
		];

		if ( $settings['sliderDirection'] === 'vertical' ) {
			$carouselSettings['direction'] = 'vertical';
		}

		if ( $settings['slidesCountMode'] === 'count' ) {
			$carouselSettings['spaceBetween']  = $settings['spaceBetween_mobile'] ?? $settings['spaceBetween'];
			$carouselSettings['slidesPerView'] = $settings['slidesPerView_mobile'] ?? $settings['slidesPerView'];

			$carouselSettings['breakpoints'][0]['slidesPerView'] = $settings['slidesPerView_mobile'] ?? $settings['slidesPerView'];
			$carouselSettings['breakpoints'][0]['spaceBetween']  = $settings['spaceBetween_mobile'] ?? $settings['spaceBetween'];

			$carouselSettings['breakpoints'][361]['slidesPerView'] = $settings['slidesPerView_tablet'] ?? $settings['slidesPerView'];
			$carouselSettings['breakpoints'][361]['spaceBetween']  = $settings['spaceBetween_tablet'] ?? $settings['spaceBetween'];

			$carouselSettings['breakpoints'][769]['slidesPerView'] = $settings['slidesPerView'];
			$carouselSettings['breakpoints'][769]['spaceBetween']  = $settings['spaceBetween'];

			if ( $settings['slidesColumn'] === 'true' ) {
				$carouselSettings['slidesPerColumn']                     = $settings['slidesPerColumn'];
				$carouselSettings['breakpoints'][0]['slidesPerColumn']   = $settings['slidesPerColumn'];
				$carouselSettings['breakpoints'][361]['slidesPerColumn'] = $settings['slidesPerColumn'];
				$carouselSettings['breakpoints'][769]['slidesPerColumn'] = $settings['slidesPerColumn'];

				$this->styles['{{WRAPPER}} .swiper-wrapper .swiper-slide'] = 'height: calc((100% - ' . ((int) $settings['spaceBetween'] * ((int) $settings['slidesPerColumn'] - 1)) . 'px) / ' . $settings['slidesPerColumn'] . ');';
				$this->styles['{{WRAPPER}} .swiper-container']             = 'height: 100%; height: 100%; margin-left: auto; margin-right: auto;';
			}

		} else {
			$carouselSettings['slidesPerView'] = 'auto';
			$carouselSettings['spaceBetween']  = $settings['spaceBetween'];
		}
		if ( (int) $settings['slidesPerGroup'] > 1 ) {
			$carouselSettings['slidesPerGroup'] = $settings['slidesPerGroup'];
		}


		if ( $settings['centeredSlides'] === 'true' ) {
			$carouselSettings['centeredSlides'] = true;
		}

		if ( isset($settings['freeMode']) && $settings['freeMode'] === 'true' ) {
			$carouselSettings['freeMode'] = true;
		}


		if ( $settings['allowTouchMove'] !== 'true' ) {
			$carouselSettings['allowTouchMove'] = false;
		}

		if ( $settings['infiniteLoop'] === 'true' ) {
			$carouselSettings['loop'] = true;

			if ( (int) $settings['slidesPerGroup'] > 1 ) {
				$carouselSettings['loopFillGroupWithBlank'] = true;
			}
		}

		if ( $settings['autoPlay'] === 'true' ) {
			$carouselSettings['autoplay'] = [
				'delay'                => $settings['autoPlayDuration'],
				'disableOnInteraction' => false
			];
		}

		if ( $settings['slideAnimation'] === 'fade' ) {
			$carouselSettings['effect']     = 'fade';
			$carouselSettings['fadeEffect'] = [
				'crossFade' => true
			];
		}

		if ( $settings['slideAnimation'] === 'coverflow' ) {
			$carouselSettings['coverflowEffect'] = [
				'rotate'       => 30,
				'slideShadows' => false
			];
		}

		if ( $settings['slideAnimation'] === 'flip' ) {
			$carouselSettings['flipEffect'] = [
				'slideShadows' => false
			];
		}

		if ( $settings['slideAnimation'] === 'cube' ) {
			$carouselSettings['cubeEffect'] = [
				'slideShadows' => false
			];
		}

		$carouselSettings['navigation'] = [
			'nextEl' => '.slider-button-next',
			'prevEl' => '.slider-button-prev'
		];
		if ( $settings['sliderNavs'] !== 'true' ) {
			$carouselSettings['breakpoints'][769]['navigation'] = false;
		}
		if ( $settings['sliderNavs_tablet'] !== 'true' ) {
			$carouselSettings['breakpoints'][361]['navigation'] = false;
		}
		if ( $settings['sliderNavs_mobile'] !== 'true' ) {
			$carouselSettings['breakpoints'][0]['navigation'] = false;
		}

		$carouselSettings['pagination'] = [
			'el'        => '.slider-pagination',
			'type'      => 'bullets',
			'clickable' => true
		];
		if ( $settings['sliderPagination'] !== 'true' ) {
			$carouselSettings['breakpoints'][769]['pagination'] = false;
		}
		if ( $settings['sliderPagination_tablet'] !== 'true' ) {
			$carouselSettings['breakpoints'][361]['pagination'] = false;
		}
		if ( $settings['sliderPagination_mobile'] !== 'true' ) {
			$carouselSettings['breakpoints'][0]['pagination'] = false;
		}

		if ( $settings['sliderScrollbar'] === 'true' ) {
			$carouselSettings['scrollbar'] = [
				'el' => '.slider-scrollbar'
			];
		} else {
			$this->styles['{{WRAPPER}} .slider-scrollbar'] = 'display: none !important;';
		}

		if ( empty($carouselSettings['breakpoints'][0]) ) {
			unset($carouselSettings['breakpoints'][0]);
		}
		if ( empty($carouselSettings['breakpoints'][361]) ) {
			unset($carouselSettings['breakpoints'][361]);
		}
		if ( empty($carouselSettings['breakpoints'][769]) ) {
			unset($carouselSettings['breakpoints'][769]);
		}

		try {
			$carouselSettings = json_encode($carouselSettings, JSON_THROW_ON_ERROR);
		} catch ( \JsonException $e ) {
			$carouselSettings = '{}';
		}

		return $carouselSettings;
	}

	/**
	 * add Widget inline styles
	 *
	 * @param      $styles
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function inlineStyles ($styles, bool $echo = true) : string {
		$output = '<style id="' . esc_attr($this->name) . '-inline-styles">' . PHP_EOL;
		if ( is_array($styles) ) {
			foreach ( $styles as $selector => $style ) {
				$output .= str_replace('{{WRAPPER}}', '.elementor-element-' . $this->get_id(), $selector) . ' {' . PHP_EOL . $style . PHP_EOL . '}' . PHP_EOL;
			}
		} else {
			$output .= $styles;
		}
		$output .= PHP_EOL . '</style>';
		if ( $echo ) {
			echo $output;
		}

		return $output;
	}

	/**
	 * @param $settings
	 *
	 * @return \DCore\Woocommerce\Current_Query_Renderer|\DCore\Woocommerce\Products_Renderer
	 */
	public static function get_shortcode_object ($settings) {
		if ( !class_exists('DCore\Woocommerce\Current_Query_Renderer') ) {
			return null;
		}
		if ( 'current_query' === $settings[Products_Renderer::QUERY_CONTROL_NAME . '_post_type'] ) {
			$type = 'current_query';

			return new DCore\Woocommerce\Current_Query_Renderer($settings, $type);
		}
		$type = 'products';

		if ( !class_exists('DCore\Woocommerce\Products_Renderer') ) {
			return null;
		}

		return new DCore\Woocommerce\Products_Renderer($settings, $type);
	}
}