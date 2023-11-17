<?php

namespace Handlers\Contact;

use Handlers\Handler;
use Models\Contact;
use Models\DAO\ContactDAO;

class UpdateHandler extends Handler
{
    public function handle($data, $controller){
        $ContactDAO = new ContactDAO($controller->connection);
        $Contact = new Contact();

        $write = [
            'id' => $data['contact_id'],
            'name' => $data['request']['name'],
            'email' => (!isset($data['request']['email']) || empty($data['request']['email'])) ? null : $data['request']['email']
        ];
        
        // ...
    }
}
