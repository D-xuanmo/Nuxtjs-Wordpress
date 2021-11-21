<?php

/**
 * 通用返回值格式
 */
class Response {
    private $code = 200;
    private $message = '';
    private $data = null;

    public function setCode($code) {
        $this->code = $code;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setResponse($data) {
        $this->data = $data;
    }

    public function getResponse(): array {
        return array(
            'code'    => $this->code,
            'message' => $this->message,
            'data'    => $this->data
        );
    }
}