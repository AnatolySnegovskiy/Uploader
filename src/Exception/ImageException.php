<?php

namespace CarrionGrow\Uploader\Exception;

class ImageException
{
    public static function widthLarger(int $width): Exception
    {
        return self::exception(sprintf('The image width value is larger than the permitted size: %d px', $width));
    }

    public static function heightLarger(int $height): Exception
    {
        return self::exception(sprintf('The image height value is larger than the permitted size: %d px', $height));
    }

    public static function widthLess(int $width): Exception
    {
        return self::exception(sprintf('The image width value is less than the permitted size: %d px', $width));
    }

    public static function heightLess(int $height): Exception
    {
        return self::exception(sprintf('The image height value is less than the permitted size: %d px', $height));
    }

    private static function exception(string $message): Exception
    {
        return new Exception(Code::RESOLUTION, $message);
    }
}
