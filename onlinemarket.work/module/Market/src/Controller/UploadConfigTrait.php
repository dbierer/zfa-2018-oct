<?php
namespace Market\Controller;

trait UploadConfigTrait
{
    protected $uploadConfig;
    public function setUploadConfig($uploadConfig)
    {
        $this->uploadConfig = $uploadConfig;
    }
}
