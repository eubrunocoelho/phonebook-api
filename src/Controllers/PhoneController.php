<?php

namespace Controllers;

use Handlers\Phone\StoreHandler as PhoneStoreHandler;
use Handlers\ValidationHandler;
use Models\Contact;
use Models\DAO\ContactDAO;
use Services\AuthorizationService;
use Sessions\Session;

class PhoneController
{
    public $connection;
    public $jsonResource;
    public $jsonRequestService;
    public $validation;
    public $user;

    public function __construct(array $dependency)
    {
        $this->connection = $dependency['lib\Connection'];
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validation = $dependency['Validations\Validation'];
        $this->user = Session::get('user');
    }

    public function store(array $params)
    {
        $contactId = (!filter_var($params['contactId'], FILTER_VALIDATE_INT) === false) ? $params['contactId'] : false;

        if (!$contactId) return $this->jsonResource->toJson(404, 'Contato inexistente.');

        $ContactDAO = new ContactDAO($this->connection);
        $Contact = new Contact();

        $Contact->setId($contactId);

        if (!$contact = $ContactDAO->getContactById($Contact)) return $this->jsonResource->toJson(404, 'Contato inexistente.');
        if (!AuthorizationService::checkOwner($this->user['id'], $contact['user_id'])) return $this->jsonResource->toJson(401, 'Você não tem permissão para executar esta ação.');

        $data['rules'] = [
            'phone_number' => [
                'required' => true,
                'regex' => '/^\([0-9]{2}\) [0-9]?[0-9]{4}-[0-9]{4}$/',
                'custom-unique' => 'phone_number|phones|contact_id|' . $contact['id']
            ],
            'description' => [
                'required' => false,
                'min' => 2,
                'max' => 255
            ]
        ];

        $data['contact_id'] = $contact['id'];
        
        $ValidationHandler = new ValidationHandler();
        $PhoneStoreHandler = new PhoneStoreHandler();

        $ValidationHandler->setSuccessor($PhoneStoreHandler);
        $ValidationHandler->handle($data, $this);
    }
}
