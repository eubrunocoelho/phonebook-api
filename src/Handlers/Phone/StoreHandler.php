<?php

namespace Handlers\Phone;

use Handlers\Handler;
use Models\DAO\PhoneDAO;
use Models\Phone;

class StoreHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        $PhoneDAO = new PhoneDAO($controller->connection);
        $Phone = new Phone();

        $write = [
            'contact_id' => $data['contact_id'],
            'phone_number' => $data['request']['phone_number'],
            'description' => (!isset($data['request']['description']) || empty($data['request']['description'])) ? null : $data['request']['description']
        ];
        
        $Phone->setContactId($write['contact_id']);
        $Phone->setPhoneNumber($write['phone_number']);
        $Phone->setDescription($write['description']);
        
        if ($resultId = $PhoneDAO->register($Phone)) {
            $Phone->setId($resultId);

            unset($data);

            $data['phone'] = $PhoneDAO->getPhoneById($Phone);

            if (is_null($data['phone']['description']) || empty($data['phone']['description'])) unset($data['phone']['description']);

            return $controller->jsonResource->toJson(201, 'Telefone adicionado com sucesso!', ['data' => $data]);
        } else return $controller->jsonResource->toJson(500, 'Houve um erro inesperado.');
    }
}
