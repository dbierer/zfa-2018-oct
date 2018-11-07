<?php
namespace RestApi\Controller;

use RestApi\Service\ApiService;
use Zend\Http\Response;
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
    public function get($id)
    {
        $result = $this->service->fetchById($id);
        $status = ($result['success'] == 1) ? Response::STATUS_CODE_200 : Response::STATUS_CODE_404;
        $this->getResponse()->setStatusCode($status);
        return new JsonModel($result);
    }
    //*** RESTAPI LAB: define a method which returns all listings
    //***              if there is an error set the status code on the response to 500
    public function getList()
    {
        $result = $this->service->fetchAll();
        $status = ($result['success'] == 1) ? Response::STATUS_CODE_200 : Response::STATUS_CODE_404;
        $this->getResponse()->setStatusCode($status);
        return new JsonModel($result);
    }
}
