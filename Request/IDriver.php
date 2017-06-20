<?php

namespace Request;


interface IDriver
{
    /**
     * Исполнить запрос
     * @return void
     */
    public function exec();

    /**
     * Возвращает результат запроса
     * @return mixed
     */
    public function response();

    /**
     * Дабавляет данные к запросу
     * @param array $data
     * @return mixed
     */
    public function bindData($data);

    /**
     * Устанавливает команду запроса
     * @param $method
     * @return mixed
     */
    public function setMethod($method);

    /**
     * Закрываем соединеие
     * @return void
     */
    public function close();
}