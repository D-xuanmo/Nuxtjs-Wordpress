<?php

/**
 * 通用返回值格式
 */
class Response {
    private $code = 200;
    private $message = '请求成功';
    private $data = null;
    private $page_data = array(
        'page'     => 1,
        'pageSize' => 8,
        'total'    => 0
    );

    public function setCode($code) {
        $this->code = $code;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setResponse($data) {
        $this->data = $data;
    }

    public function setPageData($data = array()) {
        $this->page_data = $data;
    }

    public function getResponse(): array {
        return array_merge(array(
            'code'    => $this->code,
            'message' => $this->message,
            'data'    => $this->data
        ), $this->page_data);
    }
}
