<?php 

namespace AppBundle\Service;

use AppBundle\Service\DatabaseService;
use AppBundle\Service\cacheService;

class IntegrationService {
    
	private $database;
	private $cacheService;
    
	public function __construct(DatabaseService $dbService, CacheService $cacheService) {
		$this->database = $dbService->getAll("customers");
		$this->cacheService = $cacheService;
	}
    
	public function add($customer) {
		$this->database->insert($customer);
		$this->cacheService->set("customers",$customer);
	}
    
	public function del() {
		$this->cacheService->del("customers");
		$this->database->drop();
	}
    
	public function getAll() {
		
		$customers = $this->cacheService->get("customers");
		if(empty($customers)) {
			$customers = iterator_to_array($this->database->find());
			$this->cacheService->set("customers",$customers);
		}
		return $customers;
	}
}