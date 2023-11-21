<?php

namespace Controllers;

use Models\{
    Contact,
    DAO\ContactDAO
};

use Handlers\{
    Contact\StoreHandler as ContactStoreHandler,
    Contact\UpdateHandler as ContactUpdateHandler,
    ValidationHandler
};

use Exceptions\CustomException;
use Services\AuthorizationService;
use Sessions\Session;

class ContactController
{
    public $connection;
    public $jsonResource;
    public $jsonRequestService;
    public $validate;
    public $user;

    public function __construct(array $dependency)
    {
        $this->connection = $dependency['lib\Connection'];
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validate = $dependency['Validate\Validate'];
        $this->user = Session::get('user');
    }

    public function index()
    {
        echo 'Contacts';
    }

    public function store()
    {
        $data['rules'] = [
            'name' => [
                'required' => true,
                'min' => 3,
                'max' => 255,
                'regex' => '/^[a-zA-ZÀ-ÿ\s]+$/u',
                'contact-unique' => 'name|contacts|user_id|' . $this->user['id']
            ],
            'email' => [
                'required' => false,
                'email' => true,
                'max' => 128,
                'contact-unique' => 'email|contacts|user_id|' . $this->user['id']
            ]
        ];

        $ValidationHandler = new ValidationHandler();
        $ContactStoreHandler = new ContactStoreHandler();

        $ValidationHandler->setSuccessor($ContactStoreHandler);
        $ValidationHandler->handle($data, $this);
    }

    public function update($params)
    {
        $contactId = (!filter_var($params['id'], FILTER_VALIDATE_INT) === false) ? $params['id'] : false;

        if (!$contactId) return $this->jsonResource->toJson(404, 'Contato inexistente.');

        $ContactDAO = new ContactDAO($this->connection);

        $Contact = new Contact();
        $Contact->setId($contactId);

        if (!$contact = $ContactDAO->getContactById($Contact)) return $this->jsonResource->toJson(404, 'Contato inexistente.');
        if (!AuthorizationService::checkOwner($this->user['id'], $contact['user_id'])) return $this->jsonResource->toJson(401, 'Você não tem permissão para executar esta ação.');

        $data['rules'] = [
            'name' => [
                'required' => true,
                'min' => 3,
                'max' => 255,
                'regex' => '/^[a-zA-ZÀ-ÿ\s]+$/u',
                'contact-unique-for-update' => 'name|contacts|user_id|' . $this->user['id'] . '|id|' . $contact['id']
            ],
            'email' => [
                'required' => false,
                'email' => true,
                'max' => 128,
                'contact-unique-for-update' => 'email|contacts|user_id|' . $this->user['id'] . '|id|' . $contact['id']
            ]
        ];

        $data['contact_id'] = $contact['id'];

        $ValidationHandler = new ValidationHandler();
        $ConactUpdateHandler = new ContactUpdateHandler();

        $ValidationHandler->setSuccessor($ConactUpdateHandler);
        $ValidationHandler->handle($data, $this);
    }
}
