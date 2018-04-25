<?php

namespace mp_ssv_general\base\models;

use mp_ssv_general\forms\models\FormField;
use mp_ssv_general\forms\models\SharedField;
use mp_ssv_general\forms\models\SiteSpecificField;

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 21-4-18
 * Time: 22:26
 */

abstract class Model
{

    /** @var array */
    protected $row;

    #region Class
    protected static function _create(array $data): ?int
    {
        global $wpdb;
        $wpdb->insert(static::getDatabaseTableName(), $data);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            return null;
        }
        return $wpdb->insert_id;
    }

    public static function getAll(): array
    {
        $sharedFields = SharedField::getAll();
        $siteSpecificFields = SiteSpecificField::getAll();
        $formFields = FormField::getAll();
        return array_merge($sharedFields, $siteSpecificFields, $formFields);
    }

    protected static function _getAll(): array
    {
        $results = self::_find("1 = 1");
        if ($results === null) {
            return [];
        }
        $fields = [];
        foreach ($results as $row) {
            $fields[] = new static($row);
        }
        return $fields;
    }

    abstract public static function findById(int $id): ?Model;

    protected static function _findById(int $id): ?Model
    {
        $row = self::_findRow('id = ' . $id);
        if ($row === null) {
            return null;
        }
        return new static($row);
    }

    abstract public static function findByIds(array $ids): array;

    protected static function _findByIds(array $ids): array
    {
        $results = self::_find('id IN (' . implode(', ', $ids) . ')');
        $items = [];
        foreach ($results as $row) {
            $items[] = new static($row);
        }
        return $items;
    }

    protected static function _find(string $where): ?array
    {
        global $wpdb;
        $table = static::getDatabaseTableName();
        $results = $wpdb->get_results("SELECT * FROM $table WHERE $where", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $results;
    }

    protected static function _findRow(string $where): ?array
    {
        global $wpdb;
        $table = static::getDatabaseTableName();
        $row = $wpdb->get_row("SELECT * FROM $table WHERE $where", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $row;
    }

    abstract public static function deleteByIds(array $ids): bool;

    protected static function _deleteByIds(array $ids): bool
    {
        global $wpdb;
        $table = static::getDatabaseTableName();
        $ids = implode(', ', $ids);
        $wpdb->query("DELETE FROM $table WHERE id IN ($ids)");
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            return false;
        }
        return true;
    }

    abstract public static function getTableColumns(): array;

    abstract public static function getDatabaseTableName(int $blogId = null): string;

    abstract protected static function _getDatabaseFields(): array;

    abstract public static function getDatabaseCreateQuery(int $blogId = null): string;

    protected static function _getDatabaseCreateQuery(int $blogId = null): string
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tableName = static::getDatabaseTableName($blogId);
        $fields = implode(', ', static::_getDatabaseFields());
        return 'CREATE TABLE IF NOT EXISTS '.$tableName.' (`id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT, '.$fields.') '.$charset_collate.';';
    }
    #endregion

    #region Instance
    final protected function __construct(array $row)
    {
        $this->row = $row;
    }

    abstract public function getTableRow(): array;

    final public function save(): bool
    {
        global $wpdb;
        $success = true;
        if (method_exists($this, '_beforeSave')) {
            $success = call_user_func([$this, '_beforeSave']);
        }
        if ($success) {
            $wpdb->replace(static::getDatabaseTableName(), $this->row);
        }
        if (method_exists($this, '_afterSave')) {
            $success = call_user_func([$this, '_afterSave']);
        }
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            $success = false;
        }
        return $success;
    }

    public function delete(): bool
    {
        global $wpdb;
        $success = true;
        if (method_exists($this, '_beforeDelete')) {
            $success = call_user_func([$this, '_beforeDelete']);
        }
        if ($success) {
            $wpdb->delete(static::getDatabaseTableName(), ['id' => $this->row['id']]);
        }
        if (method_exists($this, '_afterDelete')) {
            $success = call_user_func([$this, '_afterDelete']);
        }
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            $success = false;
        }
        return $success;
    }
    #endregion
}
