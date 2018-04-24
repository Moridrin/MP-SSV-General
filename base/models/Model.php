<?php

namespace mp_ssv_general\base\models;

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
    protected static function doCreate(array $data): ?int
    {
        global $wpdb;
        $wpdb->insert(static::getDatabaseTableName(), $data);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            return null;
        }
        return $wpdb->insert_id;
    }

    final public static function getAll(): array
    {
        $results = self::doFind("1 = 1");
        if ($results === null) {
            return [];
        }
        $fields = [];
        foreach ($results as $row) {
            $fields[] = new static($row);
        }
        return $fields;
    }

    final public static function findById(int $id): ?Model
    {
        $row = self::doFindRow('id = '.$id);
        if ($row === null) {
            return null;
        }
        return new static($row);
    }

    final public static function findByIds(array $ids): array
    {
        $results = self::doFind('id IN ('.implode(', ', $ids).')');
        $items = [];
        foreach ($results as $row) {
            $irems[] = new static($row);
        }
        return $items;
    }

    protected static function doFind(string $where): ?array
    {
        global $wpdb;
        $table = static::getDatabaseTableName();
        $results = $wpdb->get_results("SELECT * FROM $table WHERE $where", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $results;
    }

    protected static function doFindRow(string $where): ?array
    {
        global $wpdb;
        $table = static::getDatabaseTableName();
        $row = $wpdb->get_row("SELECT * FROM $table WHERE $where", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $row;
    }

    final public static function deleteByIds(array $ids): bool
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

    abstract protected static function getDatabaseTableName(int $blogId = null): string;

    abstract protected static function getDatabaseFields(): array;

    public static function getDatabaseCreateQuery(int $blogId = null): string
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tableName = static::getDatabaseTableName($blogId);
        $fields = implode(', ', static::getDatabaseFields());
        return "CREATE TABLE IF NOT EXISTS $tableName (`id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT, $fields) $charset_collate;";
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
        $wpdb->replace(static::getDatabaseTableName(), $this->row);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            return false;
        }
        return true;
    }

    public function delete(): bool
    {
        global $wpdb;
        $wpdb->delete(static::getDatabaseTableName(), ['id' => $this->row['id']]);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            return false;
        }
        return true;
    }
    #endregion
}
