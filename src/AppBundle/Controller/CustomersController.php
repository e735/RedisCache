<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Service\IntegrationService;

/**
 * @Route(service="customers_controller")
 */
class CustomersController extends Controller
{
    
    private $integrationService;
    
    /**
     * Constructor Method
     */
    public function __construct(IntegrationService $integrationService) {
        $this->integrationService = $integrationService;    
    }
    
    /**
     * @Route("/customers/")
     * @Method("GET")
     */
    public function getAction() {
        $customers = $this->integrationService->getAll();
        return new JsonResponse($customers);
    }

    /**
     * @Route("/customers/")
     * @Method("POST")
     */
    public function postAction(Request $request) {
        $customers = json_decode($request->getContent());
        if (empty($customers)) {
            return new JsonResponse(['status' => 'No donuts for you'], 400);
        }
        foreach ($customers as $customer) {
            $this->integrationService->add($customer);
        }
        return new JsonResponse(['status' => 'Customers successfully created']);
    }

    /**
     * @Route("/customers/")
     * @Method("DELETE")
     */
    public function deleteAction()
    {
        $database = $this->get('database_service')->getDatabase();
        $database->customers->drop();

        return new JsonResponse(['status' => 'Customers successfully deleted']);
    }
}
