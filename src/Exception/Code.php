<?php

namespace CarrionGrow\Uploader\Exception;

class Code
{
    public const ARCHIVE_EXTRACT = 2010;
    public const FILE_COPYING = 2011;
    public const READING_DIRECTORY = 2020;
    public const RECEIVING_DATA = 2021;
    public const FILE_CONTENT = 2030;
    public const FILE_FORMAT = 2031;
    public const FILE_SIZE = 2032;
    public const NOT_FILE = 2033;
    public const FILETYPE = 2034;
    public const RESOLUTION = 2035;
    public const ONLY_PARTIALLY = 2036;
    public const REMOTE_URI = 2040;
    public const REMOTE_GETTING = 2041;
    public const VIDEO_DURATION = 2060;
    public const VIDEO_CODEC = 2062;
    public const AUDIO_CODEC = 2063;
    public const VIDEO_BITRATE = 2064;

    public const MESSAGE_LIST = [
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
    ];
}