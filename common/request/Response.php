<?php


namespace common\request;


class Response
{
    public $success = true, $data = null, $message = null, $title = null, $code = 0, $paging = null, $timestamp, $other_data = null;

    public function __construct($success = true, $data = null, $message = null, $title = null, $code = 0, $paging = null, $other_data = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->message = $message;
        $this->title = $title;
        $this->code = $code;
        $this->paging = $paging;
        $this->other_data = $other_data;
        $this->timestamp = time();
    }
}
