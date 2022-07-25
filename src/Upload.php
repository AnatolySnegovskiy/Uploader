<?php

namespace CarrionGrow\Uploader;

use CarrionGrow\Uploader\Collections\ConfigCollection;
use CarrionGrow\Uploader\Collections\FileCollection;
use CarrionGrow\Uploader\Entity\Configs\Config;
use CarrionGrow\Uploader\Entity\Files\File;
use CarrionGrow\Uploader\Exception\Code;
use CarrionGrow\Uploader\Exception\Exception;
use CarrionGrow\Uploader\Exception\FilesException;
use CarrionGrow\Uploader\Factories\FileFactories;
use CarrionGrow\Uploader\Utilities\UrlHelper;

class Upload
{
    /** @var array */
    private $temp;
    /** @var ConfigCollection */
    private $configs;

    public function __construct()
    {
        $this->configs = new ConfigCollection();
    }

    /**
     * @return ConfigCollection
     */
    public function getConfigs(): ConfigCollection
    {
        return $this->configs;
    }

    /**
     * @param ConfigCollection $configs
     * @return $this
     */
    public function setConfigs(ConfigCollection $configs): Upload
    {
        $this->configs = $configs;
        return $this;
    }

    /**
     * @return FileCollection
     * @throws Exception
     * @throws FilesException
     */
    public function uploadAll(): FileCollection
    {
        return $this->upload(array_merge($this->reArrayFiles(), $this->reArrayPost(), $this->reArrayGet()));
    }

    /**
     * @return FileCollection
     * @throws Exception
     * @throws FilesException
     */
    public function uploadFiles(): FileCollection
    {
        return $this->upload($this->reArrayFiles());
    }

    /**
     * @return FileCollection
     * @throws Exception
     * @throws FilesException
     */
    public function uploadPost(): FileCollection
    {
        return $this->upload($this->reArrayPost());
    }

    /**
     * @return FileCollection
     * @throws Exception
     * @throws FilesException
     */
    public function uploadGet(): FileCollection
    {
        return $this->upload($this->reArrayGet());
    }

    /**
     * @param array $listFiles
     * @return FileCollection
     * @throws Exception
     * @throws FilesException
     */
    private function upload(array $listFiles): FileCollection
    {
        $array = new FileCollection();

        foreach ($listFiles as $key => $item) {
            $config = $this->getConfig($key);

            try {
                $file = $this->doUpload($config, $item);
                $this->moveUploadedFile($file);
                $array->set($key, $file);
            } catch (Exception $exception) {
                if ($config->isSkipError()) {
                    $array->set($key, $exception);
                } else {
                    throw $exception;
                }
            }
        }

        return $array;
    }

    /**
     * @param string $key
     * @return Config
     */
    private function getConfig(string $key): Config
    {
        $key = current(explode('||', $key));
        return $this->configs->get($key) ?? $this->configs->first();
    }

    /**
     * @param Config $config
     * @param array $file
     * @return File
     * @throws Exception
     * @throws FilesException
     */
    private function doUpload(Config $config, array $file): File
    {
        if (!empty($file['error'])) {
            throw new FilesException($file['error'] ?? 4);
        }

        return (new FileFactories($this->validateUploadPath($config)))->init($file);
    }

    /**
     * @throws Exception
     */
    private function moveUploadedFile(File $file)
    {
        if (@copy($file->getTempPath(), $file->getFilePath()) === false) {
            if (@move_uploaded_file($file->getTempPath(), $file->getFilePath()) === false) {
                throw new Exception(Code::FILE_COPYING, 'A problem was encountered while attempting to move the uploaded file to the final destination');
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    private function reArrayPost(): array
    {
        return $this->linksToFilesObject($_POST);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function reArrayGet(): array
    {
        return $this->linksToFilesObject($_GET);
    }

    /**
     * @param array $linkList
     * @return array
     * @throws Exception
     */
    private function linksToFilesObject(array $linkList): array
    {
        $result = [];

        foreach ($linkList as $key => $link) {
            $name = basename($link);
            $link = UrlHelper::toUrl($link);

            if (!filter_var($link, FILTER_VALIDATE_URL)) {
                continue;
            }

            $codeError = 0;
            $path = '';
            $headers = get_headers($link, true);

            if (!isset($headers['Content-Length'])) {
                $codeError = 4;
            }

            if (empty($codeError)) {
                $this->temp[$name] = tmpfile();
                $path = stream_get_meta_data($this->temp[$name])['uri'];
                $data = @file_get_contents($link);

                if (!empty($data)) {
                    fwrite($this->temp[$name], $data);
                } else {
                    $codeError = 4;
                }
            }

            $result[$key] =
                [
                    'name' => $name,
                    'type' => $headers['Content-Type'] ?? '',
                    'tmp_name' => $path,
                    'error' => $codeError,
                    'size' => $headers['Content-Length'] ?? '',
                ];
        }

        return $result;
    }

    /**
     * @return array
     */
    private function reArrayFiles(): array
    {
        $result = [];

        foreach ($_FILES ?? [] as $postKey => $item) {
            if (!is_array($item['name'])) {
                $result[$postKey] = $item;
                continue;
            }

            $fileCount = count($item['name']);
            $fileKeys = array_keys($item);

            for ($i = 0; $i < $fileCount; $i++) {
                foreach ($fileKeys as $key) {
                    $result[implode('||', [$postKey, $i])][$key] = $item[$key][$i];
                }
            }
        }

        return $result;
    }

    /**
     * @param Config $config
     * @return Config
     * @throws Exception
     */
    private function validateUploadPath(Config $config): Config
    {
        $uploadPath = $config->getUploadPath();

        if (is_dir($uploadPath) === false) {
            mkdir($uploadPath, 0777, true);
        }

        if ($uploadPath === '') {
            throw new Exception(Code::REMOTE_URI);
        }

        if (realpath($uploadPath) !== false) {
            $uploadPath = str_replace('\\', '/', realpath($uploadPath));
        }

        if (is_dir($uploadPath) === false) {
            throw new Exception(Code::REMOTE_URI);
        }

        if ($this->isReallyWritable($uploadPath) === false) {
            throw new Exception(Code::READING_DIRECTORY);
        }

        $config->setUploadPath(preg_replace('/(.+?)\/*$/', '\\1/', $uploadPath));

        return $config;
    }

    /**
     * @param string $file
     * @return bool
     */
    private function isReallyWritable(string $file): bool
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }

        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());

            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }

            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);

            return true;
        } elseif (!is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }

        fclose($fp);

        return true;
    }
}