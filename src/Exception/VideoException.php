<?php

namespace CarrionGrow\Uploader\Exception;

class VideoException
{
    public static function widthLarger(int $width): Exception
    {
        return self::exceptionResolution(sprintf('The video width value is larger than the permitted size: %d px', $width));
    }

    public static function heightLarger(int $height): Exception
    {
        return self::exceptionResolution(sprintf('The video height value is larger than the permitted size: %d px', $height));
    }

    public static function widthLess(int $width): Exception
    {
        return self::exceptionResolution(sprintf('The video width value is less than the permitted size: %d px', $width));
    }

    public static function heightLess(int $height): Exception
    {
        return self::exceptionResolution(sprintf('The video height value is less than the permitted size: %d px', $height));
    }

    public static function durationLarge(float $duration): Exception
    {
        return self::exceptionBitrate(sprintf('The video duration value is larger than the permitted: %d', $duration));
    }

    public static function durationLess(float $duration): Exception
    {
        return self::exceptionBitrate(sprintf('The video duration value is less than the permitted: %d', $duration));
    }

    public static function bitrateLarge(float $bitrate): Exception
    {
        return self::exceptionBitrate(sprintf('The video bitrate value is larger than the permitted: %d', $bitrate));
    }

    public static function bitrateLess(float $bitrate): Exception
    {
        return self::exceptionBitrate(sprintf('The video bitrate value is less than the permitted: %d', $bitrate));
    }

    private static function exceptionBitrate(string $message): Exception
    {
        return new Exception(Code::VIDEO_BITRATE, $message);
    }

    private static function exceptionResolution(string $message): Exception
    {
        return new Exception(Code::RESOLUTION, $message);
    }
}
