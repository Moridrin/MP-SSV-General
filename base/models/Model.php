<?php

namespace mp_general\base\models;

use mp_general\base\BaseFunctions;
use mp_general\base\SSV_Global;
use mp_general\exceptions\NotFoundException;

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
            SSV_Global::addError($wpdb->last_error);
            return null;
        }
        return $wpdb->insert_id;
    }

    abstract public static function getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'id'): array;

    protected static function _getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'id'): array
    {
        $results = self::_find("1 = 1", $orderBy, $order);
        if ($results === null) {
            return [];
        }
        $fields = [];
        foreach ($results as $row) {
            $fields[$row[$key]] = new static($row);
        }
        return $fields;
    }

    abstract public static function findById(int $id): Model;

    /**
     * @param int $id
     * @return Model
     * @throws NotFoundException
     */
    protected static function _findById(int $id): Model
    {
        $row = self::_findRow('id = ' . $id);
        if ($row === null) {
            $parts = explode('\\', static::class);
            throw new NotFoundException('The ' . end($parts) . ' with ID ' . $id . ' could not be found.');
        }
        return new static($row);
    }

    abstract public static function findByIds(array $ids, string $orderBy = 'id', string $order = 'ASC'): array;

    protected static function _findByIds(array $ids, string $orderBy = 'id', string $order = 'ASC'): array
    {
        $results = self::_find('id IN (' . implode(', ', $ids) . ')', $orderBy, $order);
        $items   = [];
        foreach ($results as $row) {
            $items[] = new static($row);
        }
        return $items;
    }

    protected static function _find(string $where, string $orderBy = 'id', string $order = 'ASC'): ?array
    {
        global $wpdb;
        $table   = static::getDatabaseTableName();
        $results = $wpdb->get_results("SELECT * FROM $table WHERE $where ORDER BY $orderBy $order", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            SSV_Global::addError($wpdb->last_error);
        }
        return $results;
    }

    protected static function _findRow(string $where): ?array
    {
        global $wpdb;
        $table = static::getDatabaseTableName();
        $row   = $wpdb->get_row("SELECT * FROM $table WHERE $where", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            SSV_Global::addError($wpdb->last_error);
        }
        return $row;
    }

    abstract public static function deleteByIds(array $ids): bool;

    protected static function _deleteByIds(array $ids): bool
    {
        global $wpdb;
        $table = static::getDatabaseTableName();
        $ids   = implode(', ', $ids);
        $wpdb->query("DELETE FROM $table WHERE id IN ($ids)");
        if (!empty($wpdb->last_error)) {
            SSV_Global::addError($wpdb->last_error);
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
        return 'CREATE TABLE IF NOT EXISTS ' . $tableName . ' (`id` BIGINT(20) PRIMARY KEY AUTO_INCREMENT, ' . $fields . ') ' . $charset_collate . ';';
    }
    #endregion

    #region Instance
    final protected function __construct(array $row)
    {
        $this->row = $row;
        if (method_exists($this, '__init')) {
            call_user_func([$this, '__init']);
        }
    }

    public function __get($name)
    {
        $methodName = 'get' . BaseFunctions::toCamelCase($name, true);
        return call_user_func([$this, $methodName]);
    }

    public function __isset($name): bool
    {
        $methodName = 'get' . BaseFunctions::toCamelCase($name, true);
        return method_exists($this, $methodName);
    }


    public function getId(): int
    {
        return $this->row['id'];
    }

    abstract public function getTableRow(): array;

    abstract public function getRowActions(): array;

    public function getData(): array
    {
        return $this->row;
    }

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
            SSV_Global::addError($wpdb->last_error);
            SSV_Global::addError((string)$wpdb->last_query);
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
            SSV_Global::addError($wpdb->last_error);
            $success = false;
        }
        return $success;
    }
    #endregion
}
