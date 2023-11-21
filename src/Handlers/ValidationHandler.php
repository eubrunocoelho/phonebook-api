<?php

namespace Handlers;

class ValidationHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        $data['request'] = $controller->jsonRequestService->getData();

        $controller->validation->validate($data['request'], $data['rules']);

        $_errors = $controller->validation->getErrors() ?? [];
        foreach ($_errors as $key => $value) $errors[] = $value;

        if ($controller->validation->passed()) {
            foreach ($this->successors as $successor) $data = $successor->handle($data, $controller);

            return $data;
        } else return $controller->jsonResource->toJson(422, 'Erro ao fazer requisição.', ['errors' => $errors]);
    }
}
