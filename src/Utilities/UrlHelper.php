<?php

namespace CarrionGrow\Uploader\Utilities;

use Exception;
use Opis\Uri\Punycode;

class UrlHelper
{
    public const PATTERN_CHECK_DOMAIN =
        '/^((https?:?\/\/|ftp:\/\/|:?\/\/)?([a-z0-9\-_.а-я]+)?[a-z0-9_\-а-я]+(!?\.[a-zа-я]{2,4}))\/?/ui';

    public static function toUrl(string $url): string
    {
        $tempUrl = $url;
        $url = trim(strip_tags($url));

        if (empty($url)) {
            return $url;
        }

        if (!self::isContainsDomain($url)) {
            return $tempUrl;
        }

        $host = self::getHost($url);

        try {
            $url =
                str_replace(
                    ['%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D', '%7B', '%7D'],
                    ['!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]", "{", "}"],
                    rawurlencode(str_replace($host, self::toPunycode($host), $url))
                );
        } catch (Exception) {
            return $tempUrl;
        }

        return $url;
    }

    public static function toPunycode(string $url): string
    {
        return Punycode::encode($url);
    }

    public static function urlDecode(string $url): string
    {
        return rawurldecode($url);
    }

    /**
     * @psalm-api
     */
    public static function punycodeDecode(string $url): string
    {
        $original = $url;
        $host = self::getHost($url);

        try {
            $url = self::urlDecode(str_replace($host, Punycode::decode($host), $url));
        } catch (Exception) {
            $url = self::urlDecode($url);
        }

        if (!self::isContainsDomain($url)) {
            return $original;
        } else {
            return $url;
        }
    }

    public static function isContainsDomain(string $string): bool
    {
        return !empty(preg_match(self::PATTERN_CHECK_DOMAIN, $string));
    }

    private static function getHost(string $url): string
    {
        if (!preg_match('/\/\//', $url)) {
            $host = parse_url(('//' . $url), PHP_URL_HOST);
        } else {
            $host = parse_url($url, PHP_URL_HOST);
        }

        return $host;
    }
}
