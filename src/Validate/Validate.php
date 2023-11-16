<?php

namespace Validate;

class Validate extends Validators
{
    private $errors;

    public function validate(array $data, array $rules): void
    {
        foreach ($rules as $item => $rules) {
            foreach ($rules as $rule => $ruleValue) {
                $value = $data[$item] ?? '';

                if ($rule == 'required' || parent::isEmpty($value)) {
                    if ($rules['required'] == true && parent::isEmpty($value)) $this->addError('O campo \'' . $item . '\' é obrigatório.');
                } elseif (!parent::isEmpty($value)) switch ($rule) {
                    case 'unique':
                        if (!parent::unique($value, $ruleValue))
                            $this->addError('Este \'' . $item . '\' já está cadastrado.');
                        break;

                    case 'unique-for-update':
                        if (!parent::uniqueForUpdate($value, $ruleValue))
                            $this->addError('Este \'' . $item . '\' já está cadastrado.');
                        break;
                    case 'min':
                        if (!parent::min($value, $ruleValue))
                            $this->addError('O campo \'' . $item . '\' deve conter pelo menos ' . $ruleValue . ' caracteres.');
                        break;
                    case 'max':
                        if (!parent::max($value, $ruleValue))
                            $this->addError('O campo \'' . $item . '\' deve conter no máximo ' . $ruleValue . ' caracteres.');
                        break;
                    case 'email':
                        if (!parent::email($value))
                            $this->addError('O endereço de e-mail está inválido.');
                        break;
                    case 'regex':
                        if (!parent::regex($value, $ruleValue))
                            $this->addError('O campo \'' . $item . '\' está inválido.');
                        break;
                }
            }
        }
    }

    private function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    public function getErrors(): array
    {
        return ($this->errors != null) ? array_unique($this->errors) : [];
    }

    public function passed(): bool
    {
        return (empty($this->errors)) ? true : false;
    }
}
