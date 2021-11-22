<?php
/**
 *
 *
 * @category
 * @version    1.4.2
 * @since      1.4.2
 */

namespace DCore;

class Configs {
    static array $configJSON = array (
  'prefix' => 'dc',
  'name' => 'DCore',
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
    'Search' => 
    array (
      'widgetTitle' => 'جستجوی حرفه ای',
      'fieldPlaceholder' => 'جستجو در بین محصولات...',
    ),
    'Cart' => 
    array (
      'widgetTitle' => 'سبد خرید',
      'text' => 'سبد خرید',
    ),
  ),
);
    static array $shortcodes = array (
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
  'cart' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\cart\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/cart/style.css',
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
  'grid_product' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\grid_product\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/grid_product/style.css',
    'js-file' => '/templates/shortcodes/grid_product/script.js',
    'style' => 
    array (
      '.widget-pagination .page-numbers li .page-numbers.current' => 'background: [primaryColor]',
    ),
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
  'instant_offer' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\instant_offer\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/instant_offer/style.css',
    'js-file' => '/templates/shortcodes/instant_offer/script.js',
    'style' => 
    array (
      '.slider-timer span' => 'background-color: [primaryColor]',
    ),
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
  'product_additional_information' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_additional_information\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_addtocart' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_addtocart\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_carousel' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_carousel\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/product_carousel/style.css',
    'js-file' => '/templates/shortcodes/product_carousel/script.js',
  ),
  'product_content' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_content\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_data_tabs' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_data_tabs\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_delivery' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_delivery\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_featured_attributes' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_featured_attributes\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_gallery' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_gallery\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/product_gallery/style.css',
    'js-file' => '/templates/shortcodes/product_gallery/script.js',
  ),
  'product_hooks' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_hooks\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_messages' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_messages\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_meta' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_meta\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_price' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_price\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_price_changes' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_price_changes\\',
        'name' => 'default',
      ),
    ),
    'js-file' => '/templates/shortcodes/product_price_changes/script.js',
  ),
  'product_rating' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_rating\\',
        'name' => 'default',
      ),
    ),
    'style' => 
    array (
      '.star-rating span' => 'color: [primaryColor]',
      '.star-rating::before' => 'color: [secondaryColor]',
    ),
  ),
  'product_related' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_related\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/product_related/style.css',
    'js-file' => '/templates/shortcodes/product_related/script.js',
  ),
  'product_reviews' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_reviews\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_ribbons' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_ribbons\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_share' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_share\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_short_description' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_short_description\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_shortcodes' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_shortcodes\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_stock' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_stock\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_subtitle' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_subtitle\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_timer' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_timer\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_title' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_title\\',
        'name' => 'default',
      ),
    ),
  ),
  'product_upsell' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_upsell\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/product_upsell/style.css',
    'js-file' => '/templates/shortcodes/product_upsell/script.js',
  ),
  'product_videos' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\product_videos\\',
        'name' => 'default',
      ),
    ),
  ),
  'sale_slider' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\sale_slider\\',
        'name' => 'default',
      ),
    ),
    'css-file' => '/templates/shortcodes/sale_slider/style.css',
    'js-file' => '/templates/shortcodes/sale_slider/script.js',
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
  'single_product' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\single_product\\',
        'name' => 'default',
      ),
    ),
    'style' => 
    array (
      '.uk-link' => 'color: [primaryColor]',
      'a' => 'color: [primaryColor]',
    ),
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
  'woocommerce_notices' => 
  array (
    'templates' => 
    array (
      'default' => 
      array (
        'dir' => '\\templates\\shortcodes\\woocommerce_notices\\',
        'name' => 'default',
      ),
    ),
  ),
);
    static array $globalCards = array (
  'amazing-product' => 
  array (
    'minimal.php' => 'Minimal design',
  ),
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
  'instant-offer' => 
  array (
    'minimal.php' => 'Minimal design',
  ),
  'product' => 
  array (
    'list.php' => 'List design',
    'minimal.php' => 'Minimal design',
  ),
  'single-product' => 
  array (
    'list.php' => 'List design',
    'minimal.php' => 'Minimal design',
  ),
);
    static array $fonts = array (
  'IRANSans_Fa' => 
  array (
    'name' => 'ایران سانس',
    'family' => 'IRANSans_Fa',
    'url' => '/assets/font/iransans/style.css',
  ),
);
}
