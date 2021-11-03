<?php

namespace CarrionGrow\Uploader\Exception;

class ImageException
{

    static public function widthLarger(int $width): Exception
    {
        return self::exception(sprintf('The image width value is larger than the permitted size: %d px', $width));
    }

    static public function heightLarger(int $height): Exception
    {
        return self::exception(sprintf('The image height value is larger than the permitted size: %d px', $height));
    }

    static public function widthLess(int $width): Exception
    {
        return self::exception(sprintf('The image width value is less than the permitted size: %d px', $width));
    }

    static public function heightLess(int $height): Exception
    {
        return self::exception(sprintf('The image height value is less than the permitted size: %d px', $height));
    }

    static private function exception($message): Exception
    {
        return new Exception(Code::RESOLUTION, $message);
    }
}