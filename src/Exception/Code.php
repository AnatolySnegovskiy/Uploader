<?php

namespace CarrionGrow\Uploader\Exception;

enum Code: int
{
    case ARCHIVE_EXTRACT = 2010;
    case FILE_COPYING = 2011;
    case READING_DIRECTORY = 2020;
    case RECEIVING_DATA = 2021;
    case FILE_CONTENT = 2030;
    case FILE_FORMAT = 2031;
    case FILE_SIZE = 2032;
    case NOT_FILE = 2033;
    case FILETYPE = 2034;
    case RESOLUTION = 2035;
    case ONLY_PARTIALLY = 2036;
    case REMOTE_URI = 2040;
    case REMOTE_GETTING = 2041;
    case VIDEO_DURATION = 2060;
    case VIDEO_CODEC = 2062;
    case AUDIO_CODEC = 2063;
    case VIDEO_BITRATE = 2064;
    case ERROR_CONFIG = 4000;

    public static function getMessage(self $code): string
    {
        return match ($code) {
            self::ARCHIVE_EXTRACT => 'Archive extract error',
            self::FILE_COPYING => 'File copying error',
            self::READING_DIRECTORY => 'Error reading directory. Impossible to receive the content',
            self::RECEIVING_DATA => 'Receiving data error. Html file not found',
            self::FILE_CONTENT => 'Invalid file content',
            self::FILE_FORMAT => 'Invalid file format',
            self::FILE_SIZE => 'Invalid file size',
            self::NOT_FILE => 'Does not file',
            self::FILETYPE => 'Unavailable filetype',
            self::RESOLUTION => 'Invalid image width or height amount',
            self::ONLY_PARTIALLY => 'The file was only partially uploaded',
            self::REMOTE_URI => 'Invalid remote file URI',
            self::REMOTE_GETTING => 'Failed remote file getting',
            self::VIDEO_DURATION => 'Invalid video duration',
            self::VIDEO_CODEC => 'Invalid video codec type',
            self::AUDIO_CODEC => 'Invalid audio codec type',
            self::VIDEO_BITRATE => 'Invalid video bitrate',
            self::ERROR_CONFIG => 'Error in configuration',
        };
    }
}
