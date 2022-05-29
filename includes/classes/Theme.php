<?php

/**
 *  Theme core helper
 *
 * @category    Helper
 * @version     1.0.0
 * @since       1.0.0
 */

namespace DCore;

use Exception;

/**
 * Class Theme
 *
 * @package DCore
 */
class Theme
{
    public static $inlineStyles = [];

    /**
     * get get configs hash
     *
     * @return array|object
     */
    public static function getConfigsHash(): string
    {
        $configPath = __DIR__ . DIRECTORY_SEPARATOR . 'Configs.php';
        if (!file_exists($configPath)) {
            return '';
        }
        return sha1_file($configPath);
    }

    /**
     * get template default configs
     *
     * @return array
     */
    public static function getConfigs(): array
    {
        return Configs::getConfigJSON();
    }

    /**
     * get template default config
     *
     * @param string $opt
     *
     * @param mixed $def
     * @param bool $dep
     * @return object|string|array
     */
    public static function getConfig(string $opt, $def = null, $dep = false)
    {
        $opts = self::getConfigs();
        return $opts[$opt] ?? $def;
    }

    /**
     * get Shortcode Default configs
     *
     * @param string $name
     *
     * @param null $def
     *
     * @return mixed
     */
    public static function getShortCodeConfigs(string $name, $def = null)
    {
        $opts = Configs::getShortcodesValues();
        if (empty($opts)) {
            return $def;
        }

        return $opts[$name] ?? $def;
    }

    /**
     * get Shortcode Default config
     *
     * @param string $name
     *
     * @param string $option
     * @param null $def
     *
     * @return mixed|null
     */
    public static function getShortCodeConfig(string $name, string $option, $def = null)
    {
        $opts = self::getShortCodeConfigs($name);
        if (empty($opts)) {
            return $def;
        }

        return $opts[$option] ?? $def;
    }

    /**
     * get dynamic styles configs
     *
     * @return array
     */
    public static function getDynamicStylesConfig(): array
    {
        return Configs::getStyleApply();
    }

    /**
     * include php files with variables
     *
     * @param string $file
     * @param array $props
     *
     * @param bool $fullpatch
     *
     * @return string
     */
    public static function getTemplatePart(string $file, array $props = [], bool $fullpatch = false): string
    {
        if (!$fullpatch) {
            $file = THEME_TEMPLATES_DIR . DSP . $file . '.php';
        }
        extract($props, EXTR_OVERWRITE, THEME_PREFIX);

        $file = realpath(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file));

        if (file_exists($file)) {
            include($file);

            return true;
        }

        return false;
    }

    /**
     * get template slice
     *
     * @param string $file
     * @param array $props
     *
     */
    public static function getSlice(string $file, array $props = []): void
    {
        $file = THEME_TEMPLATES_DIR . DSP . 'globals' . DSP . 'slices' . DSP . $file . '.php';

        self::getTemplatePart($file, $props, true);
    }

    /**
     * @param string $name
     * @param string $file
     *
     * @return string|boolean
     */
    public static function getShortcodeDir(string $name, string $file)
    {
        $dir = THEME_TEMPLATES_DIR . DSP . 'shortcodes' . DSP . strtolower($name) . DSP . $file;
        if (file_exists($dir)) {
            return $dir;
        }

        return false;
    }

    /**
     * @param string $name
     * @param string $file
     *
     * @return bool|string
     */
    public static function getShortcodeFileContent(string $name, string $file)
    {
        $dir = self::getShortcodeDir($name, $file);
        if ($dir !== false) {
            return file_get_contents($dir);
        }

        return false;
    }


    /**
     * return inline styles
     *
     * @return string
     */
    public static function dynamicStyles(): string
    {
        $cacheName = 'dynamic_styles_' . getThemeOptionsHash() . '_' . getShopOptionsHash() . '_' . self::getConfigsHash();
        $cssOutPut = '';
        $colorPrimary = getThemeOptions('primary-color', '');
        $colorPrimaryDark = getThemeOptions('primary-color-dark', '');
        $colorSecondary = getThemeOptions('secondary-color', '');
        $colorSecondaryDark = getThemeOptions('secondary-color-dark', '');

        $output = dcGetFileCache($cacheName, false);

        $siteFont = getThemeOptions('site-font', '');
        $fontsList = self::getFonts();

        if ($siteFont !== 'upload' && $siteFont !== '') {
            $currentFont = $fontsList[$siteFont];

            wp_enqueue_style(prefixStr('custom-font'), get_template_directory_uri() . ($currentFont['url'] ?? ''));
        }

        if ($output !== false) {
            return $output;
        }

        self::addStyle([
            '.button-primary',
            '.uk-checkbox:checked',
            '.uk-checkbox:indeterminate',
            '.uk-radio:checked',
            '.uk-progress::-webkit-progress-value',
            '.uk-progress::-moz-progress-bar',
            '.uk-progress::-ms-fill',
            '.uk-section-primary',
            '.uk-tile-primary',
            '.uk-card-primary',
            '.uk-card-primary.uk-card-hover:hover',
            '.uk-badge',
            '.uk-label',
            '.uk-subnav-pill > .uk-active > a',
            '.uk-background-primary'
        ], 'background-color', $colorPrimary);

        self::addStyle([
            '.uk-link',
            'a',
            '.uk-link-heading a:hover',
            '.uk-link-toggle:focus .uk-link-heading',
            '.uk-link-toggle:hover .uk-link-heading',
            'a.uk-link-heading:hover',
            '.uk-list-primary > ::before',
            '.button-link',
            '.uk-alert-primary',
            '.uk-notification-message-primary',
            '.uk-text-primary',
            '.uk-text-background'
        ], 'color', $colorPrimary);

        self::addStyle([
            '.uk-input:focus',
            '.uk-select:focus',
            '.uk-textarea:focus',
            '.uk-checkbox:focus',
            '.uk-radio:focus',
            '.uk-tab > .uk-active > a'
        ], 'border-color', $colorPrimary);

        self::addStyle([
            '.uk-radio:checked:focus',
            '.uk-checkbox:indeterminate:focus',
            '.uk-checkbox:checked:focus',
            '.button-primary.uk-active',
            '.button-primary:active',
            '.button-primary:hover',
            '.button-primary:focus',
            '.button-primary:active'
        ], 'background-color', $colorPrimaryDark);
        self::addStyle([
            '.uk-link-toggle:focus .uk-link',
            '.uk-link-toggle:hover .uk-link',
            '.uk-link:hover',
            'a:hover'
        ], 'color', $colorPrimaryDark);

        self::addStyle([
            '.button-secondary',
            '.uk-section-secondary',
            '.uk-tile-secondary',
            '.uk-card-secondary',
            '.uk-card-secondary.uk-card-hover:hover',
            '.uk-marker',
        ], 'background-color', $colorSecondary);

        self::addStyle([
            '.uk-text-secondary',
            '.uk-list-secondary > ::before',
        ], 'color', $colorSecondary);

        self::addStyle([
            '.button-secondary:hover',
            '.button-secondary:focus',
            '.button-secondary:active'
        ], 'background-color', $colorSecondaryDark);


        $dynamicStyles = self::getDynamicStylesConfig();

        if (!empty($dynamicStyles)) {
            foreach ($dynamicStyles as $selector => $styles) {
                foreach ($styles as $property => $value) {


                    if (preg_match_all('/\[(.*?)\]/', $value, $matchedSettings, PREG_SET_ORDER) > 0) {
                        foreach ($matchedSettings as $matchedSetting) {
                            if (!isset($matchedSetting[1])) {
                                continue;
                            }

                            $matchedSetting[1] = explode(':', $matchedSetting[1]);
                            $optKey = $matchedSetting[1][0];
                            $colorAlpha = $matchedSetting[1][1] ?? 100;
                            $optValue = getThemeOptions($optKey, '');
                            if (empty($optValue)) {
                                continue 2;
                            }
                            $value = str_replace($matchedSetting[0], Helper::hexToRgba($optValue, $colorAlpha), $value);
                        }
                    }

                    if (strpos('[', $value) !== false || strpos(']', $value) !== false) {
                        continue;
                    }

                    self::addStyle([$selector], $property, $value);
                }
            }
        }

        if ($siteFont === '') {
            $currentFont = $fontsList[array_key_first($fontsList)];
            $siteFont = $currentFont['family'] ?? 'tahoma';
        }

        if ($siteFont === 'upload') {
            $siteFont = 'customFont';
            $cssOutPut .= dcFontFaceBuilder($siteFont, [
                'eot' => getThemeOptions('custom-font-eot', ''),
                'woff2' => getThemeOptions('custom-font-woff2', ''),
                'woff' => getThemeOptions('custom-font-woff', ''),
                'ttf' => getThemeOptions('custom-font-ttf', ''),
                'svg' => getThemeOptions('custom-font-svg', '')
            ]);
        }


        $fontConfigs = Configs::getFontApply();
        if (!is_array($fontConfigs)) {
            $fontConfigs = [];
        }

        self::addStyle(array_merge([
            'body'
        ], $fontConfigs), 'font-family', $siteFont);

        $output = $cssOutPut . PHP_EOL . self::arrayToCSS();
        dcSetFileCache($cacheName, $output, 24 * 60 * 60);
        return $output;
    }

    public static function addStyle(array $selectors, string $property, string $value): void
    {
        if (empty($selectors)) {
            return;
        }
        foreach ($selectors as $selector) {
            if (empty($value)) {
                continue;
            }
            self::$inlineStyles[$selector] = [
                $property => $value
            ];
        }
    }


    /**
     * render css codes from array
     *
     * @return string
     */
    public static function arrayToCSS(): string
    {
        if (empty(self::$inlineStyles)) {
            return '';
        }
        $outputCss = '';
        foreach (self::$inlineStyles as $key => $val) {
            if (empty($val)) {
                continue;
            }

            $outputCss .= $key . '{';
            foreach ($val as $property => $value) {
                $outputCss .= $property . ': ' . $value . ';';
            }
            $outputCss .= '}';
        }

        return $outputCss;
    }

    /**
     * @param string $dir
     * @param array $headerList
     *
     * @return array
     */
    public static function getDirectoryFilesHeader(string $dir, array $headerList = []): array
    {
        $widgetTemplateDir = get_template_directory() . DSP . $dir . DSP;
        $widgetTemplateFiles = Helper::getDirFiles($widgetTemplateDir);

        if (empty($widgetTemplateFiles)) {
            return [];
        }
        $files = [];
        foreach ($widgetTemplateFiles as $template_file) {
            if (is_dir($widgetTemplateDir . $template_file)) {
                continue;
            }
            $files[$widgetTemplateDir . $template_file] = get_file_data($widgetTemplateDir . $template_file, $headerList);
        }

        return $files;
    }

    /**
     * get card list
     *
     * @param string $type
     * @param bool $fullPath
     * @return array
     */
    public static function getCardsList(string $type,bool $fullPath = true): array
    {
        $globalCards = Configs::getGlobalCards();
        if (!isset($globalCards[$type]) || empty($globalCards[$type])) {
            return [];
        }
        $globalCards = $globalCards[$type];
        $templateOptions = [];
        foreach ($globalCards as $patch => $name) {
            if (strpos($patch, '.php') === false) {
                continue;
            }
            if ($fullPath) {
                $templateOptions[$type . DSP . $patch] = $name;
            }else{
                $templateOptions[$patch] = $name;
            }
        }

        return $templateOptions;
    }

    /**
     * get fonts list
     *
     * @return array
     */
    public static function getFonts(): array
    {
        return Configs::getFonts();
    }

    /**
     * get fonts url & name list
     *
     * @return array
     */
    public static function getFontsList(): array
    {
        $fonts = self::getFonts();
        if (empty($fonts)) {
            return [];
        }
        $result = [];
        foreach ($fonts as $key => $font) {
            $result[$font['family']] = $font['name'];
        }

        return $result;
    }

    public static function getJsonFileArray(string $url): array
    {
        $file = file_get_contents($url);
        if ($file === false) {
            return [];
        }

        try {
            $file = json_decode($file, true);
        } catch (Exception $e) {
            $file = [];
        }

        return $file;
    }

    public static function coreVersion(): string
    {
        return "1.1.2";
    }
}
