<?php

namespace CarrionGrow\Uploader\Entity\Files;

use CarrionGrow\Uploader\Entity\Configs\VideoConfig;
use CarrionGrow\Uploader\Exception\Code;
use CarrionGrow\Uploader\Exception\Exception;
use CarrionGrow\Uploader\Exception\VideoException;
use getID3;

class Video extends File
{
    protected int $width = 0;

    protected int $height = 0;

    protected int $duration = 0;

    protected int $bitrate = 0;

    protected string $videoCodec = '';

    protected string $audioCodec = '';
    #region getter

    /**
     * @psalm-api
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @psalm-api
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @psalm-api
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @psalm-api
     */
    public function getBitrate(): float
    {
        return $this->bitrate;
    }

    /**
     * @psalm-api
     */
    public function getVideoCodec(): string
    {
        return $this->videoCodec;
    }

    /**
     * @psalm-api
     */
    public function getAudioCodec(): string
    {
        return $this->audioCodec;
    }

    #endregion

    public function __construct(VideoConfig $config)
    {
        parent::__construct($config);
    }

    /**
     * @throws Exception
     */
    public function behave(array $file): void
    {
        parent::behave($file);
        $config = $this->getConfig();
        /** @var array<string, mixed> $meta */
        $meta = (new GetID3())->analyze($this->getTempPath());
        $this->duration = $meta['playtime_seconds'] ?? 0;
        $this->bitrate = ($meta['bitrate'] ?? 0) / 1000;
        $this->videoCodec = $meta['video']['fourcc_lookup'] ?? '';
        $this->audioCodec = $meta['audio']['codec'] ?? '';
        $this->width = (int)($meta['video']['resolution_x'] ?? 0);
        $this->height = (int)($meta['video']['resolution_y'] ?? 0);

        if ($config->getMaxDuration() > 0 && $this->duration > $config->getMaxDuration()) {
            throw VideoException::durationLarge($config->getMaxWidth());
        }

        if ($config->getMinDuration() > 0 && $this->duration < $config->getMinDuration()) {
            throw VideoException::durationLess($config->getMinDuration());
        }

        if ($config->getMaxBitrate() > 0 && $this->bitrate > $config->getMaxBitrate()) {
            throw VideoException::bitrateLarge($config->getMaxBitrate());
        }

        if ($config->getMinBitrate() > 0 && $this->bitrate < $config->getMinBitrate()) {
            throw VideoException::bitrateLess($config->getMinBitrate());
        }

        if ($config->getMaxWidth() > 0 && $this->width > $config->getMaxWidth()) {
            throw VideoException::widthLarger($config->getMaxWidth());
        }

        if ($config->getMaxHeight() > 0 && $this->height > $config->getMaxHeight()) {
            throw VideoException::heightLarger($config->getMaxHeight());
        }

        if ($config->getMinWidth() > 0 && $this->width < $config->getMinWidth()) {
            throw VideoException::widthLess($config->getMinWidth());
        }

        if ($config->getMinHeight() > 0 && $this->height < $config->getMinHeight()) {
            throw VideoException::heightLess($config->getMinHeight());
        }

        $this->validateVideoCodec();
        $this->validateAudioCodec();
    }

    /**
     * @throws Exception
     */
    private function validateVideoCodec(): void
    {
        $config = $this->getConfig();
        $allowedCodec = $config->getVideoCodec();

        if ($allowedCodec === '*') {
            return;
        }

        /** @var string $item */
        foreach ((array)$allowedCodec as $item) {
            if (str_contains(strtolower($this->videoCodec), strtolower($item))) {
                return;
            }
        }

        throw new Exception(Code::VIDEO_CODEC);
    }

    /**
     * @throws Exception
     */
    private function validateAudioCodec(): void
    {
        $config = $this->getConfig();
        $allowedCodec = $config->getAudioCodec();

        if ($allowedCodec === '*') {
            return;
        }

        /** @var string $item */
        foreach ((array)$allowedCodec as $item) {
            if (str_contains(strtolower($this->audioCodec), strtolower($item))) {
                return;
            }
        }

        throw new Exception(Code::AUDIO_CODEC);
    }

    /**
     * @throws Exception
     */
    public function getConfig(): VideoConfig
    {
        if (!($this->config instanceof VideoConfig)) {
            throw new Exception(Code::ERROR_CONFIG, 'Config must be instance of ' . VideoConfig::class);
        }

        return $this->config;
    }
}
