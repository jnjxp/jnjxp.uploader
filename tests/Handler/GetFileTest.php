<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Jnjxp\Uploader\StorageInterface;
use Jnjxp\Filed\FileResponderInterface;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response;

class FilesystemTest extends TestCase
{
    protected $storage;

    protected $responder;

    protected $attr;

    protected $handler;


    public function setup() : void
    {
        $this->storage = $this->prophesize(StorageInterface::class);
        $this->responder = $this->prophesize(FileResponderInterface::class);
        $this->attr = 'attribute';

        $this->handler = new GetFile(
            $this->storage->reveal(),
            $this->responder->reveal(),
            $this->attr
        );
    }

    public function testGets()
    {
        $file = new \SplFileInfo('bar');
        $response = new Response();
        $request = (new ServerRequest())->withAttribute($this->attr, 'foo');
        $this->storage->get('foo')->willReturn($file);
        $this->responder->respondWithFile($file, $request)->willReturn($response);
        $this->assertSame(
            $response,
            $this->handler->handle($request)
        );
    }
}
