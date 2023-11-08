<?php

namespace Validation;

class Validate extends Validators
{
    private $errors;

    public function validation($data, $rules)
    {
        foreach ($rules as $item => $rules) {
            foreach ($rules as $rule => $ruleValue) {
                $value = $data[$item] ?? null;

                if ($rule == 'required' || is_null($value)) {
                    if ($ruleValue && !parent::required($value)) {
                        $this->addError('O campo \'' . $item . '\' é obrigatório.');
                    }
                } elseif (parent::required($value) /* quick-fix */) {
                    switch ($rule) {
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
    }

    private function addError($message)
    {
        $this->errors[] = $message;
    }

    public function getErrors()
    {
        return array_unique($this->errors);
    }

    public function passed()
    {
        return (empty($this->errors)) ? true : false;
    }
}
