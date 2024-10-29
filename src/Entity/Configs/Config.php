<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Entity\Entity;
use CarrionGrow\Uploader\Entity\Files\File;
use CarrionGrow\Uploader\Entity\Files\UploadHandlerInterface;

class Config extends Entity
{
    private string $fileName = '';
    private string $uploadPath;
    private string|array $allowedTypes = '*';
    private bool $fileExtToLower = false;
    private bool $overwrite = false;
    private bool $encryptName = false;
    private bool $removeSpaces = true;
    private bool $modMimeFix = true;
    private bool $skipError = false;
    private int $maxSize = 0;
    private int $maxFilename = 0;
    private int $maxFilenameIncrement = 100;
    private bool $extensionByMimes = true;

    protected UploadHandlerInterface $handler;

    public function __construct()
    {
        $this->handler = new File($this);
        $this->uploadPath = dirname(__FILE__, 6) . '/uploaded';
    }

    /**
     * @psalm-api
     */
    public function getHandler(): UploadHandlerInterface
    {
        return $this->handler;
    }

    /**
     * @psalm-api
     */
    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * @psalm-api
     */
    public function setUploadPath(string $uploadPath): self
    {
        $this->uploadPath = rtrim($uploadPath, '/') . '/';
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getAllowedTypes(): array|string
    {
        return $this->allowedTypes;
    }

    /**
     * @psalm-api
     */
    public function setAllowedTypes(string|array $allowedTypes): self
    {
        $this->allowedTypes =
            (is_array($allowedTypes) or $allowedTypes === '*')
                ? $allowedTypes
                : explode('|', $allowedTypes);

        return $this;
    }

    /**
     * @psalm-api
     */
    public function isFileExtToLower(): bool
    {
        return $this->fileExtToLower;
    }

    /**
     * @psalm-api
     */
    public function setFileExtToLower(bool $fileExtToLower): self
    {
        $this->fileExtToLower = $fileExtToLower;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function isOverwrite(): bool
    {
        return $this->overwrite;
    }

    /**
     * @psalm-api
     */
    public function setOverwrite(bool $overwrite): self
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function isEncryptName(): bool
    {
        return $this->encryptName;
    }

    /**
     * @psalm-api
     */
    public function setEncryptName(bool $encryptName): self
    {
        $this->encryptName = $encryptName;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function isRemoveSpaces(): bool
    {
        return $this->removeSpaces;
    }

    /**
     * @psalm-api
     */
    public function setRemoveSpaces(bool $removeSpaces): self
    {
        $this->removeSpaces = $removeSpaces;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function isModMimeFix(): bool
    {
        return $this->modMimeFix;
    }

    /**
     * @psalm-api
     */
    public function setModMimeFix(bool $modMimeFix): self
    {
        $this->modMimeFix = $modMimeFix;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function isSkipError(): bool
    {
        return $this->skipError;
    }

    /**
     * @psalm-api
     */
    public function setSkipError(bool $skipError): self
    {
        $this->skipError = $skipError;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    /**
     * @psalm-api
     */
    public function setMaxSize(int $maxSize): self
    {
        $this->maxSize = max($maxSize, 0);
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @psalm-api
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMaxFilename(): int
    {
        return $this->maxFilename;
    }

    /**
     * @psalm-api
     */
    public function setMaxFilename(int $maxFilename): self
    {
        $this->maxFilename = max($maxFilename, 0);
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMaxFilenameIncrement(): int
    {
        return $this->maxFilenameIncrement;
    }

    /**
     * @psalm-api
     */
    public function setMaxFilenameIncrement(int $maxFilenameIncrement): self
    {
        $this->maxFilenameIncrement = max($maxFilenameIncrement, 0);
        return $this;
    }

    /**
     * @psalm-api
     */
    public function isExtensionByMimes(): bool
    {
        return $this->extensionByMimes;
    }

    /**
     * @psalm-api
     */
    public function setExtensionByMimes(bool $extensionByMimes): self
    {
        $this->extensionByMimes = $extensionByMimes;
        return $this;
    }
}
