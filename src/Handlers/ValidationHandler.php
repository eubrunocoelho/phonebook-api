<?php

namespace Handlers;

class ValidationHandler extends Handler
{
    public function handle($data, $controller)
    {
        $data['request'] = $controller->jsonRequestService->getData();

        $controller->validate->validate($data['request'], $data['rules']);

        $_errors = $controller->validate->getErrors() ?? [];
        foreach ($_errors as $key => $value) $errors[] = $value;

        if ($controller->validate->passed()) {
            if ($this->successor !== null) $this->successor->handle($data, $controller);
        } else {
            return $controller->jsonResource->toJson(422, 'Erro ao fazer requisição.', ['errors' => $errors]);
        }
    }
}
