<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\Database;
use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class WordPressField extends Field
{
    #region Class
    private static $fieldArrays = [
        'username' => [
            'id' => 0,
            'f_name' => 'username',
            'f_properties' => ['name' => 'username', 'type' => 'text', 'value' => ''],
        ],
        'first_name' => [
            'id' => 0,
            'f_name' => 'first_name',
            'f_properties' => ['name' => 'first_name', 'type' => 'text', 'value' => ''],
        ],
        'last_name' => [
            'id' => 0,
            'f_name' => 'last_name',
            'f_properties' => ['name' => 'last_name', 'type' => 'text', 'value' => ''],
        ],
        'email' => [
            'id' => 0,
            'f_name' => 'email',
            'f_properties' => ['name' => 'email', 'type' => 'text', 'value' => ''],
        ],
        'password' => [
            'id' => 0,
            'f_name' => 'password',
            'f_properties' => ['name' => 'password', 'type' => 'password', 'value' => ''],
        ],
        'password_confirm' => [
            'id' => 0,
            'f_name' => 'password_confirm',
            'f_properties' => ['name' => 'password_confirm', 'type' => 'password', 'value' => ''],
        ],
    ];
    /**
     * @param string $orderBy
     * @param string $order
     * @param string $key
     * @return WordPressField[]
     */
    public static function getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'f_name'): array
    {
        $fields = [];
        foreach (self::$fieldArrays as $fieldArray) {
            $fieldArray['f_properties'] = json_encode($fieldArray['f_properties']);
            $fields[$fieldArray[$key]] = new WordPressField($fieldArray);
        }
        return $fields;
    }

    public static function getAllExcept(array $except, string $orderBy = 'id', string $order = 'ASC', string $key = 'f_name'): array
    {
        $fields = self::getAll($orderBy, $order, $key);
        foreach ($except as $field) {
            if ($field instanceof Field) {
                unset($fields[$field->getName()]);
            } elseif (is_string($field)) {
                unset($fields[$field]);
            }
        }
        return $fields;
    }

    /**
     * @deprecated WordPress Fields are not findable by ID.
     * @param int|null $id
     * @return Model|null
     * @throws \Exception
     */
    public static function findById(?int $id): ?Model
    {
        throw new \Exception('Can\'t get WordPress fields by ID');
    }

    /**
     * @deprecated WordPress Fields are not findable by ID.
     * @param array $ids
     * @param string $orderBy
     * @param string $order
     * @return array
     * @throws \Exception
     */
    public static function findByIds(array $ids, string $orderBy = 'id', string $order = 'ASC'): array
    {
        throw new \Exception('Can\'t get WordPress fields by ID');
    }

    /**
     * @deprecated WordPress Fields can not be deleted.
     * @param array $ids
     * @return bool
     * @throws \Exception
     */
    public static function deleteByIds(array $ids): bool
    {
        throw new \Exception('Can\'t delete WordPress fields');
    }

    /**
     * @deprecated WordPress Fields do not have a database table name
     * @param int|null $blogId
     * @return string
     * @throws \Exception
     */
    public static function getDatabaseTableName(int $blogId = null): string
    {
        throw new \Exception('Can\'t get the WordPress Fields Table name');
    }

    /**
     * @deprecated WordPress fields have no table in the Database
     * @param int|null $blogId
     * @return string
     * @throws \Exception
     */
    public static function getDatabaseCreateQuery(int $blogId = null): string
    {
        throw new \Exception('There is no database Create Query for the WordPress Fields');
    }
    #endregion

    #region Instance
    /**
     * @deprecated WordPress fields cannot be edited.
     * @param string $name
     * @return Field
     * @throws \Exception
     */
    public function setName(string $name): Field
    {
        throw new \Exception('Can\'t edit WordPress Fields');
    }

    /**
     * @deprecated WordPress fields cannot be edited.
     * @param array $properties
     * @return Field
     * @throws \Exception
     */
    public function setProperties(array $properties): Field
    {
        throw new \Exception('Can\'t edit WordPress Fields');
    }

    /**
     * @deprecated WordPress fields cannot be edited.
     * @param string $key
     * @param $value
     * @return Field
     * @throws \Exception
     */
    public function setProperty(string $key, $value): Field
    {
        throw new \Exception('Can\'t edit WordPress Fields');
    }

    protected function _beforeSave(): bool
    {
        throw new \Exception('Can\'t edit WordPress Fields');
    }

    public function getType(): string
    {
        return 'WordPress';
    }
    #endregion
}
