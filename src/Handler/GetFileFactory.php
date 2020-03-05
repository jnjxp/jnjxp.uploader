<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use Jnjxp\Filed\FileResponderInterface;
use Jnjxp\Uploader\StorageInterface;
use Psr\Container\ContainerInterface;

class GetFileFactory
{
    public function __invoke(ContainerInterface $container) : GetFile
    {
        return new GetFile(
            $container->get(StorageInterface::class),
            $container->get(FileResponderInterface::class),
        );
    }
}
