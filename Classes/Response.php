<?php

class Response
{
    public $outcome;
    public $reason;
    public $response;
    public $request;
    protected $set = false;

    public function set($_outcome, $_reason, $_response)
    {
        if ($this->set == false || $this->outcome === 'SUCCESS') {
            $this->set = true;
            $this->outcome = $_outcome;
            if (isset($_reason)) {
                $this->reason = $_reason;
            } else {
                unset($this->reason);
            }

            if (!is_null($_response)) {
                $this->response = $_response;
            } else {
                unset($this->response);
            }
        }
    }
}
