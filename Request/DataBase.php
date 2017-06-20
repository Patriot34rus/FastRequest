<?php

namespace Request;

use PDO;


/**
 * Class DataBase
 * @package Request
 */
final class DataBase extends ADriver
{
    /**
     * Singleton
     * @var DataBase
     */
    private static $instance;

    /**
     * @var PDO
     */
    private $db;

    /**
     * Параметры подключения
     * @var string uri
     * @var string user
     * @var string pass
     */
    private $uri, $user, $pass;

    /**
     * DataBase constructor.
     * @param $URI
     * @param array $config
     * @throws \Exception
     */
    private function __construct($URI, array $config)
    {
        if (!isset($config['username']) || !isset($config['password'])) {
            throw new \Exception('please, passing ["username" => "..." , "password" => "..."] into Request options');
        }

        $this->uri = $URI;
        $this->user = $config['username'];
        $this->pass = $config['password'];

        $this->connectToDB();
    }

    /**
     * Установка соединения с БД
     * @throws \Exception
     */
    public function connectToDB()
    {
        $this->db = new PDO($this->uri, $this->user, $this->pass);
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
     * @return bool
     * @throws \Exception
     */
    public function exec()
    {
        if ($stm = $this->db->prepare($this->comand)) {
            $this->response = $stm->execute($this->data);
        } else {
            throw new \Exception("prepare() was failed");
        }

    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        $this->db = null;
        self::$instance = null;
    }
}