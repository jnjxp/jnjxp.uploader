<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use Jnjxp\Uploader\StorageInterface;
use Psr\Container\ContainerInterface;

class UploadFileFactory
{
    public function __invoke(ContainerInterface $container) : UploadFile
    {
        return new UploadFile(
            $container->get(StorageInterface::class),
            $container->get(UploadResponderInterface::class)
        );
    }
}
