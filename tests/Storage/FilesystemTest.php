<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Storage;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;

class FilesystemTest extends TestCase
{
    protected $filesystem;

    protected $fid;

    protected $fileid;

    public function setup() : void
    {
        $this->fid = $this->prophesize(FileId::class);
        $this->fileid = $this->fid->reveal();
        $this->filesystem = new Filesystem(__DIR__ . '/fake', $this->fileid);
    }

    public function testInvalidRoot()
    {
        $this->expectException('Exception');
        new Filesystem(__DIR__ . '/non-existant', new FileId());
    }

    protected function storeForId($fileid)
    {
        $name = 'filename';
        $this->fid->generate($name)->willReturn($fileid);
        $upload = $this->prophesize(UploadedFileInterface::class);
        $upload->getClientFilename()->willReturn($name);
        $upload->moveTo(__DIR__ . '/fake/' . $fileid)->willReturn(1);

        return $this->filesystem->store($upload->reveal());
    }

    public function testStores()
    {
        $result = $this->storeForId('foo');
        $this->assertEquals('foo', $result);
    }

    public function testExistsException()
    {
        $this->expectException('Exception');
        $this->storeForId('bar');
    }

    public function testCreatesDirs()
    {
        $fid = '/sub/dir/baz.thing';
        $dir = __DIR__ . '/fake/sub/dir';
        $result = $this->storeForId($fid);
        $this->assertEquals($fid, $result);
        $this->assertTrue(is_dir($dir));
        rmdir(__DIR__ . '/fake/sub/dir');
        rmdir(__DIR__ . '/fake/sub');
    }

    public function testGet()
    {
        $file = $this->filesystem->get('bar');
        $this->assertTrue(file_exists((string) $file));
    }

    public function testGetBad()
    {
        $file = $this->filesystem->get('../../bing');
        $this->assertFalse(file_exists((string) $file));
    }
}
