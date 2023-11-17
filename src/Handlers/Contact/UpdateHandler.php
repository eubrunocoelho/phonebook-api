<?php

namespace Handlers\Contact;

use Models\{
    Contact,
    DAO\ContactDAO
};

use Handlers\Handler;

class UpdateHandler extends Handler
{
    public function handle($data, $controller)
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
            unset($data);

            $data = $ContactDAO->getContactById($Contact);
            $data['email'] = (!isset($data['email']) || empty($data['email'])) ? 'Não informado' : $data['email'];

            return $controller->jsonResource->toJson(200, 'Contato atualizado com sucesso!', ['data' => $data]);
        } else return $controller->jsonResource->toJson(500, 'Houve um erro inesperado.');
    }
}
