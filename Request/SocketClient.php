<?php

namespace Request;

class SocketClient extends ARequest
{
    private static $instance;

    private $socket, $buffer = 4096;

    private function __construct($URI)
    {
        $this->socket = stream_socket_client($URI, $errno, $errstr);
    }

    public static function getInstance($URI, $options = [])
    {
        if (self::$instance === null) {
            self::$instance = new self($URI, $options = []);
        }

        return self::$instance;
    }

    public function exec()
    {
        fwrite($this->socket, $this->formatedComand());
        $this->response = fread($this->socket, $this->buffer);
    }

    private function formatedComand()
    {
        return json_encode(['comand' => $this->comand, 'data' => $this->data]) . "\r\n";
    }

    public function close()
    {
        fclose($this->socket);
        self::$instance = null;
    }
}