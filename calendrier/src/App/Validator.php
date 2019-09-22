<?php

namespace App;

class Validator
{

    private $data;
    protected $errors = [];

    /**
     * @param array $data
     * @return array|bool
     */

    public function validates(array $data)
    {
        $this->errors = [];
        $this->data = $data;
    }

    public function validate(string $field, string $method, ...$parameters)
    {
        if(!isset($this->data[$field])) {
            $this->errors[$field] = "Le champs $field n'est pas rempli";
        } else {
            call_user_func([$this, $method], $field, ...$parameters);
        }
    }

    public function minLength(string $field, int $length)
    {
        if(mb_strlen($field) < $length) {
            $this->errors[$field] = "Le champs doit avoir plus de $length caractères";
        }
    }

    public function date (string $field)
    {
       if (\DateTime::createFromFormat('Y-m-d', $this->data[$field]) === false) {
           $this->errors[$field] = "La date n'est pas valide";
       }
    }

/*    public function time (string $field)
    {
        if (\DateTime::createFromFormat('H-i', $this->data[$field]) === false) {
            $this->errors[$field] = "Le temps n'est pas valide";
        }
    }*/

}