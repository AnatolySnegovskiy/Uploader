<?php

namespace CarrionGrow\Uploader\Exception;

class FilesException extends Exception
{
    private const FILE_TO_UPLOAD =
        [
            UPLOAD_ERR_INI_SIZE => Code::FILE_SIZE,
            UPLOAD_ERR_FORM_SIZE => Code::FILE_SIZE,
            UPLOAD_ERR_PARTIAL => Code::ONLY_PARTIALLY,
            UPLOAD_ERR_NO_TMP_DIR => Code::READING_DIRECTORY,
            UPLOAD_ERR_CANT_WRITE => Code::READING_DIRECTORY,
            UPLOAD_ERR_EXTENSION => Code::FILE_FORMAT,
            UPLOAD_ERR_NO_FILE => Code::NOT_FILE
        ];

    public function __construct(int $code)
    {
        parent::__construct(self::FILE_TO_UPLOAD[$code] ?? Code::NOT_FILE);
    }
}