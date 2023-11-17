<?php

namespace Handlers\Contact;

use Models\{
    Contact,
    DAO\ContactDAO
};

use Handlers\Handler;

class StoreHandler extends Handler
{
    public function handle($data, $controller)
    {
        $ContactDAO = new ContactDAO($controller->connection);
        $Contact = new Contact();

        $write = [
            'name' => $data['request']['name'],
            'email' => (empty($data['request']['email'])) ? null : $data['request']['email']
        ];

        $Contact->setUserId($controller->user['id']);
        $Contact->setName($write['name']);
        $Contact->setEmail($write['email']);
        $resultId = $ContactDAO->register($Contact);

        if ($resultId !== false) {
            $Contact->setId($resultId);
            $data = $ContactDAO->getContactById($Contact);
            $data['email'] = (!isset($data['email']) || empty($data['email'])) ? 'Não informado' : $data['email'];

            return $controller->jsonResource->toJson(201, 'Contato cadastrado com sucesso!', ['data' => $data]);
        } else return $controller->jsonResource->toJson(500, 'Houve um erro interno.');
    }
}
