<?php

namespace DCore\Elementor\Modules\Page\Widgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use DCore\Elementor\Control_Types\Group_Control_Related;
use DCore\Elementor\Widget;
use DCore\Theme;

/**
 * Class Blog_Carousel
 *
 * @package DCore\Elementor\Modules\Page\Widgets
 */
class Blog_Carousel extends Widget {

	/**
	 * Blog Carousel constructor.
	 *
	 * @param array $data
	 * @param null  $args
	 *
	 * @throws \Exception
	 */
	public function __construct ($data = [], $args = null) {
		$this->name       = 'Blog_Carousel_Widget';
		$this->title      = 'Blog Carousel Widget';
		$this->icon       = 'fad fa-arrows-h';
		$this->categories = ['prefix::page'];
		$this->cardStyle = 'blog-carousel';

		parent::__construct($data, $args);
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls () : void {
		parent::register_controls();
		$this->start_controls_section('Content_settings', [
			'label' => __('Contents', THEME_TEXTDOMAIN),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('archiveTitle', [
			'label'       => __('Archive Title', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::TEXT,
			'default'     => __('News', THEME_TEXTDOMAIN),
			'label_block' => true,
		]);

		$this->add_control('archiveLink', [
			'label'       => __('Archive Link', THEME_TEXTDOMAIN),
			'type'        => Controls_Manager::URL,
			'placeholder' => __('https://your-link.com', THEME_TEXTDOMAIN),
			'dynamic'     => [
				'active' => true,
			],
			'default'     => [
				'url' => '#',
			],
		]);

		$this->addCardStyles();


		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name' => 'blogPosts',
				'presets' => [ 'full' ]
			]
		);



		$this->end_controls_section();
		$this->addWidgetStyles();

		$this->addSwiperCarouselOptions();


		$this->addStyleOptions(__('Archive Title', THEME_TEXTDOMAIN), '.archive-title', 'archiveTitle', [], ['border', 'flex'], [
			':normal'                 => __('Normal', THEME_TEXTDOMAIN)
		]);
	}


	public function settingsParse (array &$settings) : void {
		$elementorQuery = Module_Query::instance();
		$postQuery = $elementorQuery->get_query( $this, 'blogPosts');
		$settings['posts'] = [];
		if(!isset($settings['cardStyle'])){
			return;
		}

		if ( !empty($settings['archiveLink']['url']) ) {
			$this->add_link_attributes('blog-archive-link', $settings['archiveLink']);

			$settings['archiveLinkAtts'] = $this->get_render_attribute_string('blog-archive-link');
		}

		if($postQuery->have_posts()){
			while ( $postQuery->have_posts() ) {
				$postQuery->the_post();
				global $post;
				ob_start();
				Theme::getTemplatePart($settings['cardStyle'],[
					'post'   =>  $post
				],true);
				$settings['posts'][] = ob_get_clean();
			}
		}

		$settings['carouselOptions'] = $this->getSwiperCarouselOptions($settings);
	}

}
