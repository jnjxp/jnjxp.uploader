<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Handler;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Jnjxp\Uploader\StorageInterface;
use Jnjxp\Filed\FileResponderInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class UploadFileTest extends TestCase
{
    protected $storage;

    protected $responder;

    protected $filekey;

    protected $handler;


    public function setup() : void
    {
        $this->storage = $this->prophesize(StorageInterface::class);
        $this->responder = $this->prophesize(UploadResponderInterface::class);
        $this->filekey = 'key';

        $this->handler = new UploadFile(
            $this->storage->reveal(),
            $this->responder->reveal(),
            $this->filekey
        );
    }

    public function testUpload()
    {
        $file = $this->prophesize(UploadedFileInterface::class);
        $file->getError()->willReturn(UPLOAD_ERR_OK);
        $upload = $file->reveal();
        $response = new Response();
        $request = (new ServerRequest())
            ->withMethod('POST')
            ->withUploadedFiles([$this->filekey => $upload]);
        $this->storage->store($upload)->willReturn('foo');
        $this->responder->success('foo')->willReturn($response);
        $this->assertSame(
            $response,
            $this->handler->handle($request)
        );
    }

    public function testInvalidRequest()
    {
        $response = new Response();
        $request = (new ServerRequest());
        $this->responder->invalidRequest($request)->willReturn($response);
        $this->assertSame(
            $response,
            $this->handler->handle($request)
        );
    }

    public function testNoFile()
    {
        $response = new Response();
        $request = (new ServerRequest())
            ->withMethod('POST');
        $this->responder->noFile()->willReturn($response);
        $this->assertSame(
            $response,
            $this->handler->handle($request)
        );
    }

    public function testInvalidFile()
    {
        $file = $this->prophesize(UploadedFileInterface::class);
        $file->getError()->willReturn(UPLOAD_ERR_NO_TMP_DIR);
        $upload = $file->reveal();
        $response = new Response();
        $request = (new ServerRequest())
            ->withMethod('POST')
            ->withUploadedFiles([$this->filekey => $upload]);
        $this->responder->invalidFile($upload)->willReturn($response);
        $this->assertSame(
            $response,
            $this->handler->handle($request)
        );
    }
}
