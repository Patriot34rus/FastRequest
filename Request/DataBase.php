<?php

namespace Request;

use PDO;


class DataBase extends ARequest
{
    /**
     * Singleton
     * @var ARequest
     */
    private static $instance;

    /**
     * @var PDO
     */
    private $db;

    /**
     * DataBase constructor.
     * @param $URI
     * @param array $config
     */
    private function __construct($URI, array $config)
    {
        $this->db = new PDO($URI, $config['username'], $config['password']);
    }

    /**
     * @param $URI
     * @param $options
     * @return DataBase
     */
    public static function getInstance($URI, $options)
    {
        if (self::$instance === null) {
            self::$instance = new self($URI, $options);
        }

        return self::$instance;
    }

    /**
     * @inheritdoc
     */
    public function exec()
    {
        $this->response = $this->db->prepare($this->comand)->exec($this->data);
    }

    /**
     * закрывает соединение
     */
    public function close()
    {
        $this->db = null;
        self::$instance = null;
    }
}