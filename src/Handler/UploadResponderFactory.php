<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class UploadResponderFactory
{
    public function __invoke(ContainerInterface $container) : UploadResponder
    {
        return new UploadResponder(
            $container->get(ResponseFactoryInterface::class)
        );
    }
}
