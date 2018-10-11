<?php
namespace PrivateMessages\Model;
class Message
{

    protected $id;
    protected $toEmail;
    protected $fromEmail;
    protected $message;
    protected $dateTime;
 
    public function getId()
    {
		return $this->id;
	}
    public function getToEmail()
    {
		return $this->toEmail;
	}
    public function getFromEmail()
    {
		return $this->fromEmail;
	}
    public function getMessage()
    {
		return $this->message;
	}
    public function getDateTime()
    {
		return $this->dateTime;
	}

    public function setId($val)
    {
		$this->id = $val;
	}
    public function setToEmail($val)
    {
		$this->toEmail = $val;
	}
    public function setFromEmail($val)
    {
		$this->fromEmail = $val;
	}
    public function setMessage($val)
    {
		$this->message = $val;
	}
    public function setDateTime($val)
    {
		$this->dateTime = $val;
	}

}
