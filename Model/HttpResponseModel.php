<?php

class HttpResponseModel
{
    private $code = 0;
    private $header = "";
    private $message = null;
    private $HttpResponse = null;

    public function __construct($code, $header, $message)
    {
        $this->header = $header;
        $this->code = $code;
        $this->message = $message;
        $this->setHttpResponse();
    }

    /**
     * @param mixed $HttpResponse
     */
    private function setHttpResponse()
    {
        $this->HttpResponse['code'] = $this->code;
        $this->HttpResponse['header'] = $this->header;
        $this->HttpResponse['message'] = $this->message;
    }
    /**
     * @return mixed
     */
    public function getHttpResponse()
    {
        $this->setHttpResponse();
        return $this->HttpResponse;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


}