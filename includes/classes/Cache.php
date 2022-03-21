<?php

namespace DCore;
/**
 * Class Cache
 * @package DCore
 */
class Cache
{
    private array $caches = [];
    private string $cachesPath = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'caches' . DIRECTORY_SEPARATOR . 'dcore' . DIRECTORY_SEPARATOR;

    public function set(string $name, $data, int $expire, string $group): bool
    {
        if (empty($group)) {
            $group = 'default';
        }
        $cacheName = 'dc_cache_' . $group . '_' . $name;
        $this->caches[$cacheName] = $data;

        return set_site_transient($cacheName, $this->caches[$cacheName], $expire);
    }

    public function get(string $name, $default, string $group)
    {
        $cacheName = 'dc_cache_' . $group . '_' . $name;
        if (!isset($this->caches[$cacheName])) {
            $this->caches[$cacheName] = get_site_transient($cacheName);
        }

        return $this->caches[$cacheName] === false ? $default : $this->caches[$cacheName];
    }

    private function _groupPath(string $group): string
    {
        return $this->cachesPath . str_replace(['/', '\\', '.', '@'], '_', $group) . DIRECTORY_SEPARATOR;
    }

    private function _filePath(string $name, string $group): string
    {
        return $this->_groupPath($group) . sha1(str_replace(['/', '\\', '.', '@'], '_', $name));
    }

    public function setFile(string $name, $data, int $expire, string $group): bool
    {
        if (!file_exists(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'caches')) {
            @mkdir(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'caches');
        }
        if (!file_exists($this->cachesPath)) {
            @mkdir($this->cachesPath);
        }
        $groupPath = $this->_groupPath($group);
        $filePath = $this->_filePath($name, $group);
        $fileName = str_replace($groupPath, '', $filePath);
        if (!file_exists($this->_groupPath($group))) {
            @mkdir($this->_groupPath($group));
        }
        $expire += time();
            $this->set($fileName, $expire, $expire + 1, $group);
        return file_put_contents($this->_filePath($name, $group), serialize($data)) !== false;
    }

    public function getFile(string $name, $default, string $group)
    {
        $filePath = $this->_filePath($name, $group);
        if (!file_exists($filePath)) {
            return $default;
        }
        $groupPath = $this->_groupPath($group);
        $fileName = str_replace($groupPath, '', $filePath);
        $expire = $this->get($fileName, false, $group);
        if ($expire === false || (is_numeric($expire) && (int)$expire < time())) {
            @unlink($filePath);
            return $default;
        }
        return unserialize(file_get_contents($filePath));
    }
}