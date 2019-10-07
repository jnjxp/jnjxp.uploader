<?php

namespace Jnjxp\Uploader\Storage;

use Psr\Http\Message\UploadedFileInterface;
use Jnjxp\Uploader\StorageInterface;
use SplFileInfo;

class Filesystem implements StorageInterface
{
    protected $fileid;
    protected $root;

    public function __construct(string $root, FileIdInterface $fileid)
    {
        $this->fileid = $fileid;
        $this->root = realpath($root);
        if (! $this->root || ! is_writable($root)) {
            throw new \Exception("Invalid root: $root");
        }
    }

    public function store(UploadedFileInterface $file) : ?string
    {
        $name   = $file->getClientFilename();
        $fileid = $this->fileid->generate($name);
        $dest   = $this->root . '/' . $fileid;
        $info   = pathinfo($dest);

        if (! is_dir($info['dirname'])) {
            mkdir($info['dirname'], 0755, true);
        }

        if (file_exists($dest)) {
            throw new \Exception('File already exists: ' . $dest);
        }

        $file->moveTo($dest);
        return $fileid;
    }

    public function get(string $fileid) : SplFileInfo
    {
        $path = $this->path($fileid);
        return new SplFileInfo($path);
    }

    protected function path(string $fileid) : string
    {
        $path = $this->root . '/' . trim($fileid, '/.');
        if (! $this->isValid($path)) {
            // @codeCoverageIgnoreStart
            throw new \Exception("Bad fileid: $fileid");
            // @codeCoverageIgnoreEnd
        }
        return $path;
    }

    protected function isValid($path) : bool
    {
        $parent = realpath(pathinfo($path, PATHINFO_DIRNAME)) . '/';
        $start  = substr($parent, 0, strlen($this->root . '/'));
        return $start === $this->root . '/';
    }
}
