<?php

namespace DCore\Elementor\Modules\Page\Widgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use DCore\Elementor\Control_Types\Group_Control_Related;
use DCore\Elementor\Widget;
use DCore\Theme;

/**
 * Class Blog_Posts
 *
 * @package DCore\Elementor\Modules\Page\Widgets
 */
class Blog_Posts extends Widget
{

    /**
     * Blog Posts constructor.
     *
     * @param array $data
     * @param null $args
     *
     * @throws \Exception
     */
    public function __construct($data = [], $args = null)
    {
        $this->name = 'Blog_Posts_Widget';
        $this->title = 'Blog Posts Widget';
        $this->icon = 'fas fa-clone';
        $this->categories = ['prefix::page'];
        $this->cardStyle = 'blog-post';

        parent::__construct($data, $args);
    }

    /**
     * @inheritDoc
     */
    protected function register_controls(): void
    {
        parent::register_controls();
        $this->start_controls_section('Content_settings', [
            'label' => __('Contents', THEME_TEXTDOMAIN),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);


        $this->addCardStyles();


        $this->add_group_control(Group_Control_Related::get_type(), [
            'name' => 'blogPosts',
            'presets' => ['full']
        ]);


        $this->end_controls_section();

        $this->start_controls_section('layout_settings', [
            'label' => __('Layout', THEME_TEXTDOMAIN),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('columns', [
            'label' => __('Columns', THEME_TEXTDOMAIN),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 6,
            'step' => 1,
            'desktop_default' => 1,
            'tablet_default' => 1,
            'mobile_default' => 1,
            'frontend_available' => true
        ]);

        $this->add_responsive_control('columnsPadding', [
            'label' => __('Padding', THEME_TEXTDOMAIN),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 15,
            ],
            'selectors' => [
                '{{WRAPPER}} .uk-grid-column-small > *, {{WRAPPER}} .uk-grid-small > *' => 'padding-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .uk-grid-column-small, {{WRAPPER}} .uk-grid-small' => 'margin-right: -{{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .uk-grid-margin' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section('pagination_settings', [
            'label' => __('Pagination', THEME_TEXTDOMAIN),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);
        $this->add_control('paginateType', [
            'label' => __('Pagination Type', THEME_TEXTDOMAIN),
            'type' => Controls_Manager::SELECT,
            'default' => 'false',
            'options' => [
                'false' => __('False', THEME_TEXTDOMAIN),
                'number' => __('Number', THEME_TEXTDOMAIN),
                'loadmore' => __('Load more', THEME_TEXTDOMAIN),
                'autoload' => __('Auto load', THEME_TEXTDOMAIN)
            ],
            'condition' => [
                'blogPosts_post_type!' => 'current_query'
            ]
        ]);
        $this->add_control('paginateTypeCurrentQuery', [
            'label' => __('Pagination Type', THEME_TEXTDOMAIN),
            'type' => Controls_Manager::SELECT,
            'default' => 'false',
            'options' => [
                'false' => __('False', THEME_TEXTDOMAIN),
                'number' => __('Number', THEME_TEXTDOMAIN)
            ],
            'condition' => [
                'blogPosts_post_type' => 'current_query'
            ]
        ]);
        $this->add_control('ajaxNumericPaginate', [
            'label' => __('Ajax paginate', THEME_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('True', THEME_TEXTDOMAIN),
            'label_off' => __('False', THEME_TEXTDOMAIN),
            'return_value' => 'true',
            'default' => 'true',
            'condition' => [
                'paginateType' => 'number',
                'blogPosts_post_type!' => 'current_query'
            ]
        ]);

        $this->add_control('midSize', [
            'label' => __('Mid Size', THEME_TEXTDOMAIN),
            'type' => Controls_Manager::NUMBER,
            'description' => __('How many numbers to either side of the current pages.', THEME_TEXTDOMAIN),
            'min' => 1,
            'max' => 20,
            'step' => 1,
            'default' => 2,
            'condition' => [
                'relation' => 'or',
                'terms' => [
                    'paginateType' => 'number',
                    'paginateTypeCurrentQuery' => 'number'
                ],
            ]
        ]);

        $this->end_controls_section();
        $this->addWidgetStyles();
    }


    public function settingsParse(array &$settings): void
    {
        $settings['widgetClasses'] = [];
        $elementorQuery = Module_Query::instance();
        $settings['paginateKey'] = 'page_' . $this->get_id();

        if ($settings['blogPosts_post_type'] === 'current_query') {
            $settings['paginateType'] = $settings['paginateTypeCurrentQuery'];
            $settings['ajaxNumericPaginate'] = 'false';
        }
        if ($settings['blogPosts_post_type'] === 'current_query') {
            $settings['paginateKey'] = 'paged';
        }
        if (isset($_GET[$settings['paginateKey']]) && is_numeric($_GET[$settings['paginateKey']])) {
            $settings['currentPage'] = (int)$_GET[$settings['paginateKey']];
        } else {
            $settings['currentPage'] = get_query_var($settings['paginateKey'], 1);
        }
        if (!is_numeric($settings['currentPage']) || $settings['currentPage'] < 1) {
            $settings['currentPage'] = 1;
        }

        $cacheName = 'dc_product_query_' . crc32(serialize($this->get_settings_for_display()));

        if (isset($GLOBALS[$cacheName])) {
            $postQuery = $GLOBALS[$cacheName];
        } else {
            $postQuery = $elementorQuery->get_query($this, 'blogPosts', [
                'paged' => $settings['paginateType'] === 'number' ? $settings['currentPage'] : 1,
                'cache_results' => true
            ]);
            $GLOBALS[$cacheName] = $postQuery;
        }


        $settings['totalPages'] = $postQuery->max_num_pages;


        if ($settings['paginateType'] !== 'false') {
            $settings['widgetClasses'][] = 'paginate-type-' . $settings['paginateType'];
        }

        if ($settings['paginateType'] === 'number' && $settings['ajaxNumericPaginate'] === 'true') {
            $settings['widgetClasses'][] = 'ajax-paginate';
        }


        if (!isset($settings['cardStyle'])) {
            return;
        }
        $settings['posts'] = [];

        if ($postQuery->have_posts()) {
            while ($postQuery->have_posts()) {
                $postQuery->the_post();
                global $post;
                ob_start();
                Theme::getTemplatePart($settings['cardStyle'], [
                    'post' => $post
                ], true);
                $settings['posts'][] = ob_get_clean();
            }
        }

    }

}
