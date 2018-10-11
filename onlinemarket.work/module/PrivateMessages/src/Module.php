<?php
namespace PrivateMessages;

class Module
{

    const ERROR_OPENSSL = 'ERROR: the OpenSSL extension is not available on this server';
    const ERROR_ALGO    = 'ERROR: none of the preferred algorithms or modes are supported on this server';

    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

}
