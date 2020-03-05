<?php

namespace Jnjxp\Uploader;

use Psr\Http\Message\UploadedFileInterface;
use SplFileInfo;

interface StorageInterface
{
    public function store(UploadedFileInterface $file) : ?string;

    public function get(string $fileid) : SplFileInfo;
}
