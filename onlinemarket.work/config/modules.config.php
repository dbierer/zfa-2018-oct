<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Zend\Session',
    'Zend\Cache',
    'Zend\Form',
    'Zend\InputFilter',
    'Zend\Filter',
    'Zend\Paginator',
    'Zend\Hydrator',
    'Zend\I18n',
    'Zend\Db',
    'Zend\Log',
    'Zend\Router',
    'Zend\Validator',
    'Zend\Mvc\Plugin\FlashMessenger',
    'Application',
    'Market',
    'Model',
    //*** DATABASE LABS
    'Events',
    //*** DELEGATORS LAB
    'Registration',
    'SecurePost',   // disable this and CSRF element disappears from the form
    //*** CACHE LAB
    'Cache',
    //*** SESSION LAB
    'PhpSession',
    //*** LOGGER LAB
    //'Logging'
    //*** EMAIL LAB
    //'Zend\Mail',
    //'Notification',
    //*** AUTHENTICATION LAB
    //'Login',
    //*** ACL Lab
    //'AccessControl',
    //'PrivateMessages',
    //*** NAVIGATION LAB
    //'Zend\Navigation',
    //*** REST LAB
    //'RestApi',
];
