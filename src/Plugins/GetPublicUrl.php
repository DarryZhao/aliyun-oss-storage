<?php

namespace DarryZhao\AliOSS\Plugins;

use Illuminate\Support\Str;
use League\Flysystem\Plugin\AbstractPlugin;

class GetPublicUrl extends AbstractPlugin
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'getPublicUrl';
    }

    public function handle($path)
    {
        if (!$this->filesystem->getAdapter()->has($path)) {
            throw new \Exception($path . ' not found');
        }
        if (empty($this->config['cdn_domain'])) {
            if (Str::startsWith($this->config['cdn_domain'], ['https://', 'http://'])) {
                $host = $this->config['cdn_domain'];
            } else {
                $host = ($this->config['ssl'] ? 'https://' : 'http://') . $this->config['cdn_domain'];
            }
        } else {
            if (Str::startsWith($this->config['endpoint'], ['https://', 'http://'])) {
                $host = str_replace(['https://', 'http://'], ["https://{$this->config['bucket']}.", "http://{$this->config['bucket']}."], $this->config['endpoint']);
            } else {
                $host = ($this->config['ssl'] ? 'https://' : 'http://') . $this->config['bucket'] . '.' . $this->config['endpoint'];
            }
        }

        return $host . '/' . ltrim($path, '/');
    }
}
