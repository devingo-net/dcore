<?php

/**
 *  General Functions
 *
 * @category   Functions
 * @version    1.0.0
 * @since      1.0.0
 */

use DCore\Theme;

/**
 * add theme prefix to string
 *
 * @param string $string
 *
 * @return string
 */
function prefixStr(string $string): string
{
    return THEME_PREFIX . '_' . $string;
}

/**
 * build html tags
 *
 * @param string $tagName
 * @param string $content
 * @param array $attributes
 *
 * @return string
 */
function dcHtmlTagBuilder(string $tagName, string $content, array $attributes = []): string
{
    $output = '<' . (empty($tagName) ? 'div' : $tagName) . '';
    if (!empty($attributes)) {
        foreach ($attributes as $attribute => $value) {
            $output .= ' ' . $attribute . '="' . esc_attr($value) . '"';
        }
    }
    $output .= '>' . $content . '</' . (empty($tagName) ? 'div' : $tagName) . '>';

    return $output;
}

/**
 * icon renderer
 *
 * @param string $class
 * @param string $tagName
 *
 * @return string
 */
function dcIconTagBuilder(string $class, string $tagName = 'i'): string
{
    if (empty($class) || empty($tagName)) {
        return '';
    }

    return '<' . $tagName . ' class="' . $class . '"></' . $tagName . '>';
}

/**
 * get theme options
 *
 * @param string $optionName
 * @param null $default
 *
 * @return mixed|null
 */
function getThemeOptions(string $optionName, $default = null)
{
    global $themeOptions;
    if (!isset($themeOptions) || empty($themeOptions)) {
        $themeOptions = get_option(THEME_PREFIX . '-theme-options');
    }

    if (!isset($themeOptions[$optionName])) {
        return $default;
    }

    return $themeOptions[$optionName];
}

/**
 * get theme options hash
 *
 * @return string
 */
function getThemeOptionsHash(): string
{
    global $themeOptions;
    if (!isset($themeOptions) || empty($themeOptions)) {
        getThemeOptions('');
    }
    return sha1(serialize($themeOptions));
}

/**
 * get shop options
 *
 * @param string $optionName
 * @param null $default
 *
 * @return mixed|null
 */
function getShopOptions(string $optionName, $default = null)
{
    global $shopOptions;
    if (!isset($shopOptions) || empty($shopOptions)) {
        $shopOptions = get_option(THEME_PREFIX . '-shop-options');
    }

    if (!isset($shopOptions[$optionName])) {
        return $default;
    }

    return $shopOptions[$optionName];
}

/**
 * get shop options hash
 *
 * @return string
 */
function getShopOptionsHash(): string
{
    global $shopOptions;
    if (!isset($shopOptions) || empty($shopOptions)) {
        getShopOptions('');
    }
    return sha1(serialize($shopOptions));
}

/**
 * css font face builder
 *
 * @param $font_family
 * @param $data
 *
 * @return string
 */
function dcFontFaceBuilder($font_family, $data): string
{
    $src = [];
    foreach (['eot', 'woff2', 'woff', 'ttf', 'svg'] as $type) {
        if (!isset($data[$type]) || empty($data[$type])) {
            continue;
        }

        if ('svg' === $type) {
            $data[$type] .= '#' . str_replace(' ', '', $font_family);
        }

        $src[] = dcGetFontSrcPerType($type, $data[$type]);
    }

    $font_face = '@font-face {' . PHP_EOL;
    $font_face .= "\tfont-family: '" . $font_family . "';" . PHP_EOL;

    if (isset($data['eot']) && !empty($data['eot'])) {
        $font_face .= "\tsrc: url('" . esc_attr($data['eot']) . "');" . PHP_EOL;
    }

    $font_face .= "\tsrc: " . implode(',' . PHP_EOL . "\t\t", $src) . ';' . PHP_EOL . '}';

    return $font_face;
}

function dcGetFontSrcPerType($type, $url): string
{
    $src = 'url(\'' . esc_attr($url) . '\') ';
    switch ($type) {
        case 'woff':
        case 'woff2':
        case 'svg':
            $src .= 'format(\'' . $type . '\')';
            break;

        case 'ttf':
            $src .= 'format(\'truetype\')';
            break;

        case 'eot':
            $src = 'url(\'' . esc_attr($url) . '?#iefix\') format(\'embedded-opentype\')';
            break;
    }

    return $src;
}

/**
 * get post sub title
 *
 * @param $post
 *
 * @return string
 */
function dcGetPostSubTitle($post): string
{
    if ($post instanceof WC_Product) {
        return $post->get_meta('sub-title');
    }

    return get_post_meta($post, 'sub-title', true);
}

/**
 * post sub title
 *
 * @param        $post
 *
 * @param string $before
 * @param string $after
 *
 * @return void
 */
function the_subtitle(string $before = '', string $after = '', $post = null): void
{
    if ($post === null) {
        global $post;
    }
    $subTitle = dcGetPostSubTitle($post);
    if (empty($subTitle)) {
        echo '';
    }

    echo $before . $subTitle . $after;
}

/**
 * get parsed template path
 *
 * @param string $path
 * @return string
 */
function dcGetTemplatePath(string $path = ''): string
{
    $parsedPath = realpath(get_template_directory() . '/' . $path);
    if (!file_exists($parsedPath)) {
        die(sprintf('% <br> file not found!', $parsedPath));
    }
    return $parsedPath;
}

/**
 * get request user ip
 *
 * @return mixed|string
 */
function dcGetUserIP()
{
    if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

/**
 * get post views count
 *
 * @param int $postID
 * @return int
 */
function dcGetPostViews(int $postID): int
{
    return \DCore\Features\Statistics::getRecordsCount($postID);
}

/**
 * Set data to cache in file
 *
 * @param string $name
 * @param $data
 * @param int $expire
 * @param string $group
 * @return bool
 */

function dcSetFileCache(string $name, $data, int $expire = 0, string $group = 'default'): bool
{
    global $dcCache;
    if (!isset($dcCache) || empty($dcCache)) {
        $dcCache = new DCore\Cache();
    }
    return $dcCache->setFile($name, $data, $expire, $group);
}

/**
 * Get data from cached file
 *
 * @param string $name
 * @param false $default
 * @param string $group
 * @return mixed
 */
function dcGetFileCache(string $name, $default = false, string $group = 'default')
{
    global $dcCache;
    if (!isset($dcCache) || empty($dcCache)) {
        $dcCache = new DCore\Cache();
    }
    return $dcCache->getFile($name, $default, $group);
}
/**
 * Set data to cache
 *
 * @param string $name
 * @param $data
 * @param int $expire
 * @param string $group
 * @return bool
 */

function dcSetCache(string $name, $data, int $expire = 0, string $group = 'default'): bool
{
    global $dcCache;
    if (!isset($dcCache) || empty($dcCache)) {
        $dcCache = new DCore\Cache();
    }
    return $dcCache->set($name, $data, $expire, $group);
}

/**
 * Get data from cache
 *
 * @param string $name
 * @param false $default
 * @param string $group
 * @return mixed
 */
function dcGetCache(string $name, $default = false, string $group = 'default')
{
    global $dcCache;
    if (!isset($dcCache) || empty($dcCache)) {
        $dcCache = new DCore\Cache();
    }
    return $dcCache->get($name, $default, $group);
}



/**
 * get template part
 *
 * @param string $path
 * @param array $args
 * @param bool $return
 * @return void|string
 */
function dcGetTemplatePart(string $path, array $args = [], bool $return = false)
{
    if ($return) {
        ob_start();
    }
    Theme::getTemplatePart($path, $args);
    if ($return) {
        return ob_get_clean();
    }
}

/**
 * get elementor widget without elementor
 *
 * @param string $name
 * @param array $options
 * @param string $style
 */
function dcGetElementorWidget(string $name, array $options = [],string $style = 'default')
{
    $widgetPath = THEME_TEMPLATES_DIR . DSP . 'shortcodes' . DSP . $name . DSP;
    if (!file_exists($widgetPath)) {
        return;
    }
    $assetName = THEME_PREFIX . '-widget-' . $name;
    if (file_exists($widgetPath . 'style.css') && !wp_style_is($assetName)) {
        add_action('wp_footer',function () use ($assetName, $widgetPath) {
            echo '<style id="inline-'.$assetName.'">';
            echo file_get_contents($widgetPath . 'style.css');
            echo '</style>';
        });
    }

    if (file_exists($widgetPath . 'script.js') && !wp_script_is($assetName)) {
        add_action('wp_footer',function () use ($name) {
            echo '<script src="'.THEME_SHORTCODES_URI . $name . '/script.js"></script>';
        });
    }

    if ($style === 'default'){
        Theme::getTemplatePart('shortcodes/' . $name . '/index',$options);
        return;
    }

    Theme::getTemplatePart('shortcodes/' . $name .'/' . $style . '/index',$options);
}