<?php

namespace Controllers;

use Models\{
    Contact,
    DAO\ContactDAO,
    DAO\PhoneDAO,
    Phone
};

use Handlers\{
    Phone\StoreHandler as PhoneStoreHandler,
    Phone\UpdateHandler as PhoneUpdateHandler,
    ValidationHandler
};

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

    public function store(array $params): mixed
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

    public function update(array $params): mixed
    {
        $phoneId = (!filter_var($params['id'], FILTER_VALIDATE_INT) === false) ? $params['id'] : false;

        if (!$phoneId) return $this->jsonResource->toJson(404, 'Telefone inexistente.');

        $PhoneDAO = new PhoneDAO($this->connection);
        $Phone = new Phone();

        $Phone->setId($phoneId);

        if (!$phone = $PhoneDAO->getPhoneById($Phone)) return $this->jsonResource->toJson(404, 'Telefone inexistente.');

        $ContactDAO = new ContactDAO($this->connection);
        $Contact = new Contact();

        $Contact->setId($phone['contact_id']);

        if (!$contact = $ContactDAO->getContactById($Contact)) return $this->jsonResource->toJson(404, 'Contato inexistente.');

        if (!AuthorizationService::checkOwner($this->user['id'], $contact['id'])) return $this->jsonResource->toJson(401, 'Você não tem permissão para executar esta ação.');

        $data['rules'] = [
            'phone_number' => [
                'required' => true,
                'regex' => '/^\([0-9]{2}\) [0-9]?[0-9]{4}-[0-9]{4}$/',
                'custom-unique-for-update' => 'phone_number|phones|contact_id|' . $contact['id'] . '|id|' . $phone['id']
            ],
            'description' => [
                'required' => false,
                'min' => 2,
                'max' => 255
            ]
        ];

        $data['phone_id'] = $phone['id'];

        $ValidationHandler = new ValidationHandler();
        $PhoneUpdateHandler = new PhoneUpdateHandler();

        $ValidationHandler->setSuccessor($PhoneUpdateHandler);
        $ValidationHandler->handle($data, $this);
    }
}
