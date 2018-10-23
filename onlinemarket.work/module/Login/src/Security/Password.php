<?php
namespace Login\Security;

//*** PASSWORD LAB: add the appropriate "use" statement(s)
use Zend\Crypt\Password\Bcrypt;

class Password
{
	//*** PASSWORD LAB: return a Bcrypt hash
    public static function createHash($plainText)
    {
        //*** place your code here
        return (new Bcrypt())->create($plainText);
    }
	//*** PASSWORD LAB: verify a password against a hash
    public static function verify($plainText, $hash)
    {
        //*** place your code here
        return (new Bcrypt())->verify($plainText, $hash);
    }
}
