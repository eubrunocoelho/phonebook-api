<?php

namespace Handlers\Contact;

use Models\{
    Contact,
    DAO\ContactDAO
};

use Handlers\Handler;

class UpdateHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        $ContactDAO = new ContactDAO($controller->connection);
        $Contact = new Contact();

        $write = [
            'id' => $data['contact_id'],
            'name' => $data['request']['name'],
            'email' => (!isset($data['request']['email']) || empty($data['request']['email'])) ? null : $data['request']['email']
        ];

        $Contact->setId($write['id']);
        $Contact->setName($write['name']);
        $Contact->setEmail($write['email']);

        if ($ContactDAO->update($Contact)) {
            $Contact->setId($data['contact_id']);

            unset($data);

            $data['contact'] = $ContactDAO->getContactById($Contact);

            if (is_null($data['contact']['email']) || empty($data['contact']['email'])) unset($data['contact']['email']);

            return $controller->jsonResource->toJson(200, 'Contato atualizado com sucesso!', ['data' => $data]);
        } else return $controller->jsonResource->toJson(500, 'Houve um erro inesperado.');
    }
}
