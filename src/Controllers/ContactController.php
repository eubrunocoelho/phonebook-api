<?php

namespace Controllers;

use Exceptions\CustomException;
use Handlers\Contact\StoreHandler;
use Handlers\Contact\UpdateHandler;
use Handlers\ValidationHandler;
use lib\ConnectionFactory;
use Models\DAO\ContactDAO;
use Models\Contact;
use Resources\JsonResource;
use Services\AuthorizationService;
use Sessions\Session;

class ContactController
{
    public $jsonResource;
    public $jsonRequestService;
    public $validate;
    public $connection;
    public $user;
    public $validationHandler;

    public function __construct(array $dependency)
    {
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validate = $dependency['Validate\Validate'];
        $this->validationHandler = $dependency['Handlers\ValidationHandler'];
        $this->connection = ConnectionFactory::getConnection();
        $this->user = Session::get('user');
    }

    public function index(): void
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

        $validationHandler = new ValidationHandler();
        $storeHandler = new StoreHandler();

        $validationHandler->setSuccessor($storeHandler);
        $validationHandler->handle($data, $this);
    }

    public function update($params)
    {
        $contactId = (!filter_var($params['id'], FILTER_VALIDATE_INT) === false) ? $params['id'] : false;

        if (!$contactId) throw new CustomException('Contato inexistente.', 404);

        $ContactDAO = new ContactDAO($this->connection);

        $Contact = new Contact();
        $Contact->setId($contactId);

        if (!$contact = $ContactDAO->getContactById($Contact)) throw new CustomException('Contato inexistente.', 404);

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

        $validationHandler = new ValidationHandler();
        $updateHandler = new UpdateHandler();

        $validationHandler->setSuccessor($updateHandler);
        $validationHandler->handle($data, $this);
        // ...
    }

    // public function update($params)
    // {
    //     $contactId = (!filter_var($params['id'], FILTER_VALIDATE_INT) === false) ? $params['id'] : false;
    //     if (!$contactId) return $this->jsonResource->toJson(404, 'Página não encontrada.');

    //     $ContactDAO = new ContactDAO($this->connection);
    //     $Contact = new Contact();

    //     $Contact->setId($contactId);
    //     $result = $ContactDAO->getContactById($Contact);

    //     if (!!$result) {
    //         $userId = $this->user['id'];
    //         $ownerId = $result['user_id'];

    //         if (AuthorizationService::checkOwner($userId, $ownerId)) {
    //             $data = $this->jsonRequestService->getData();

    //             $rules = [
    //                 'name' => [
    //                     'required' => true,
    //                     'min' => 3,
    //                     'max' => 255,
    //                     'regex' => '/^[a-zA-ZÀ-ÿ\s]+$/u',
    //                 ],
    //                 'email' => [
    //                     'required' => false,
    //                     'email' => true,
    //                     'max' => 128,
    //                     'contact-unique-for-update' => 'email|contacts|user_id|' . $userId . '|id|' . $result['id']
    //                 ]
    //             ];

    //             $this->validate->validate($data, $rules);
    //             $_errors = $this->validate->getErrors() ?? [];

    //             foreach ($_errors as $key => $value) $errors[] = $value;

    //             if ($this->validate->passed()) {
    //                 $data['email'] = $data['email'] ?? null;

    //                 $Contact->setId($result['id']);
    //                 $Contact->setName($data['name']);
    //                 $Contact->setEmail($data['email']);

    //                 $result = $ContactDAO->update($Contact);
    //                 unset($data);
    //                 if (!!$data = $ContactDAO->getContactById($Contact)) {
    //                     $data['email'] = (empty($data['email']) || is_null($data['email'])) ? 'Não informado' : $data['email'];

    //                     return $this->jsonResource->toJson(200, 'Contato atualizado com sucesso!', ['data' => $data]);
    //                 } else
    //                     return $this->jsonResource->toJson(500, 'Houve um erro interno.');
    //             } else
    //                 return $this->jsonResource->toJson(422, 'Error ao tentar atualizar o cadastro.', ['errors' => $errors]);
    //         } else
    //             return $this->jsonResource->toJson(401, 'Você não tem permissão para executar esta ação.');
    //     } else
    //         return $this->jsonResource->toJson(404, 'Contato inexistente.');
    // }
}
