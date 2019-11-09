<?php

namespace DarryZhao\AliOSS\Plugins;

use Illuminate\Support\Str;
use League\Flysystem\Plugin\AbstractPlugin;

class GetPrivateUrl extends AbstractPlugin
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
        return 'getPrivateUrl';
    }

    public function handle($path)
    {
        if (!$this->filesystem->getAdapter()->has($path)) {
            throw new \Exception($path . ' not found');
        }
        if (empty($this->config['endpoint_internal'])) {
            throw new \Exception('endpoint_internal not config');
        }
        if (Str::startsWith($this->config['endpoint_internal'], ['https://', 'http://'])) {
            $host = str_replace(['https://', 'http://'], ["https://{$this->config['bucket']}.", "http://{$this->config['bucket']}."], $this->config['endpoint']);
        } else {
            $host = ($this->config['ssl'] ? 'https://' : 'http://') . $this->config['bucket'] . '.' . $this->config['endpoint'];
        }

        return $host . '/' . ltrim($path, '/');
    }
}
