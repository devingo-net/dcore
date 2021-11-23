<?php
/**
 * Cached configs
 *
 * @category   Configs
 * @version    1.4.2
 * @since      1.4.2
 */

namespace DCore;

class Configs {
	static $configJSON  = [
		'prefix'     => 'sd',
		'name'       => 'DevingoCore',
		'textdomain' => 'dcore',
		'colors'     => [
			'primary'   => [
				'normal' => '#f99',
				'dark'   => '#f00',
			],
			'secondary' => [
				'normal' => '#f99',
				'dark'   => '#f00',
			],
		],
		'fontApply'  => [
			0  => '.font-family',
			1  => '.uk-h1',
			2  => '.uk-h2',
			3  => '.uk-h3',
			4  => '.uk-h4',
			5  => '.uk-h5',
			6  => '.uk-h6',
			7  => '.uk-heading-2xlarge',
			8  => '.uk-heading-large',
			9  => '.uk-heading-medium',
			10 => '.uk-heading-small',
			11 => '.uk-heading-xlarge',
			12 => 'h1',
			13 => 'h2',
			14 => 'h3',
			15 => 'h4',
			16 => 'h5',
			17 => 'h6',
		],
		'styleApply' => [
			'.star-rating span' => [
				'color' => '[primary-color]',
			],
		],
		'shortcodes' => [],
	];
	static $shortcodes  = [
		'account'         => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\account\\',
					'name' => 'default',
				],
			],
			'css-file'  => '/templates/shortcodes/account/style.css',
		],
		'blog_carousel'   => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\blog_carousel\\',
					'name' => 'default',
				],
			],
			'js-file'   => '/templates/shortcodes/blog_carousel/script.js',
		],
		'blog_posts'      => [
			'templates' => [
				'default' => [
					'dir'      => '\\templates\\shortcodes\\blog_posts\\',
					'manifest' => [
						'name'            => 'Flat Design',
						'colors'          => [
							'text_color'    => [
								'name'       => 'text_color',
								'conditions' => [
									'cardStyle' => 'flat.php',
								],
							],
							'buttons_color' => [
								'name' => 'buttons_color',
							],
						],
						'disabledOptions' => [],
					],
					'name'     => 'Flat Design',
				],
			],
			'css-file'  => '/templates/shortcodes/blog_posts/style.css',
			'js-file'   => '/templates/shortcodes/blog_posts/script.js',
			'style'     => [
				'.grid-content-area' => 'background: [buttons_color:20]',
				'.uk-card-title'     => 'background: [text_color:50]',
			],
		],
		'categories'      => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\categories\\',
					'name' => 'default',
				],
			],
			'js-file'   => '/templates/shortcodes/categories/script.js',
		],
		'custom_carousel' => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\custom_carousel\\',
					'name' => 'default',
				],
			],
			'js-file'   => '/templates/shortcodes/custom_carousel/script.js',
		],
		'image_slider'    => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\image_slider\\',
					'name' => 'default',
				],
			],
			'js-file'   => '/templates/shortcodes/image_slider/script.js',
		],
		'menu'            => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\menu\\',
					'name' => 'default',
				],
			],
			'css-file'  => '/templates/shortcodes/menu/style.css',
			'js-file'   => '/templates/shortcodes/menu/script.js',
		],
		'search'          => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\search\\',
					'name' => 'default',
				],
				'minimal' => [
					'dir'  => '\\templates\\shortcodes\\search\\minimal\\',
					'name' => 'minimal',
				],
			],
			'css-file'  => '/templates/shortcodes/search/style.css',
			'js-file'   => '/templates/shortcodes/search/script.js',
		],
		'tabbed_menu'     => [
			'templates' => [
				'default' => [
					'dir'  => '\\templates\\shortcodes\\tabbed_menu\\',
					'name' => 'default',
				],
			],
			'css-file'  => '/templates/shortcodes/tabbed_menu/style.css',
		],
	];
	static $globalCards = [
		'blog-carousel' => [
			'minimal.php' => 'Minimal design',
		],
		'blog-post'     => [
			'minimal.php' => 'Minimal design',
		],
		'category'      => [
			'minimal.php' => 'Minimal design',
		],
		'image'         => [
			'minimal.php' => 'Minimal design',
		],
	];
	static $fonts       = [
		'IRANSans_Fa' => [
			'name'   => 'ایران سانس',
			'family' => 'IRANSans_Fa',
			'url'    => '/assets/font/iransans/style.css',
		],
	];
}
