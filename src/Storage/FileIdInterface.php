<?php

namespace Jnjxp\Uploader\Storage;

interface FileIdInterface
{
    public function generate(string $filename) : string;
}
