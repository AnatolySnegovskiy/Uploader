<?php

namespace CarrionGrow\Uploader\Entity\Configs;

use CarrionGrow\Uploader\Entity\Files\Video;

class VideoConfig extends ImageConfig
{
    private int $minBitrate = 0;

    private int $maxBitrate = 0;

    private int $maxDuration = 0;

    private int $minDuration = 0;

    private string|array $videoCodec = '*';

    private string|array $audioCodec = '*';

    public function __construct()
    {
        parent::__construct();
        $this->handler = new Video($this);
    }

    /**
     * @psalm-api
     */
    public function getMinBitrate(): int
    {
        return $this->minBitrate;
    }

    /**
     * @psalm-api
     */
    public function setMinBitrate(int $minBitrate): self
    {
        $this->minBitrate = $minBitrate;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMaxBitrate(): int
    {
        return $this->maxBitrate;
    }

    /**
     * @psalm-api
     */
    public function setMaxBitrate(int $maxBitrate): self
    {
        $this->maxBitrate = $maxBitrate;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMaxDuration(): int
    {
        return $this->maxDuration;
    }

    /**
     * @psalm-api
     */
    public function setMaxDuration(int $maxDuration): self
    {
        $this->maxDuration = $maxDuration;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getMinDuration(): int
    {
        return $this->minDuration;
    }

    /**
     * @psalm-api
     */
    public function setMinDuration(int $minDuration): self
    {
        $this->minDuration = $minDuration;
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getVideoCodec(): array|string
    {
        return $this->videoCodec;
    }

    /**
     * @psalm-api
     */
    public function setVideoCodec(array|string $videoCodec): self
    {
        $this->videoCodec =
            (is_array($videoCodec) or $videoCodec === '*')
                ? $videoCodec
                : explode('|', $videoCodec);
        return $this;
    }

    /**
     * @psalm-api
     */
    public function getAudioCodec(): array|string
    {
        return $this->audioCodec;
    }

    /**
     * @psalm-api
     */
    public function setAudioCodec(array|string $audioCodec): self
    {
        $this->audioCodec =
            (is_array($audioCodec) or $audioCodec === '*')
                ? $audioCodec
                : explode('|', $audioCodec);

        return $this;
    }
}
