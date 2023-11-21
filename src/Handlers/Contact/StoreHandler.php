<?php

namespace Handlers\Contact;

use Models\{
    Contact,
    DAO\ContactDAO
};

use Handlers\Handler;

class StoreHandler extends Handler
{
    public function handle(array $data, Object $controller): Object
    {
        $ContactDAO = new ContactDAO($controller->connection);
        $Contact = new Contact();

        $write = [
            'user_id' => $controller->user['id'],
            'name' => $data['request']['name'],
            'email' => (empty($data['request']['email'])) ? null : $data['request']['email']
        ];

        $Contact->setUserId($write['user_id']);
        $Contact->setName($write['name']);
        $Contact->setEmail($write['email']);

        if ($resultId = $ContactDAO->register($Contact)) {
            $Contact->setId($resultId);

            unset($data);

            $data['contact'] = $ContactDAO->getContactById($Contact);
            $data['contact']['email'] = (!isset($data['contact']['email']) || empty($data['contact']['email'])) ? 'Não informado' : $data['contact']['email'];

            return $controller->jsonResource->toJson(201, 'Contato cadastrado com sucesso!', ['data' => $data]);
        } else return $controller->jsonResource->toJson(500, 'Houve um erro interno.');
    }
}
