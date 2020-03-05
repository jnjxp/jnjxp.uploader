<?php

namespace Jnjxp\Uploader\Storage;

class FileId implements FileIdInterface
{
    public function generate(string $filename) : string
    {
        $info = pathinfo($filename);
        $ext  = isset($info['extension'])
            ? '.' . strtolower($info['extension'])
            : '';
        $slug = $this->slug($info['filename']);
        $date = date('/Y/m/d/');

        return $date . $slug . $ext;
    }

    protected function slug(string $name) : string
    {
        $name = preg_replace('/[^a-z0-9-]+/', '-', strtolower($name));
        $name = preg_replace("/-+/", '-', $name);
        $name = trim($name, '-');
        return $name;
    }
}
