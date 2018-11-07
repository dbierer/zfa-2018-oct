<?php
namespace RestApi\Service;

use Model\Table\ListingsTable;
class ApiService
{

	const ERROR_NO_ID = 'ERROR: no ID provided';
	const ERROR_UNABLE = 'ERROR: unable to provide listings';
    use TableTrait;

	//*** RESTAPI LAB: define a method which uses Model\Table\ListingsTable to return all listings in the form of an array
    public function fetchAll()
    {
		//*** your code goes here
		//*** if there is an error, return an array which includes an error code of 2 and message == ERROR_UNABLE
        $listing = $this->table->select();
        if ($listing) {
            $data = [];
            $hydrator = $this->table->getResultSetPrototype()->getHydrator();
            foreach ($listing as $entity) $data[] = $hydrator->extract($entity);
            $result = ['success' => 1, 'data' => $data];
        } else {
            $result = ['success' => 0, 'error' => 1, 'message' => self::ERROR_UNABLE];
        }
        return $result;
    }
	//*** RESTAPI LAB: define a method which uses Model\Table\ListingsTable to return a single listing in the form of an array
    public function fetchById($id)
    {
		//*** your code goes here
		//*** if the ID is not found, return an array which includes an error code of 1 and message == ERROR_NO_ID
        $listing = $this->table->findById($id);
        if ($listing) {
            $result = ['success' => 1, 'data' => $this->table->getResultSetPrototype()->getHydrator()->extract($listing)];
        } else {
            $result = ['success' => 0, 'error' => 1, 'message' => self::ERROR_NO_ID];
        }
        return $result;
	}
}
