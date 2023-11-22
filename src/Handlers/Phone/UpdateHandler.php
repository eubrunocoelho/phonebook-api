<?php

namespace Handlers\Phone;

use Models\{
    DAO\PhoneDAO,
    Phone
};

use Handlers\Handler;

class UpdateHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        $PhoneDAO = new PhoneDAO($controller->connection);
        $Phone = new Phone();

        $write = [
            'id' => $data['phone_id'],
            'phone_number' => $data['request']['phone_number'],
            'description' => (!isset($data['request']['description']) || empty($data['request']['description'])) ? null : $data['request']['description']
        ];

        $Phone->setId($write['id']);
        $Phone->setPhoneNumber($write['phone_number']);
        $Phone->setDescription($write['description']);

        if ($PhoneDAO->update($Phone)) {
            $Phone->setId($data['phone_id']);

            unset($data);

            $data['phone'] = $PhoneDAO->getPhoneById($Phone);

            if (is_null($data['phone']['description']) || empty($data['phone']['description'])) unset($data['phone']['description']);

            return $controller->jsonResource->toJson(200, 'Contato atualizado com sucesso!', ['data' => $data]);
        } else return $controller->jsonResource->toJson(500, 'Houve um erro interno.');
    }
}
