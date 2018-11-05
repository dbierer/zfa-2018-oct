<?php
namespace Notification\Traits;

trait NotificationConfigTrait
{
    protected $notificationConfig;
    public function setNotificationConfig($config)
    {
        $this->notificationConfig = $config;
    }
}
