<?php

namespace App\Api;

class ApiMessages
{
    private $message = [];

    public function __construct(string $message, $code, array $data = [])
    {
        $this->message['message'] = $message;
        $this->message['code'] = $code;
        $this->message['errors'] = $data;   
    }

    public function getMessage(): array{
        return $this->message;
    }
}
