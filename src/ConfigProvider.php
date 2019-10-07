<?php

declare(strict_types=1);

namespace Jnjxp\Uploader;

use Zend\Expressive\Application;
use Phly\Expressive\ConfigFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'invokables' => [
                Storage\FileIdInterface::class => Storage\FileId::class
            ],
            'factories'  => [
                'config-uploader.storage' => ConfigFactory::class,
                Handler\GetFile::class => Handler\GetFileFactory::class,
                Handler\UploadFile::class => Handler\UploadFileFactory::class,
                Handler\UploadResponderInterface::class => Handler\UploadResponderFactory::class,
                StorageInterface::class => Storage\FilesystemFactory::class
            ],
        ];
    }

    public function registerRoutes(Application $app, string $path = '/upload') : void
    {
        $app->post($path, Handler\UploadFile::class, 'uploader.upload');
        $app->get($path . '/{fileid:.*}', Handler\GetFile::class, 'uploader.get');
    }
}
