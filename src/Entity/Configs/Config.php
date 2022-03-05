<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Entity\Entity;
use CarrionGrow\Uploader\Entity\Files\File;
use CarrionGrow\Uploader\Entity\Files\UploadHandlerInterface;

class Config extends Entity
{
    /** @var string */
    private $fileName = '';
    /** @var string */
    private $uploadPath = '';
    /** @var string|array */
    private $allowedTypes = '*';
    /** @var bool */
    private $fileExtToLower = false;
    /** @var bool */
    private $overwrite = false;
    /** @var bool */
    private $encryptName = false;
    /** @var bool */
    private $removeSpaces = true;
    /** @var bool */
    private $modMimeFix = true;
    /** @var bool */
    private $skipError = false;
    /** @var int */
    private $maxSize = 0;
    /** @var int */
    private $maxFilename = 0;
    /** @var int */
    private $maxFilenameIncrement = 100;
    /** @var bool  */
    private $extensionByMimes = true;

    /** @var UploadHandlerInterface */
    protected $handler;

    /**
     * @return mixed
     */
    public function getHandler(): UploadHandlerInterface
    {
        return $this->handler;
    }

    public function __construct()
    {
        $this->handler = new File($this);
        $this->uploadPath = dirname(__FILE__, 6) . '/uploaded';
    }

    /**
     * @return string
     */
    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * @param string $uploadPath
     * @return self
     */
    public function setUploadPath(string $uploadPath): self
    {
        $this->uploadPath = rtrim($uploadPath, '/') . '/';
        return $this;
    }

    /**
     * @return string|array
     */
    public function getAllowedTypes()
    {
        return $this->allowedTypes;
    }

    /**
     * @param string|array $allowedTypes
     * @return self
     */
    public function setAllowedTypes($allowedTypes): self
    {
        $this->allowedTypes =
            (is_array($allowedTypes) or $allowedTypes === '*')
                ? $allowedTypes
                : explode('|', $allowedTypes);

        return $this;
    }

    /**
     * @return bool
     */
    public function isFileExtToLower(): bool
    {
        return $this->fileExtToLower;
    }

    /**
     * @param bool $fileExtToLower
     * @return self
     */
    public function setFileExtToLower(bool $fileExtToLower): self
    {
        $this->fileExtToLower = $fileExtToLower;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOverwrite(): bool
    {
        return $this->overwrite;
    }

    /**
     * @param bool $overwrite
     * @return self
     */
    public function setOverwrite(bool $overwrite): self
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEncryptName(): bool
    {
        return $this->encryptName;
    }

    /**
     * @param bool $encryptName
     * @return self
     */
    public function setEncryptName(bool $encryptName): self
    {
        $this->encryptName = $encryptName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRemoveSpaces(): bool
    {
        return $this->removeSpaces;
    }

    /**
     * @param bool $removeSpaces
     * @return self
     */
    public function setRemoveSpaces(bool $removeSpaces): self
    {
        $this->removeSpaces = $removeSpaces;
        return $this;
    }

    /**
     * @return bool
     */
    public function isModMimeFix(): bool
    {
        return $this->modMimeFix;
    }

    /**
     * @param bool $modMimeFix
     * @return self
     */
    public function setModMimeFix(bool $modMimeFix): self
    {
        $this->modMimeFix = $modMimeFix;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSkipError(): bool
    {
        return $this->skipError;
    }

    /**
     * @param bool $skipError
     * @return self
     */
    public function setSkipError(bool $skipError): self
    {
        $this->skipError = $skipError;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    /**
     * @param int $maxSize
     * @return self
     */
    public function setMaxSize(int $maxSize): self
    {
        $this->maxSize = max($maxSize, 0);
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return self
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxFilename(): int
    {
        return $this->maxFilename;
    }

    /**
     * @param int $maxFilename
     * @return self
     */
    public function setMaxFilename(int $maxFilename): self
    {
        $this->maxFilename = max($maxFilename, 0);
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxFilenameIncrement(): int
    {
        return $this->maxFilenameIncrement;
    }

    /**
     * @param int $maxFilenameIncrement
     * @return self
     */
    public function setMaxFilenameIncrement(int $maxFilenameIncrement): self
    {
        $this->maxFilenameIncrement = max($maxFilenameIncrement, 0);
        return $this;
    }

    /**
     * @return bool
     */
    public function isExtensionByMimes(): bool
    {
        return $this->extensionByMimes;
    }

    /**
     * @param bool $extensionByMimes
     * @return $this
     */
    public function setExtensionByMimes(bool $extensionByMimes): self
    {
        $this->extensionByMimes = $extensionByMimes;
        return $this;
    }
}