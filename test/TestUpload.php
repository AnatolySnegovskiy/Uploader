<?php


use CarrionGrow\Uploader\Entity\Configs\VideoConfig;
use CarrionGrow\Uploader\Upload;
use PHPUnit\Framework\TestCase;

class TestUpload extends TestCase
{
    private $upload;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->upload = new Upload();
    }

    public function testUploadFiles()
    {
        $this->testFileData();
        var_dump($this->upload->uploadFiles()->toArray());
    }

    /**
     * @throws \CarrionGrow\Uploader\Exception\FilesException
     * @throws \CarrionGrow\Uploader\Exception\Exception
     */
    public function testUploadPost()
    {
        $this->upload->getConfigs()->new('VIDEO')->image()->setUploadPath(__DIR__ . '/test')->save();

        $this->upload->uploadAll();

        $this->testPostData();
        $data = $this->upload->uploadAll()->toArray();
        $result = [];

        foreach ($data as $key => $datum) {
            $result[$key] = $datum->toArray();
        }

        var_dump($result);
    }

    private function testPostData()
    {
        $_POST['VIDEO'] = 'https://ft.imgsniper.com/video/dunlop/dunlop_20sec.mp4';
//        $_POST['link_jpg'] = 'https://i.ytimg.com/vi/C9D-uXKzvPY/maxresdefault.jpg';
//        $_POST['error'] = 'https://yandex.ru/search/?clid=2186621&text=php+%D0%BF%D1%88%D0%B5%D1%80%D1%84%D0%B8&lr=38&redircnt=1635444353.1';
//        $_GET['zip'] = 'https://speedtest.selectel.ru/10MB';
    }

    private function testFileData()
    {
        $_FILES = [
            "logotype" => [
                "name" => [
                    "birthday-1124.gif",
                    ""
                ],
                "type" => [
                    "image/jpeg",
                    ""
                ],
                "tmp_name" => [
                    __DIR__ . '/Files/birthday-1124.gif',
                    ""
                ],
                "error" => [
                    0,
                    4
                ],
                "size" => [
                    701123,
                    0
                ]
            ],
            "logo_medium" => [
                "name" => "",
                "type" => "",
                "tmp_name" => "",
                "error" => 4,
                "size" => 0
            ],
            "logo_small" => [
                "name" => "",
                "type" => "",
                "tmp_name" => "",
                "error" => 4,
                "size" => 0
            ],
            "logo_email" => [
                "name" => "",
                "type" => "",
                "tmp_name" => "",
                "error" => 4,
                "size" => 0
            ],
            "background" => [
                "name" => "",
                "type" => "",
                "tmp_name" => "",
                "error" => 4,
                "size" => 0
            ],
            "background_mail" => [
                "name" => "",
                "type" => "",
                "tmp_name" => "",
                "error" => 4,
                "size" => 0
            ]
        ];
    }
}
