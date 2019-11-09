# Aliyun-oss-storage for Laravel 5+
fork自jacobcyl/ali-oss-storage，由于原作者停止维护，故fork过来，修复bug，调整了host配置和获取url策略，更名为DarryZhao/ali-oss-storage。

## Require
- Laravel 5+
- cURL extension

##Installation
In order to install AliOSS-storage, just add

    "DarryZhao/ali-oss-storage": "^3.0"

to your composer.json. Then run `composer install` or `composer update`.  
Or you can simply run below command to install:

    "composer require DarryZhao/ali-oss-storage:^3.0"
    
## Configuration
Add the following in app/filesystems.php:
```php
'disks'=>[
    ...
    'oss' => [
            'driver'        => 'oss',
            'access_id'     => '<Your Aliyun OSS AccessKeyId>',
            'access_key'    => '<Your Aliyun OSS AccessKeySecret>',
            'bucket'        => '<OSS bucket name>',
            'endpoint'      => '<the endpoint of OSS, E.g: oss-cn-hangzhou.aliyuncs.com | custom domain, E.g:img.abc.com>', // OSS 外网节点
            'endpoint_internal' => '<internal endpoint [OSS内网节点] 如：oss-cn-shenzhen-internal.aliyuncs.com>', // OSS内网节点
            'cdn_domain'     => '<CDN domain, cdn域名>', // cdn域名或自定义域名
            'host_use'     => '<CDN domain, cdn域名>', // 使用哪个域名进行操作，可选值：endpoint/endpoint_internal/cdn_domain，默认endpoint
            'ssl'           => <true|false> // true to use 'https://' and false to use 'http://'. default is false,
            'debug'         => <true|false>
    ],
    ...
]
```
Then set the default driver in app/filesystems.php:
```php
'default' => 'oss',
```
Ok, well! You are finish to configure. Just feel free to use Aliyun OSS like Storage!

## Usage
See [Larave doc for Storage](https://laravel.com/docs/5.2/filesystem#custom-filesystems)
Or you can learn here:

> First you must use Storage facade

```php
use Illuminate\Support\Facades\Storage;
```    
> Then You can use all APIs of laravel Storage

```php
Storage::disk('oss'); // if default filesystems driver is oss, you can skip this step

//fetch all files of specified bucket(see upond configuration)
Storage::files($directory);
Storage::allFiles($directory);

Storage::put('path/to/file/file.jpg', $contents); //first parameter is the target file path, second paramter is file content
Storage::putFile('path/to/file/file.jpg', 'local/path/to/local_file.jpg'); // upload file from local path

Storage::get('path/to/file/file.jpg'); // get the file object by path
Storage::exists('path/to/file/file.jpg'); // determine if a given file exists on the storage(OSS)
Storage::size('path/to/file/file.jpg'); // get the file size (Byte)
Storage::lastModified('path/to/file/file.jpg'); // get date of last modification

Storage::directories($directory); // Get all of the directories within a given directory
Storage::allDirectories($directory); // Get all (recursive) of the directories within a given directory

Storage::copy('old/file1.jpg', 'new/file1.jpg');
Storage::move('old/file1.jpg', 'new/file1.jpg');
Storage::rename('path/to/file1.jpg', 'path/to/file2.jpg');

Storage::prepend('file.log', 'Prepended Text'); // Prepend to a file.
Storage::append('file.log', 'Appended Text'); // Append to a file.

Storage::delete('file.jpg');
Storage::delete(['file1.jpg', 'file2.jpg']);

Storage::makeDirectory($directory); // Create a directory.
Storage::deleteDirectory($directory); // Recursively delete a directory.It will delete all files within a given directory, SO Use with caution please.

Storage::putRemoteFile('target/path/to/file/jacob.jpg', 'http://example.com/jacob.jpg'); //upload remote file to storage by remote url
Storage::url('path/to/img.jpg') // 获取文件链接，先使用cdn_domain，再使用endpoint
Storage::getPrivateUrl('path/to/img.jpg') // 获取内网链接，必须配置endpoint_internal
Storage::getPublicUrl('path/to/img.jpg') // 获取外网链接，先使用cdn_domain，再使用endpoint
```

## License
Source code is release under MIT license. Read LICENSE file for more information.
