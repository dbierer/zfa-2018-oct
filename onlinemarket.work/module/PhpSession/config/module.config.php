<?php
namespace PhpSession;

//*** SESSION LAB: you *must* specify these two keys, otherwise the module will fail to initialize
return [
	'session_config' => [
	],
	'session_storage' => [
		//*** SESSION LAB: enter the type of storage to use
		'type' => 'Zend\Session\Storage\SessionArrayStorage',
	],
];
