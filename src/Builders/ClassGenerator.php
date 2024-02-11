<?php
namespace Joegabdelsater\CatapultBase\Builders;

class ClassGenerator {

    private $filePath = '';
    private $fileName = '';
    private $content = '';

    public function __construct($filePath, $fileName, $content) {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->content = $content;
    }

    public function getFullPath() {
        return $this->filePath . '/' . $this->fileName;
    }

    public function generate() {
        file_put_contents($this->getFullPath(), $this->content);
        return $this;
    }

    public function setFilePath($filePath) {
        $this->filePath = $filePath;
        return $this;

    }

    public function setFileName($fileName) {
        $this->fileName = $fileName;
        return $this;

    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getFilePath() {
        return $this->filePath;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public  function modifyContent($stringModifications = [], $regexModifications = []) {
        $file = file_get_contents($this->getFullPath());
        $file = str_replace(array_keys($stringModifications), array_values($stringModifications), $file);
        $file = preg_replace(array_keys($regexModifications), array_values($regexModifications), $file);
        file_put_contents($this->getFullPath(), $file);
        return $this;
    }

    public function renameFile($newName) {
        $oldFullPath = $this->getFullPath();
        $this->setFileName($newName);
        rename($oldFullPath, $this->getFullPath());
        return $this;

    }

    public function moveFile($newPath) {
        $oldFullPath = $this->getFullPath();
        $this->setFilePath($newPath);
        rename($oldFullPath, $this->getFullPath());
        return $this;
    }
}