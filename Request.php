<?php

use Request\Curl;
use Request\DataBase;
use Request\IDriver;
use Request\SocketClient;


final class Request
{
    /**
     * Типы подключения
     */
    const
        TYPE_CURL = 'curl',
        TYPE_SOCKET = 'socket',
        TYPE_DATA_BASE = 'data_base';

    /**
     * Объект выбраного типа соединения
     * @var IDriver
     */
    private $connection;

    /**
     * Request constructor.
     * @param $URI
     * @param $ConnectionType
     * @param array $ConnectionParams
     * @throws Exception
     */
    public function __construct($URI, $ConnectionType, $ConnectionParams = [])
    {
        try {
           $this->createConnection($URI, $ConnectionType, $ConnectionParams);
        } catch (\Exception $ex) {
            throw new \Exception("Request::Can't creat connection - " . $ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * Отправить данные
     * @param null $data данные
     * @param string $method
     * @return mixed
     * @throws Exception
     */
    public function send($data = null, $method = null)
    {
        try {
            if ($method !== null) {
                $this->connection->setMethod($method);
            }

            if ($data !== null) {
                $this->connection->bindData($data);
            }

            $this->connection->exec();
            return $this->connection->response();
        } catch (\Exception $ex) {
            throw new \Exception("Error sending request", $ex->getCode(), $ex);
        }
    }

    /**
     * Создает объект соединения
     * @param $URI
     * @param $ConnectionType
     * @param array $ConnectionParams
     * @throws Exception
     */
    private function createConnection($URI, $ConnectionType, $ConnectionParams = []){
        switch ($ConnectionType) {
            case self::TYPE_CURL:
                $this->setConnection(new Curl($URI, $ConnectionParams));
                break;

            case self::TYPE_SOCKET:
                $this->setConnection(SocketClient::getInstance($URI, $ConnectionParams));
                break;

            case self::TYPE_DATA_BASE:
                $this->setConnection(DataBase::getInstance($URI, $ConnectionParams));
                break;

            default:
                throw new \Exception("unknow connection type");
        }
    }

    /**
     * Установить объект текущего соединения
     * @param IDriver $connect
     */
    private function setConnection(IDriver $connect)
    {
        $this->connection = $connect;
    }
}