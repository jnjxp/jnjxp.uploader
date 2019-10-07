<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Storage;

use Psr\Container\ContainerInterface;

class FilesystemFactory
{
    public function __invoke(ContainerInterface $container) : Filesystem
    {
        $config = $container->get('config-uploader.storage');
        return new Filesystem(
            $container->get(FileIdInterface::class),
            $config['root']
        );
    }
}
