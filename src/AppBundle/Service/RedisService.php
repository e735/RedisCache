<?php
namespace AppBundle\Service;

use Predis;
use Predis\Connection\ConnectionException;


class RedisService implements CacheService {
    
    private $customer;
    
    public function __construct($host, $port, $prefix) {
        $this->customer = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
    }
    
    public function isOK() {
        $avaliable = null;
        try {
            $avaliable = $this->customer->ping();
        }catch(ConnectionException $e) {
            $avaliable = false;
        }
        return $avaliable;
    }
    
    public function get($key) {
        $customers = null;
        if($this->isOK()) {
            $customers = $this->customer->get($key); 
        }
        return unserialize($customers);
    }
    
    public function set($key,$customer) {
        if($this->isOK()) {
            $customers = $this->get($key);
            if(!$customers) {
                $customers = [];
            }
            if(!empty($customer)) {
                array_push($customers,$customer);
            }
            $this->customer->set($key,serialize($customers));
        }
    }
    
    public function del($key) {  
        $this->customer->del($key);
    }
    
} // END Class