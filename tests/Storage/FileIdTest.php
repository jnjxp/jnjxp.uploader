<?php

declare(strict_types=1);

namespace Jnjxp\Uploader\Storage;

use PHPUnit\Framework\TestCase;

class FileIdTest extends TestCase
{
    protected $fileid;

    public function setup() : void
    {
        $this->fileid = new FileId();
    }

    public function testGenerate()
    {
        $name = 'Foo Bar.JPG';
        $expect = date('/Y/m/d/') . 'foo-bar.jpg';
        $fid = $this->fileid->generate($name);
        $this->assertEquals($expect, $fid);
    }
}
