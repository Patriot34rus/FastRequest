<?php

namespace Request;

class SocketClient extends ARequest
{
    /**
     * Singleton
     * @var SocketClient
     */
    private static $instance;

    /**
     * @var bool|resource
     * @var int $buffer
     */
    private $socket, $buffer = 4096;

    /**
     * SocketClient constructor.
     * @param $URI
     */
    private function __construct($URI)
    {
        $errno =  $errstr = null;
        $this->socket = stream_socket_client($URI, $errno, $errstr);

        if($errno){
            throw new \Exception("Error Request. Socket - ".$errstr);
        }
    }

    /**
     * Возвращает обеъкт текущего соединения или создает новое
     * @param $URI
     * @param array $options
     * @return SocketClient
     */
    public static function getInstance($URI, $options = [])
    {
        if (self::$instance === null) {
            self::$instance = new self($URI, $options = []);
        }

        return self::$instance;
    }

    /**
     * @inheritdoc
     */
    public function exec()
    {
        fwrite($this->socket, $this->formatedComand());
        $this->response = fread($this->socket, $this->buffer);
    }

    /**
     * Форматирование данных в json для передачи в сокет
     * @return string
     */
    private function formatedComand()
    {
        return json_encode(['comand' => $this->comand, 'data' => $this->data]) . "\r\n";
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        fclose($this->socket);
        self::$instance = null;
    }
}