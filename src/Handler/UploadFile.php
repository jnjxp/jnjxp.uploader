<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use Jnjxp\Uploader\StorageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UploadFile implements RequestHandlerInterface
{
    protected $storage;
    protected $responder;
    protected $fileKey;

    public function __construct(
        StorageInterface $storage,
        UploadResponderInterface $responder,
        string $filekey = 'fileToUpload'
    ) {
        $this->storage = $storage;
        $this->responder = $responder;
        $this->fileKey  = $filekey;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        if (! $this->isValidRequest($request)) {
            return $this->responder->invalidRequest($request);
        }

        if (! $file = $this->getFile($request)) {
            return $this->responder->noFile();
        }

        if (! $this->isValidFile($file)) {
            return $this->responder->invalidFile($file);
        }

        $fileid = $this->storage->store($file);
        return $this->responder->success($fileid);
    }

    protected function isValidRequest(ServerRequestInterface $request) : bool
    {
        return $request->getMethod() == 'POST';
    }

    protected function getFile(ServerRequestInterface $request) : ?UploadedFileInterface
    {
        $files = $request->getUploadedFiles();
        return $files[$this->fileKey] ?? null;
    }

    protected function isValidFile(UploadedFileInterface $file) : bool
    {
        return $file->getError() == UPLOAD_ERR_OK;
    }
}
