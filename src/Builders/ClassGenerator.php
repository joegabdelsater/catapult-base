<?php
namespace Joegabdelsater\CatapultBase\Builders;

class ClassGenerator {

    public static function generate($fileName, $content, $contentType) {
        $dir = config("directories.{$contentType}");
        file_put_contents("$dir/$fileName", $content);
    }
}