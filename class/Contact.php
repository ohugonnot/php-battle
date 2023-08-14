<?php

require "repository.php";

class Contact
{
    public PDO|Envms\FluentPDO\Query $db;
    public ?int $id;
    public ?string $email;
    public ?string $name;
    public ?string $message;

    public array $errors = [];
    public ?string $captcha_token;

    public function __construct(array $contact)
    {
        $this->db = connectBDD(true);
        $this->id = $contact["id"] ?? null;
        $this->email = $contact["email"] ?? null;
        $this->name = $contact["name"] ?? null;
        $this->message = $contact["message"] ?? null;
        $this->captcha_token = $contact["recaptcha-response"] ?? null;
    }

    public function isValid(): bool
    {
        if (empty($this->message)) {
            $this->errors['message'] = "Attention le message est vide";
        }
        if (empty($this->email)) {
            $this->errors['email'] = "Attention le email est vide";
        }
        if (empty($this->name)) {
            $this->errors['name'] = "Attention le name est vide";
        }
        if (empty($this->errors))
            return true;
        return false;
    }

    public function captchaIsValid(): bool
    {
        $url = "https://www.google.com/recaptcha/api/siteverify?secret={$_ENV['SECRET_KEY_GOOGLE_CAPTCHA']}&response={$this->captcha_token}";
        $response = file_get_contents($url);
        if (!empty($response) && !is_null($response)) {
            $data = json_decode($response);
            if ($data->success) {
                return true;
            }
        }
        return false;
    }

    public function save()
    {
        $this->id = $this->db->insertInto('contact')->values([
                "name" => $this->name,
                "email" => $this->email,
                "message" => $this->message,
            ]
        )->execute();
    }
}