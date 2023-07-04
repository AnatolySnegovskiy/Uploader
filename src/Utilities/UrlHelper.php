<?php

namespace CarrionGrow\Uploader\Utilities;

use Opis\Uri\Punycode;

class UrlHelper
{
    const PATTERN_CHECK_DOMAIN =
        '/^((https?:?\/\/|ftp:\/\/|:?\/\/)?([a-z0-9\-\_\.а-я]+)?[a-z0-9\_\-а-я]+(!?\.[a-zа-я]{2,4}))\/?/ui';

    /**
     * @param string $url
     * @return string
     */
    static public function toUrl(string $url): string
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
        } catch (\Exception $e) {
            return $tempUrl;
        }

        return $url;
    }

    /**
     * @param string $url
     * @return string
     */
    static public function toPunycode(string $url): string
    {
        return Punycode::encode($url);
    }

    /**
     * @param string $url
     * @return string
     */
    static public function urlDecode(string $url): string
    {
        return rawurldecode($url);
    }

    /**
     * @param string $url
     * @return string
     * @psalm-api
     */
    static public function punycodeDecode(string $url): string
    {
        $original = $url;
        $host = self::getHost($url);

        try {
            $url = self::urlDecode(str_replace($host, Punycode::decode($host), $url));
        } catch (\Exception $e) {
            $url = self::urlDecode($url);
        }

        if (!self::isContainsDomain($url)) {
            return $original;
        } else {
            return $url;
        }
    }

    /**
     * @param string $string
     * @return false|int
     */
    public static function isContainsDomain(string $string)
    {
        return preg_match(self::PATTERN_CHECK_DOMAIN, $string);
    }

    /**
     * @param string $url
     * @return string
     */
    static private function getHost(string $url): string
    {
        if (!preg_match('/\/\//i', $url)) {
            $host = parse_url(('//' . $url), PHP_URL_HOST);
        } else {
            $host = parse_url($url, PHP_URL_HOST);
        }

        return $host;
    }
}