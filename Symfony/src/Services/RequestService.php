<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class RequestService {

    private $request;

    public function __construct(Request $request)
    {
        $this->request = json_decode($request->getContent(), true);
    }

    public function getAll() {
        return $this->request;
    }

    public function get(string $index) {
        return isset($this->request[$index]) ? $this->request[$index] : null;
    }
}