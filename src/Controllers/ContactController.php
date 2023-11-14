<?php

namespace Controllers;

use lib\ConnectionFactory;
use Models\DAO\ContactDAO;
use Models\Contact;
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

    public function store()
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
                'unique' => 'email|contacts'
            ]
        ];

        $this->validate->validate($data, $rules);
        $_errors = $this->validate->getErrors() ?? [];

        foreach ($_errors as $key => $value) $errors[] = $value;

        if ($this->validate->passed()) {
            $data['user_id'] = $this->user['id'];
            $data['email'] = $data['email'] ?? '';

            $Contact->setUserId($data['user_id']);
            $Contact->setName($data['name']);
            $Contact->setEmail($data['email']);
            $result = $ContactDAO->register($Contact);

            //
            if (!!$result) {
                $Contact->setId($result);
                
                dd($ContactDAO->getContactById($Contact));
            } // finished system for contact registration
        } else
            return $this->jsonResource->toJson(422, 'Erro ao tentar cadastrar o contato.', ['errors' => $errors]);
    }
}
