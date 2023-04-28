<?php

class Controller {

    public $status = null;
    public $message = null;
    public $errors = [];
    public $data = [];
    public $http_code = null;

    function getErrorsAsHTMLString() {
        $html = '';
        foreach ($this->errors as $value) {
            $html .= '<p>' . $value . '</p>';
        }
        return $html;
    }

}