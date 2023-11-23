<?php

namespace Controllers;

use Models\{
    Contact,
    DAO\ContactDAO,
    DAO\PhoneDAO,
    Phone
};

use Handlers\{
    Contact\StoreHandler as ContactStoreHandler,
    Contact\UpdateHandler as ContactUpdateHandler,
    ValidationHandler
};

use Resources\JsonResource;
use Services\AuthorizationService;
use Sessions\Session;

class ContactController
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

    public function index(): JsonResource
    {
        $ContactDAO = new ContactDAO($this->connection);
        $Contact = new Contact();

        $Contact->setUserId($this->user['id']);

        $data['contacts'] = $ContactDAO->getContactsByUserId($Contact);

        if (!$data['contacts']) return $this->jsonResource->toJson(404, 'Você não possui contatos registrado.');

        foreach ($data['contacts'] as $key => $value) {
            if (is_null($data['contacts'][$key]['email']) || empty($data['contacts'][$key]['email'])) unset($data['contacts'][$key]['email']);
        }

        return $this->jsonResource->toJson(200, extra: ['data' => $data]);
    }

    public function show(array $params): JsonResource
    {
        $contactId = (!filter_var($params['id'], FILTER_VALIDATE_INT) === false) ? $params['id'] : false;

        if (!$contactId) return $this->jsonResource->toJson(404, 'Contato inexistente.');

        $ContactDAO = new ContactDAO($this->connection);
        $Contact = new Contact();

        $Contact->setId($contactId);

        if (!$data['contact'] = $ContactDAO->getContactById($Contact)) return $this->jsonResource->toJson(404, 'Contato inexistente.');

        if (!AuthorizationService::checkOwner($this->user['id'], $data['contact']['user_id'])) return $this->jsonResource->toJson(401, 'Você não tem permissão para executar esta ação.');

        if (is_null($data['contact']['email']) || empty($data['contact']['email'])) unset($data['contact']['email']);

        $PhoneDAO = new PhoneDAO($this->connection);
        $Phone = new Phone();

        $Phone->setContactId($data['contact']['id']);

        $phones = $PhoneDAO->getPhonesByContactId($Phone);

        if ($phones !== false && is_array($phones)) foreach ($phones as $key => $value) {
            if (is_null($phones[$key]['description']) || empty($phones[$key]['description'])) unset($phones[$key]['description']);

            $data['contact']['phones'] = $phones;
        }

        return $this->jsonResource->toJson(200, extra: ['data' => $data]);
    }

    public function store(): void
    {
        $data['rules'] = [
            'name' => [
                'required' => true,
                'min' => 3,
                'max' => 255,
                'regex' => '/^[a-zA-ZÀ-ÿ\s]+$/u',
                'custom-unique' => 'name|contacts|user_id|' . $this->user['id']
            ],
            'email' => [
                'required' => false,
                'email' => true,
                'max' => 128,
                'custom-unique' => 'email|contacts|user_id|' . $this->user['id']
            ]
        ];

        $ValidationHandler = new ValidationHandler();
        $ContactStoreHandler = new ContactStoreHandler();

        $ValidationHandler->setSuccessor($ContactStoreHandler);
        $ValidationHandler->handle($data, $this);
    }

    public function update(array $params): JsonResource
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
                'custom-unique-for-update' => 'name|contacts|user_id|' . $this->user['id'] . '|id|' . $contact['id']
            ],
            'email' => [
                'required' => false,
                'email' => true,
                'max' => 128,
                'custom-unique-for-update' => 'email|contacts|user_id|' . $this->user['id'] . '|id|' . $contact['id']
            ]
        ];

        $data['contact_id'] = $contact['id'];

        $ValidationHandler = new ValidationHandler();
        $ConactUpdateHandler = new ContactUpdateHandler();

        $ValidationHandler->setSuccessor($ConactUpdateHandler);
        $ValidationHandler->handle($data, $this);
    }

    public function delete(array $params)
    {
        $contactId = (!filter_var($params['id'], FILTER_VALIDATE_INT) === false) ? $params['id'] : false;

        if (!$contactId) return $this->jsonResource->toJson(404, 'Contato inexistente.');

        $ContactDAO = new ContactDAO($this->connection);
        $Contact = new Contact();

        $Contact->setId($contactId);

        if (!$contact = $ContactDAO->getContactById($Contact)) return $this->jsonResource->toJson(404, 'Contato inexistente.');

        if (!AuthorizationService::checkOwner($this->user['id'], $contact['user_id'])) return $this->jsonResource->toJson(401, 'Você não tem permissão para executar esta ação.');

        $PhoneDAO = new PhoneDAO($this->connection);
        $Phone = new Phone();

        $Phone->setContactId($contact['id']);

        if (!$PhoneDAO->deletePhonesByContactId($Phone)) return $this->jsonResource->toJson(500, 'Houve um erro interno.');

        if ($ContactDAO->deleteContactById($Contact)) return $this->jsonResource->toJson(204);
        else return $this->jsonResource->toJson(500, 'Houve um erro interno.');
    }
}
