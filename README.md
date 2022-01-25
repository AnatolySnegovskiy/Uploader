# Uploader

An Object-Oriented library to upload files

composer require carriongrow/uploader

## Installation
```bash
$ composer require php-ffmpeg/php-ffmpeg
```

## Basic Usage

```php 
//<input type = "file" name = "test">
//<input type = "text" name = "test_text">
//<input type = "text" name = "video_test">
        
        $upload = new Upload();
        $newVideoConfig = new VideoConfig();
        $newVideoConfig->setMaxBitrate(100000);
        
        ## Default set config in collection ##
        $upload->getConfigs()->set('video_test', $newVideoConfig);
        
        ## BUILDER ##
        $upload->getConfigs() 
            ->new('test')->image() //new it's, set - input name field
            ->setUploadPath(__DIR__ . '/download') // path saved files, default: /yourProject/uploaded
            ->setFileName('newName') // the new name, if omitted, will be saved with the original name
            ->setMaxSize(1000) // max 10MB
            ->setOverwrite(true)// Overwrite file
            ->save()

            ->new('test_text')->other()
            ->setAllowedTypes('doc|txt')
            ->save();

 
        $result = $upload->uploadAll();
        $testText = $result->get('test_text');
        $textPath = $testText->getFilePath();

        foreach ($result->getImages() as $image) {
            $a[] =
                [
                    $image->getName(),
                    $image->getRawName(),
                    $image->getWidth(),
                    $image->getHeight(),
                    $image->getSize()
                ];
            $image->toArray();
        }
```
### Custom Config Class
```php

## The handler of the uploaded file ##
class HandlerCustom implements UploadHandlerInterface
{
    /** @var string */
    protected $tempPath;
    /** @var Config */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @throws Exception
     */
    public function behave(array $file)
    {
        $file['tmp_name'];
        $file['size'];
        $file['name'];
        $file['mimes'];
        // your object checks and fillings
    }
}

## CONFIG CLASS ##
class ConfigCustom extends Config
{
    /** @var string */
    private $string = '';
    /** @var string */
    private $string2 = '';

    /** @var UploadHandlerInterface */
    protected $handler;
    
    public function __construct()
    {
        $this->handler = new HandlerCustom($this);
    }
}

## USE ##
$upload = new Upload();
$customConfig = new ConfigCustom();
$upload->getConfigs()->set('custom_config', $customConfig);
$upload->uploadAll();

```
