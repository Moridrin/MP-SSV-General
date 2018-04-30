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
    /**
     * @param string $orderBy
     * @param string $order
     * @return WordPressField[]
     */
    public static function getAll(string $orderBy = 'id', string $order = 'ASC'): array
    {
        return [
            new WordPressField(['id' => 0, 'f_name' => 'username', 'f_properties' => json_encode(['name' => 'username', 'type' => 'text', 'value' => ''])]),
            new WordPressField(['id' => 0, 'f_name' => 'first_name', 'f_properties' => json_encode(['name' => 'first_name', 'type' => 'text', 'value' => ''])]),
            new WordPressField(['id' => 0, 'f_name' => 'last_name', 'f_properties' => json_encode(['name' => 'last_name', 'type' => 'text', 'value' => ''])]),
            new WordPressField(['id' => 0, 'f_name' => 'email', 'f_properties' => json_encode(['name' => 'email', 'type' => 'text', 'value' => ''])]),
            new WordPressField(['id' => 0, 'f_name' => 'password', 'f_properties' => json_encode(['name' => 'password', 'type' => 'password', 'value' => ''])]),
            new WordPressField(['id' => 0, 'f_name' => 'password_confirm', 'f_properties' => json_encode(['name' => 'password_confirm', 'type' => 'password', 'value' => ''])]),
        ];
    }

    public static function findById(?int $id): ?Model
    {
        return parent::_findById($id);
    }

    public static function findByIds(array $ids, string $orderBy = 'id', string $order = 'ASC'): array
    {
        return parent::_findByIds($ids, $orderBy, $order);
    }

    public static function deleteByIds(array $ids): bool
    {
        return parent::_deleteByIds($ids);
    }

    public static function getDatabaseTableName(int $blogId = null): string
    {
        return Database::getBasePrefix().'ssv_shared_fields';
    }

    public static function getDatabaseCreateQuery(int $blogId = null): string
    {
        return parent::_getDatabaseCreateQuery($blogId);
    }
    #endregion

    #region Instance
    public function setName(string $name): Field
    {
        return $this;
    }

    public function setProperties(array $properties): Field
    {
        return $this;
    }

    public function setProperty(string $key, $value): Field
    {
        return $this;
    }
    protected function _beforeSave(): bool
    {
        throw new \Exception('Can\'t edit WordPress Fields');
    }
    #endregion
}
