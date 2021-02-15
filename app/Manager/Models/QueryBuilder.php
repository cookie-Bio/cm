<?php


namespace Manager\Models;

use Adbar\Dot;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\InvalidPropertyAccessException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use SleekDB\Store;


abstract class QueryBuilder
{

    /**
     * Корневая директория в которой будут лежать данные
     * app, vendor, etc...
     */
    const DATA_DIR = __DIR__ . '/../../../database/';

    /**
     * Название идентификатора
     */
    const ID = 'id';


    /**
     * Стандартные настройки для базы данных
     * https://sleekdb.github.io/#/configurations
     */
    const CONFIGURATION_DB =
        [
            "auto_cache" => false,
            "cache_lifetime" => null,
            "timeout" => 120,
//            "primary_key" => self::ID
        ];
    protected string $store_name;
    protected int $id;
    protected Dot $data;
    private Store $db;

    /**
     * QueryBuilder constructor.
     * @param int $id
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws InvalidPropertyAccessException
     */
    public function __construct(int $id)
    {
        $this->id = $id;
        $this->db = new Store($this->store_name, self::DATA_DIR, self::CONFIGURATION_DB);

        $data = $this->loadRecord();
        if ($data === null) $data = $this->createRecord($this->__generateTable($id));
        $this->data = new Dot($data);
    }

    /**
     * Загрузить массив из бд
     * @return array|null
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidPropertyAccessException
     */
    private function loadRecord(): array|null
    {
        return $this->db->findOneBy([self::ID, '=', $this->id]);
    }

    /**
     * Создать запись в бд
     * @param array|null $params
     * @return array
     * @throws IOException
     * @throws IdNotAllowedException
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    private function createRecord(array $params = null): array
    {
        return $this->db->insert($params);
    }

    /**
     * Генератор массива с данными
     * @param int $id
     * @return array
     */
    protected abstract function __generateTable(int $id): array;

    /**
     * Удалить json файл
     * @return bool
     * @throws IOException
     */
    public function deleteRecord(): bool
    {
        return $this->db->deleteById($this->id);
    }

    /**
     * Добавить в массив элементы
     * @param Dot $arr
     * @return bool
     */
    protected function addTo(Dot $arr): bool
    {
        $arr->mergeRecursive($this->data);
        return $this->update($arr);
    }

    /**
     * Update db
     * @param Dot $arr
     * @return bool
     */
    protected function update(Dot $arr): bool
    {
        $arr->setArray($arr);
        return $this->db->update($arr);
    }

    /**
     * Удалить ключ в массиве
     * example members.exited.21
     * @param string $string
     * @return bool
     */
    protected function deleteIn(string $string): bool
    {
        if ($this->data->get($string) !== null) {
            $this->data->clear($string);
            return $this->update($this->data);
        } else return false;
    }

    /**
     * Удалить таблицу
     * @return bool
     * @throws IOException
     */
    public function deleteTable(): bool
    {
        unset($this->data);
        return $this->db->deleteStore();
    }
}