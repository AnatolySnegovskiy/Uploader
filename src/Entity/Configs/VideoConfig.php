<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Entity\Files\Video;

class VideoConfig extends ImageConfig
{
    /** @var int */
    private $minBitrate = 0;
    /** @var int */
    private $maxBitrate = 0;
    /** @var int */
    private $maxDuration = 0;
    /** @var int */
    private $minDuration = 0;
    /** @var array|string */
    private $videoCodec = '*';
    /** @var array|string */
    private $audioCodec = '*';

    public function __construct()
    {
        parent::__construct();
        $this->handler = new Video($this);
    }
    /**
     * @return int
     * @psalm-api
     */
    public function getMinBitrate(): int
    {
        return $this->minBitrate;
    }

    /**
     * @param int $minBitrate
     * @return VideoConfig
     * @psalm-api
     */
    public function setMinBitrate(int $minBitrate): self
    {
        $this->minBitrate = $minBitrate;
        return $this;
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getMaxBitrate(): int
    {
        return $this->maxBitrate;
    }

    /**
     * @param int $maxBitrate
     * @return VideoConfig
     * @psalm-api
     */
    public function setMaxBitrate(int $maxBitrate): self
    {
        $this->maxBitrate = $maxBitrate;
        return $this;
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getMaxDuration(): int
    {
        return $this->maxDuration;
    }

    /**
     * @param int $maxDuration
     * @return VideoConfig
     * @psalm-api
     */
    public function setMaxDuration(int $maxDuration): self
    {
        $this->maxDuration = $maxDuration;
        return $this;
    }

    /**
     * @return int
     * @psalm-api
     */
    public function getMinDuration(): int
    {
        return $this->minDuration;
    }

    /**
     * @param int $minDuration
     * @return VideoConfig
     * @psalm-api
     */
    public function setMinDuration(int $minDuration): self
    {
        $this->minDuration = $minDuration;
        return $this;
    }

    /**
     * @return array|string
     * @psalm-api
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * @param array|string $videoCodec
     * @return VideoConfig
     * @psalm-api
     */
    public function setVideoCodec($videoCodec): self
    {
        $this->videoCodec =
            (is_array($videoCodec) or $videoCodec === '*')
                ? $videoCodec
                : explode('|', $videoCodec);
        return $this;
    }

    /**
     * @return array|string
     * @psalm-api
     */
    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    /**
     * @param array|string $audioCodec
     * @return VideoConfig
     * @psalm-api
     */
    public function setAudioCodec($audioCodec): self
    {
        $this->audioCodec =
            (is_array($audioCodec) or $audioCodec === '*')
                ? $audioCodec
                : explode('|', $audioCodec);

        return $this;
    }
}