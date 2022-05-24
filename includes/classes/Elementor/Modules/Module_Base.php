<?php

namespace DCore\Elementor\Modules;
if (!class_exists('\ElementorPro\Base\Module_Base')) {
    abstract class Module_Base
    {
    }

    return;
}


use DCore\Helper;
use ReflectionClass;

/**
 * Class Module_Base
 * @package DCore\Elementor\Modules
 */
abstract class Module_Base extends \ElementorPro\Base\Module_Base
{
    /**
     * @return array
     */
    public function get_widgets(): array
    {
        try {
            $reflector = new ReflectionClass(get_class($this));
        } catch (\ReflectionException $e) {
            return [];
        }
        $reflector = dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR;

        if (!file_exists($reflector . 'Widgets')) {
            return [];
        }

        $files = Helper::getDirFiles($reflector . 'Widgets');

        if (empty($files)) {
            return [];
        }

        foreach ($files as &$file) {
            $file = str_replace(['.php', '.PHP'], '', $file);
        }
        unset($file);

        $files = apply_filters('dc_manage_widgets_list', $files, $files);

        return apply_filters('dc_manage_' . strtolower($this->get_name()) . '_widgets_list', $files, $files);
    }
}