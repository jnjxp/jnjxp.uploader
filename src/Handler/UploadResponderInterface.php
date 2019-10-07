<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

interface UploadResponderInterface
{
    public function invalidRequest(ServerRequestInterface $request) : ResponseInterface;

    public function invalidFile(UploadedFileInterface $file) : ResponseInterface;

    public function noFile() : ResponseInterface;

    public function success(string $fileid) : ResponseInterface;
}
