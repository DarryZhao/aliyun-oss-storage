<?php

namespace DarryZhao\AliOSS;

use DarryZhao\AliOSS\Plugins\GetPrivateUrl;
use DarryZhao\AliOSS\Plugins\GetPublicUrl;
use DarryZhao\AliOSS\Plugins\PutRemoteFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use OSS\OssClient;

class AliOssServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('oss', function ($app, $config) {
            $accessId = $config['access_id'];
            $accessKey = $config['access_key'];

            $cdnDomain = empty($config['cdn_domain']) ? '' : $config['cdn_domain'];
            $bucket = $config['bucket'];
            $ssl = empty($config['ssl']) ? false : $config['ssl'];
            $debug = empty($config['debug']) ? false : $config['debug'];

            $isCname = false;
            if ($config['host_use'] == 'endpoint_internal' && !empty($config['endpoint_internal'])) {
                $host = $config['endpoint_internal'];
            } else if ($config['host_use'] == 'cdn_domain' && !empty($config['cdn_domain'])) {
                $host = $config['cdn_domain'];
                $isCname = true;
            } else {
                $host = $config['endpoint'];
            }

            if ($debug) Log::debug('OSS config:', $config);

            $client = new OssClient($accessId, $accessKey, $host, $isCname);
            $adapter = new AliOssAdapter($client, $bucket, $host, $ssl, $isCname, $debug, $cdnDomain);

            $filesystem = new Filesystem($adapter);

            $filesystem->addPlugin(new GetPublicUrl($config));
            $filesystem->addPlugin(new GetPrivateUrl($config));
            $filesystem->addPlugin(new PutRemoteFile());
            return $filesystem;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }

}
