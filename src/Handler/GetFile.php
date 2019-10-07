<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use Jnjxp\Filed\FileResponderInterface;
use Jnjxp\Uploader\StorageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetFile implements RequestHandlerInterface
{
    protected $storage;
    protected $responder;
    protected $attribute;

    public function __construct(
        StorageInterface $storage,
        FileResponderInterface $responder,
        string $attribute = 'fileid'
    ) {
        $this->storage   = $storage;
        $this->responder = $responder;
        $this->attribute = $attribute;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $fileid = $this->getFileId($request);
        $file = $fileid ? $this->storage->get($fileid) : null;
        return $this->responder->respondWithFile($file, $request);
    }

    protected function getFileId(ServerRequestInterface $request) : ?string
    {
        return $request->getAttribute($this->attribute);
    }
}
