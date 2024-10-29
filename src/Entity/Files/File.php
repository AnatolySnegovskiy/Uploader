<?php

namespace CarrionGrow\Uploader\Entity\Files;

use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Entity\Entity;
use CarrionGrow\Uploader\Entity\Mimes;
use CarrionGrow\Uploader\Exception\Code;
use CarrionGrow\Uploader\Exception\Exception;

class File extends Entity implements UploadHandlerInterface
{
    protected string $tempPath = '';

    protected float $size = 0;

    protected string $originalName = '';

    protected string $extension = '';

    protected string $type = '';

    protected string $name = '';

    protected string $fileDir = '';

    protected string $filePath = '';

    protected string $rawName = '';

    protected readonly Config $config;

    #region getters

    /**
     * @psalm-api
     */
    public function getTempPath(): string
    {
        return $this->tempPath;
    }

    /**
     * @psalm-api
     */
    public function getSize(): float
    {
        return $this->size;
    }

    /**
     * @psalm-api
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @psalm-api
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @psalm-api
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @psalm-api
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @psalm-api
     */
    public function getFileDir(): string
    {
        return $this->fileDir;
    }

    /**
     * @psalm-api
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @psalm-api
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
    public function behave(array $file): void
    {
        $this->tempPath = $file['tmp_name'];
        $this->size = round($file['size'] / 1024, 2);
        $this->originalName = $file['name'];
        $this->type = $file['mimes'];
        $this->extension = $this->extractExtension($file['name']);
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
     * @throws Exception
     */
    private function validateFileType(): void
    {
        $allowedTypes = $this->config->getAllowedTypes();

        if ($allowedTypes === '*') {
            return;
        }

        if (empty($allowedTypes) or !is_array($allowedTypes)) {
            throw new Exception(Code::FILETYPE);
        }

        foreach ($allowedTypes as $allowed_type) {
            if (!isset(Mimes::EXTENSION_LIST[$allowed_type])) {
                continue;
            }

            $mime = Mimes::EXTENSION_LIST[$allowed_type];

            if (
                (is_array($mime) && in_array($this->type, $mime, true)) ||
                $mime === $this->type
            ) {
                return;
            }
        }

        throw new Exception(Code::FILETYPE);
    }

    private function extractExtension(string $filename): string
    {
        $x = explode('.', $filename);

        if (count($x) === 1 && $this->config->isExtensionByMimes()) {
            $extensionResult = '';

            foreach (Mimes::EXTENSION_LIST as $extension => $mimesList) {
                if (!is_array($mimesList) && str_contains($mimesList, $this->type)) {
                    $extensionResult = '.' . $extension;
                    break;
                }
            }

            if (empty($extensionResult)) {
                foreach (Mimes::EXTENSION_LIST as $extension => $mimesList) {
                    if (is_array($mimesList) && in_array($this->type, $mimesList)) {
                        $extensionResult = '.' . $extension;
                        break;
                    }
                }
            }

            return $extensionResult;
        } elseif (count($x) === 1) {
            return '';
        }

        $ext = $this->config->isFileExtToLower() ? strtolower(end($x)) : end($x);

        return '.' . $ext;
    }

    /**
     * @throws Exception
     */
    private function validateFileSize(): void
    {
        if ($this->config->getMaxSize() !== 0 && $this->config->getMaxSize() < $this->size) {
            throw new Exception(Code::FILE_SIZE);
        }

    }

    private function prepFilename(string $filename): string
    {
        if (($extPos = strrpos($filename, '.')) === false) {
            return $filename;
        }

        $filename = substr($filename, 0, $extPos);

        return str_replace('.', '_', $filename);
    }

    /**
     * @throws Exception
     */
    private function setFilename(string $filename): string
    {
        $path = $this->config->getUploadPath();

        if ($this->config->isEncryptName()) {
            $filename = md5(uniqid((string)mt_rand()));
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

        if ($newFilename === '') {
            throw new Exception(Code::FILE_COPYING, 'The file name you submitted already exists on the server');
        }

        return $newFilename;
    }

    private function limitFilenameLength(string $filename): string
    {
        $length = $this->config->getMaxFilename();

        if ($length == 0 || strlen($filename) < $length) {
            return $filename;
        }

        return substr($filename, 0, $length);
    }
}
