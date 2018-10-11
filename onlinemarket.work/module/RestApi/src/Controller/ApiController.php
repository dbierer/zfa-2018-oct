<?php
namespace RestApi\Controller;

use RestApi\Service\ApiService;
use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\AbstractRestfulController;

class ApiController extends AbstractRestfulController
{
    protected $service;
    public function __construct(ApiService $service)
    {
        $this->service = $service;
    }
    //*** RESTAPI LAB: define a method which returns a single listing based on its ID
    //***              if the ID is not found, set the status code on the response to 404
    //***              otherwise, set the response status code to 200
    //*** RESTAPI LAB: define a method which returns all listings
    //***              if there is an error set the status code on the response to 500
}
