<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Storage;

use Psr\Container\ContainerInterface;

class FilesystemFactory
{
    public function __invoke(ContainerInterface $container) : Filesystem
    {
        $root = $container->get('config')['uploader']['storage']['root'] ?? null;
        return new Filesystem(
            $root,
            $container->get(FileIdInterface::class)
        );
    }
}
