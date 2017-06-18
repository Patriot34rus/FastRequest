<?php

namespace Request;

abstract class ARequest implements IDriver
{
    /**
     * @var mixed $response результат запроса
     * @var mixed $data данные добавляемые к запросу
     * @var mixed $comand команда запроса
     */
    protected $response, $data, $comand;

    /**
     * @inheritdoc
     */
    public function response()
    {
        $this->response();
    }

    /**
     * @inheritdoc
     */
    public function bindData($data)
    {
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function setMethod($comand)
    {
        $this->comand = $comand;
    }
}