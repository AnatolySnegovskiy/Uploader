<?php

namespace CarrionGrow\Uploader\Entity\Files;

use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Entity\Entity;
use CarrionGrow\Uploader\Entity\Mimes;
use CarrionGrow\Uploader\Exception\Code;
use CarrionGrow\Uploader\Exception\Exception;

class File extends Entity implements UploadHandlerInterface
{
    /** @var string */
    protected $tempPath;
    /** @var int */
    protected $size;
    /** @var string */
    protected $originalName;
    /** @var string */
    protected $extension;
    /** @var string */
    protected $type;
    /** @var string */
    protected $name;
    /** @var string */
    protected $fileDir;
    /** @var string */
    protected $filePath;
    /** @var string */
    protected $rawName;
    /** @var Config */
    protected $config;
#region getters

    /**
     * @return string
     */
    public function getTempPath(): string
    {
        return $this->tempPath;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFileDir(): string
    {
        return $this->fileDir;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getRawName(): string
    {
        return $this->rawName;
    }
#endregion

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @throws Exception
     */
    public function behave(array $file)
    {
        $this->tempPath = $file['tmp_name'];
        $this->size = round($file['size'] / 1024, 2);
        $this->originalName = $file['name'];
        $this->extension = $this->extractExtension($file['name']);
        $this->type = $file['mimes'];
        $fileName = $this->prepFilename($this->config->getFileName() ?: $file['name']);
        $fileName = $this->limitFilenameLength($fileName);

        if ($this->config->isRemoveSpaces()) {
            $fileName = preg_replace('/\s+/', '_', $fileName);
        }

        $this->name = $this->setFilename($fileName);
        $this->fileDir = $this->config->getUploadPath();
        $this->filePath = $this->config->getUploadPath() . $this->name;
        $this->rawName = $this->name;

        if (!empty($this->extension)) {
            $this->rawName = substr($this->rawName, 0, -strlen($this->extension));
        }

        $this->validateFileType();
        $this->validateFileSize();
    }

    /**
     * @param $filename
     * @return string
     */
    private function extractExtension($filename): string
    {
        $x = explode('.', $filename);

        if (count($x) === 1) {
            return '';
        }

        $ext = $this->config->isFileExtToLower() ? strtolower(end($x)) : end($x);

        return '.' . $ext;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function validateFileType(): bool
    {
        $allowedTypes = $this->config->getAllowedTypes();

        if ($allowedTypes === '*') {
            return TRUE;
        }

        if (empty($allowedTypes) or !is_array($allowedTypes))
            throw new Exception(Code::FILETYPE);

        foreach ($allowedTypes as $allowed_type) {
            if (!isset(Mimes::EXTENSION_LIST[$allowed_type])) {
                continue;
            }

            $mime = Mimes::EXTENSION_LIST[$allowed_type];

            if (
                (is_array($mime) && in_array($this->type, $mime, TRUE)) ||
                $mime === $this->type
            ) {
                return true;
            }
        }

        throw new Exception(Code::FILETYPE);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function validateFileSize(): void
    {
        if ($this->config->getMaxSize() !== 0 && $this->config->getMaxSize() < $this->size)
            throw new Exception(Code::FILE_SIZE);

    }

    /**
     * @param $filename
     * @return mixed|string
     */
    private function prepFilename($filename)
    {
        if (($extPos = strrpos($filename, '.')) === FALSE) {
            return $filename;
        }

        $filename = substr($filename, 0, $extPos);

        return str_replace('.', '_', $filename);
    }

    /**
     * @param string $filename
     * @return string
     * @throws Exception
     */
    private function setFilename(string $filename): string
    {
        $path = $this->config->getUploadPath();

        if ($this->config->isEncryptName()) {
            $filename = md5(uniqid(mt_rand())) . $this->extension;
        }

        if ($this->config->isOverwrite() || !file_exists($path . $filename . $this->extension)) {
            return $filename . $this->extension;
        }

        $newFilename = '';

        for ($i = 1; $i < $this->config->getMaxFilenameIncrement(); $i++) {
            $iterationFileName = sprintf('%s_%d%s', $filename, $i, $this->extension);

            if (!file_exists($path . $iterationFileName)) {
                $newFilename = $iterationFileName;
                break;
            }
        }

        if ($newFilename === '')
            throw new Exception('The file name you submitted already exists on the server', 2011);

        return $newFilename;
    }

    /**
     * @param string $filename
     * @return string
     */
    private function limitFilenameLength(string $filename): string
    {
        $length = $this->config->getMaxFilename();

        if ($length == 0 || strlen($filename) < $length) {
            return $filename;
        }

        return substr($filename, 0, $length);
    }
}