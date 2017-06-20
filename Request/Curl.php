<?php

namespace Request;

final class Curl extends ADriver
{
    /**
     * Методы передачи данных
     */
    const
        METHOD_GET = 'GET',
        METHOD_POST = 'POST',
        METHOD_PUT = 'PUT',
        METHOD_DELETE = 'DELETE',
        METHOD_UPDATE = 'UPDATE';

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
            throw new \Exception(sprintf("can't set option %s with value %s.", [$option, $value]));
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
        if ($method === self::METHOD_POST) {
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
     * @inheritdoc
     */
    public function close()
    {
        curl_close($this->connection);
    }
}