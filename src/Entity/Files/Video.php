<?php

namespace CarrionGrow\Uploader\Entity\Files;

use CarrionGrow\Uploader\Entity\Configs\VideoConfig;
use CarrionGrow\Uploader\Exception\Code;
use CarrionGrow\Uploader\Exception\Exception;
use CarrionGrow\Uploader\Exception\VideoException;
use getID3;

class Video extends File
{
    /** @var int  */
    protected $width;
    /** @var int  */
    protected $height;
    /** @var string  */
    protected $duration;
    /** @var string  */
    protected $bitrate;
    /** @var string */
    protected $videoCodec;
    /** @var string */
    protected $audioCodec;
    /** @var VideoConfig */
    protected $config;

#region getter
    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * @return string
     */
    public function getVideoCodec(): string
    {
        return $this->videoCodec;
    }

    /**
     * @return string
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

    public function behave(array $file)
    {
        parent::behave($file);

        $meta = (new GetID3())->analyze($this->getTempPath());

        $this->duration = round($meta['playtime_seconds'] ?? 0);
        $this->bitrate = round(($meta['bitrate'] ?? 0) / 1000);
        $this->videoCodec = $meta['video']['fourcc_lookup'] ?? '';
        $this->audioCodec = $meta['audio']['codec'] ?? '';
        $this->width = (int) ($meta['video']['resolution_x'] ?? 0);
        $this->height = (int) ($meta['video']['resolution_y'] ?? 0);

        if ($this->config->getMaxDuration() > 0 && $this->duration > $this->config->getMaxDuration())
            throw VideoException::durationLarge($this->config->getMaxWidth());

        if ($this->config->getMinDuration() > 0 && $this->duration < $this->config->getMinDuration())
            throw VideoException::durationLess($this->config->getMinDuration());

        if ($this->config->getMaxBitrate() > 0 && $this->bitrate > $this->config->getMaxBitrate())
            throw VideoException::bitrateLarge($this->config->getMaxBitrate());

        if ($this->config->getMinBitrate() > 0 && $this->bitrate < $this->config->getMinBitrate())
            throw VideoException::bitrateLess($this->config->getMinBitrate());

        if ($this->config->getMaxWidth() > 0 && $this->width > $this->config->getMaxWidth())
            throw VideoException::widthLarger($this->config->getMaxWidth());

        if ($this->config->getMaxHeight() > 0 && $this->width > $this->config->getMaxHeight())
            throw VideoException::heightLarger($this->config->getMaxHeight());

        if ($this->config->getMinWidth() > 0 && $this->width < $this->config->getMinWidth())
            throw VideoException::widthLess($this->config->getMinWidth());

        if ($this->config->getMinHeight() > 0 && $this->width < $this->config->getMinHeight())
            throw VideoException::heightLess($this->config->getMinHeight());

        $this->validateVideoCodec();
        $this->validateAudioCodec();
    }

    /**
     * @throws Exception
     */
    private function validateVideoCodec()
    {
        $allowedCodec = $this->config->getVideoCodec();

        if ($allowedCodec === '*') {
            return;
        }

        foreach ($allowedCodec as $item) {
            if (strpos(strtolower($this->videoCodec), strtolower($item)) !== false) {
                return;
            }
        }

        throw new Exception(Code::VIDEO_CODEC);
    }

    /**
     * @throws Exception
     */
    private function validateAudioCodec()
    {
        $allowedCodec = $this->config->getAudioCodec();

        if ($allowedCodec === '*') {
            return;
        }

        foreach ($allowedCodec as $item) {
            if (strpos(strtolower($this->audioCodec), strtolower($item)) !== false) {
                return;
            }
        }

        throw new Exception(Code::AUDIO_CODEC);
    }
}