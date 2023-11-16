<?php

namespace Controllers;

use lib\ConnectionFactory;
use Models\DAO\ContactDAO;
use Models\Contact;
use Resources\JsonResource;
use Services\AuthorizationService;
use Sessions\Session;

class ContactController
{
    private $jsonResource;
    private $jsonRequestService;
    private $validate;
    private $connection;
    private $user;

    public function __construct(array $dependency)
    {
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validate = $dependency['Validate\Validate'];
        $this->connection = ConnectionFactory::getConnection();
        $this->user = Session::get('user');
    }

    public function index(): void
    {
        echo 'Contacts';
    }

    public function store(): JsonResource
    {
        $ContactDAO = new ContactDAO($this->connection);
        $Contact = new Contact();

        $data = $this->jsonRequestService->getData();

        $rules = [
            'name' => [
                'required' => true,
                'min' => 3,
                'max' => 255,
                'regex' => '/^[a-zA-ZÀ-ÿ\s]+$/u'
            ],
            'email' => [
                'required' => false,
                'email' => true,
                'max' => 128,
                'contact-unique' => 'email|contacts|user_id|' . $this->user['id']
            ]
        ];

        $this->validate->validate($data, $rules);
        $_errors = $this->validate->getErrors() ?? [];

        foreach ($_errors as $key => $value) $errors[] = $value;

        if ($this->validate->passed()) {
            $data['user_id'] = $this->user['id'];
            $data['email'] = $data['email'] ?? null;

            $Contact->setUserId($data['user_id']);
            $Contact->setName($data['name']);
            $Contact->setEmail($data['email']);
            $resultId = $ContactDAO->register($Contact);

            if (!!$resultId) {
                unset($data);

                $Contact->setId($resultId);
                $data = $ContactDAO->getContactById($Contact);
                $data['email'] = (!is_null($data['email'])) ? $data['email'] : 'Não informado';

                return $this->jsonResource->toJson(201, 'Contato cadastrado com sucesso!', ['data' => $data]);
            } else
                return $this->jsonResource->toJson(500, 'Houve um erro interno.');
        } else
            return $this->jsonResource->toJson(422, 'Erro ao tentar cadastrar o contato.', ['errors' => $errors]);
    }

    public function update($params)
    {
        $contactId = (!filter_var($params['id'], FILTER_VALIDATE_INT) === false) ? $params['id'] : false;
        if (!$contactId) return $this->jsonResource->toJson(404, 'Página não encontrada.');

        $ContactDAO = new ContactDAO($this->connection);
        $Contact = new Contact();

        $Contact->setId($contactId);
        $result = $ContactDAO->getContactById($Contact);

        if (!!$result) {
            $userId = $this->user['id'];
            $ownerId = $result['user_id'];

            if (AuthorizationService::checkOwner($userId, $ownerId)) {
                $data = $this->jsonRequestService->getData();

                $rules = [
                    'name' => [
                        'required' => true,
                        'min' => 3,
                        'max' => 255,
                        'regex' => '/^[a-zA-ZÀ-ÿ\s]+$/u',
                    ],
                    'email' => [
                        'required' => false,
                        'email' => true,
                        'max' => 128,
                        'contact-unique-for-update' => 'email|contacts|user_id|' . $userId . '|id|' . $result['id']
                    ]
                ];

                $this->validate->validate($data, $rules);
                $_errors = $this->validate->getErrors() ?? [];

                foreach ($_errors as $key => $value) $errors[] = $value;
                
                if ($this->validate->passed()) {
                    $data['email'] = $data['email'] ?? null;

                    $Contact->setId($result['id']);
                    $Contact->setName($data['name']);
                    $Contact->setEmail($data['email']);

                    $result = $ContactDAO->update($Contact);

                    if ($result) {
                        return $this->jsonResource->toJson(204); // 204 (_NO_CONTENT?)
                    } else
                        return $this->jsonResource->toJson(500, 'Houve um erro interno.');
                } else
                    return $this->jsonResource->toJson(422, 'Error ao tentar atualizar o cadastro.', ['errors' => $errors]);
            } else
                return $this->jsonResource->toJson(401, 'Você não tem permissão para executar esta ação.');
        } else
            return $this->jsonResource->toJson(404, 'Contato inexistente.');
    }
}
