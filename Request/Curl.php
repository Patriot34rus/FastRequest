<?php

namespace Request;

class Curl extends ARequest
{
    /**
     * Методы передачи данных
     */
    const
        CURL_METHOD_GET = 'GET',
        CURL_METHOD_POST = 'POST',
        CURL_METHOD_PUT = 'PUT',
        CURL_METHOD_DELETE = 'DELETE',
        CURL_METHOD_UPDATE = 'UPDATE';

    /**
     * @var resource
     */
    private $connection;

    /**
     * Дефолтные настройки подключения
     * @var array
     */
    private $default_options = [
        CURLOPT_COOKIEJAR => 'cookiemon.txt',
        CURLOPT_COOKIEFILE => 'cookiemon.txt',
        CURLOPT_TIMEOUT => 40000,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_ENCODING => 'UTF-8',
        CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)'
    ];

    /**
     * Curl constructor.
     * @param $URI
     * @param array $ConnectionOptions
     */
    public function __construct($URI, $ConnectionOptions = [])
    {
        $this->connection = curl_init();

        $options = $ConnectionOptions + $this->default_options;

        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        $this->setOption(CURLOPT_URL, $URI);
    }

    /**
     * Установить опцию
     * @param int $option
     * @param array $value
     */
    private function setOption($option, $value)
    {
        if (!curl_setopt($this->connection, $option, $value)) {
            throw new \Exception(sprintf("Curl connection: can't set option %s with value %s."));
        }
    }

    /**
     * @inheritdoc
     */
    public function bindData($data)
    {
        curl_setopt($this->connection, CURLOPT_POSTFIELDS, $data);
    }

    /**
     * @inheritdoc
     */
    public function setMethod($method)
    {
        if ($method === self::CURL_METHOD_POST) {
            $this->setOption(CURLOPT_POST, true);
        } else {
            $this->setOption(CURLOPT_CUSTOMREQUEST, $method);
        }
    }

    /**
     * @inheritdoc
     */
    public function exec()
    {
        $this->response = curl_exec($this->connection);
        $this->close();
    }

    /**
     * Закрываем соединеие
     */
    public function close()
    {
        curl_close($this->connection);
    }
}