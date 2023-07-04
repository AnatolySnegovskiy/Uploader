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
    /** @var float */
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
     * @psalm-api
     */
    public function getTempPath(): string
    {
        return $this->tempPath;
    }

    /**
     * @return float
     * @psalm-api
     */
    public function getSize(): float
    {
        return $this->size;
    }

    /**
     * @return string
     * @psalm-api
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     * @psalm-api
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     * @psalm-api
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     * @psalm-api
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     * @psalm-api
     */
    public function getFileDir(): string
    {
        return $this->fileDir;
    }

    /**
     * @return string
     * @psalm-api
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
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
    public function behave(array $file)
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
     * @return void
     * @throws Exception
     */
    private function validateFileType()
    {
        $allowedTypes = $this->config->getAllowedTypes();

        if ($allowedTypes === '*') {
            return;
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
                return;
            }
        }

        throw new Exception(Code::FILETYPE);
    }

    /**
     * @param $filename
     * @return string
     */
    private function extractExtension($filename): string
    {
        $x = explode('.', $filename);

        if (count($x) === 1 && $this->config->isExtensionByMimes()) {
            $extensionResult = '';

            foreach (Mimes::EXTENSION_LIST as $extension => $mimesList) {
                if (!is_array($mimesList) && strpos($mimesList, $this->type) !== false) {
                    $extensionResult =  '.' . $extension;
                    break;
                }
            }

            if (empty($extensionResult)) {
                foreach (Mimes::EXTENSION_LIST as $extension => $mimesList) {
                    if (is_array($mimesList) && in_array($this->type, $mimesList)) {
                        $extensionResult =  '.' . $extension;
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

        if ($newFilename === '')
            throw new Exception(2011, 'The file name you submitted already exists on the server');

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