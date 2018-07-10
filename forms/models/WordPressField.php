<?php

namespace mp_general\forms\models;

use mp_general\base\BaseFunctions;
use mp_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class WordPressField extends Field
{
    #region Class
    private static $fields = [
        'username'         => [
            'id'           => 0,
            'f_name'       => 'username',
            'f_properties' => ['type' => 'text', 'value' => '', 'title' => 'Username'],
        ],
        'first_name'       => [
            'id'           => 0,
            'f_name'       => 'first_name',
            'f_properties' => ['type' => 'text', 'value' => '', 'title' => 'First Name'],
        ],
        'last_name'        => [
            'id'           => 0,
            'f_name'       => 'last_name',
            'f_properties' => ['type' => 'text', 'value' => '', 'title' => 'Last Name'],
        ],
        'email'            => [
            'id'           => 0,
            'f_name'       => 'email',
            'f_properties' => ['type' => 'text', 'value' => '', 'title' => 'Email'],
        ],
        'password'         => [
            'id'           => 0,
            'f_name'       => 'password',
            'f_properties' => ['type' => 'password', 'value' => '', 'title' => 'Password'],
        ],
        'password_confirm' => [
            'id'           => 0,
            'f_name'       => 'password_confirm',
            'f_properties' => ['type' => 'password', 'value' => '', 'title' => 'Confirm Password'],
        ],
    ];

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
     * @param string $orderBy
     * @param string $order
     * @param string $key
     * @return WordPressField[]
     */
    public static function getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'f_name'): array
    {
        $fields = [];
        foreach (self::$fields as $field) {
            foreach (self::INPUT_ATTRIBUTES as $attribute => $attributeProperties) {
                $field['f_properties'][$attribute] = BaseFunctions::sanitize($field['f_properties'][$attribute] ?? $attributeProperties['default'], $attributeProperties['type']);
            }
            $field['f_properties'] = json_encode($field['f_properties']);
            $fields[$field[$key]]  = new WordPressField($field);
        }
        return $fields;
    }

    /**
     * @deprecated WordPress Fields are not findable by ID.
     * @param int|null $id
     * @return Model|null
     * @throws \Exception
     */
    public static function findById(int $id): Model
    {
        throw new \Exception('Can\'t get WordPress fields by ID');
    }

    /**
     * @deprecated WordPress Fields are not findable by ID.
     * @param array  $ids
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

    protected static function _findRow(string $where): ?array
    {
        preg_match('/f_name(.*)/', $where, $matches);
        preg_match_all('/"(.*?)"/', $matches[1], $values);
        foreach ($values[1] as $value) {
            if (isset(self::$fields[$value])) {
                $field = self::$fields[$value];
                foreach (self::INPUT_ATTRIBUTES as $attribute => $attributeProperties) {
                    $field['f_properties'][$attribute] = BaseFunctions::sanitize($field['f_properties'][$attribute] ?? $attributeProperties['default'], $attributeProperties['type']);
                }
                return $field;
            }
        }
        return null;
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
     * @param        $value
     * @return Field
     * @throws \Exception
     */
    public function setProperty(string $key, $value): Field
    {
        if ($key !== 'form_id') {
            throw new \Exception('Can\'t edit WordPress Fields');
        } else {
            return parent::setProperty($key, $value);
        }
    }

    public function getType(): string
    {
        return 'WordPress';
    }

    /**
     * @deprecated WordPress fields cannot be saved.
     * @return bool
     * @throws \Exception
     */
    protected function _beforeSave(): bool
    {
        throw new \Exception('Can\'t save WordPress Fields');
    }
    #endregion
}
