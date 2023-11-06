<?php

namespace Validation;

class Validation extends Validators
{
    private $data;
    private $errors;
    private $rules;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    public function validation()
    {
        foreach ($this->rules as $item => $rules) {
            foreach ($rules as $rule => $value) {
                if ($rule == 'required') {
                    if ((!isset($this->data[$item]) || !parent::required($value)) &&
                        $value
                    ) {
                        $this->addError('O campo "' . $item . '" é obrigatório.');
                    }
                } elseif (isset($this->data[$item])) {
                    switch ($rule) {
                        case 'min':
                            if (parent::min($this->data[$item], $value))
                                $this->addError('O campo "' . $item . '" deve conter pelo menos ' . $value . ' caracteres.');
                            break;
                    }
                }
            }
        }
    }

    public function addError($message)
    {
        $this->errors[] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
