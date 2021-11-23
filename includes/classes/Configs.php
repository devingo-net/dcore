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
    static $configJSON = array (
  'prefix' => 'sd',
  'name' => 'DevingoCore',
  'textdomain' => 'dcore',
  'colors' => 
  array (
    'primary' => 
    array (
      'normal' => '#f99',
      'dark' => '#f00',
    ),
    'secondary' => 
    array (
      'normal' => '#f99',
      'dark' => '#f00',
    ),
  ),
  'fontApply' => 
  array (
    0 => '.font-family',
    1 => '.uk-h1',
    2 => '.uk-h2',
    3 => '.uk-h3',
    4 => '.uk-h4',
    5 => '.uk-h5',
    6 => '.uk-h6',
    7 => '.uk-heading-2xlarge',
    8 => '.uk-heading-large',
    9 => '.uk-heading-medium',
    10 => '.uk-heading-small',
    11 => '.uk-heading-xlarge',
    12 => 'h1',
    13 => 'h2',
    14 => 'h3',
    15 => 'h4',
    16 => 'h5',
    17 => 'h6',
  ),
  'styleApply' => 
  array (
    '.star-rating span' => 
    array (
      'color' => '[primary-color]',
    ),
  ),
  'shortcodes' => 
  array (
  ),
);
    static $shortcodes = array (
  'account' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\account\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/account/style.css',
  ),
  'blog_carousel' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\blog_carousel\\',
        'name' => 'default',
      ),
    ),
    'js-file' => '/templates/shortcodes/blog_carousel/script.js',
  ),
  'blog_posts' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\blog_posts\\',
        'manifest' => 
        array (
          'name' => 'Flat Design',
          'colors' => 
          array (
            'text_color' => 
            array (
              'name' => 'text_color',
              'conditions' => 
              array (
                'cardStyle' => 'flat.php',
              ),
            ),
            'buttons_color' => 
            array (
              'name' => 'buttons_color',
            ),
          ),
          'disabledOptions' => 
          array (
          ),
        ),
        'name' => 'Flat Design',
      ),
    ),
    'css-file' => '/templates/shortcodes/blog_posts/style.css',
    'js-file' => '/templates/shortcodes/blog_posts/script.js',
    'style' => 
    array (
      '.grid-content-area' => 'background: [buttons_color:20]',
      '.uk-card-title' => 'background: [text_color:50]',
    ),
  ),
  'categories' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\categories\\',
        'name' => 'default',
      ),
    ),
    'js-file' => '/templates/shortcodes/categories/script.js',
  ),
  'custom_carousel' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\custom_carousel\\',
        'name' => 'default',
      ),
    ),
    'js-file' => '/templates/shortcodes/custom_carousel/script.js',
  ),
  'image_slider' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\image_slider\\',
        'name' => 'default',
      ),
    ),
    'js-file' => '/templates/shortcodes/image_slider/script.js',
  ),
  'menu' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\menu\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/menu/style.css',
    'js-file' => '/templates/shortcodes/menu/script.js',
  ),
  'search' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\search\\',
        'name' => 'default',
      ),
      'minimal' => 
      array (
        'dir' => '\\templates\\shortcodes\\search\\minimal\\',
        'name' => 'minimal',
      ),
    ),
    'css-file' => '/templates/shortcodes/search/style.css',
    'js-file' => '/templates/shortcodes/search/script.js',
  ),
  'tabbed_menu' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\tabbed_menu\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/tabbed_menu/style.css',
  ),
);
    static $globalCards = array (
  'blog-carousel' => 
  array (
    'minimal.php' => 'Minimal design',
  ),
  'blog-post' => 
  array (
    'minimal.php' => 'Minimal design',
  ),
  'category' => 
  array (
    'minimal.php' => 'Minimal design',
  ),
  'image' => 
  array (
    'minimal.php' => 'Minimal design',
  ),
);
    static $fonts = array (
  'IRANSans_Fa' => 
  array (
    'name' => 'ایران سانس',
    'family' => 'IRANSans_Fa',
    'url' => '/assets/font/iransans/style.css',
  ),
);
}
