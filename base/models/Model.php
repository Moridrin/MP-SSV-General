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

    /** @var int */
    protected $id;

    #region Class
    /**
     * @param string $table
     * @param array  $data
     *
     * @return false|int
     */
    protected static function doCreate(string $table, array $data)
    {
        global $wpdb;
        $id = $wpdb->insert($table, $data);
        if ($id === false && !empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $id;
    }

    abstract public static function getAll(): array;

    protected static function doFind(string $table, string $where): ?array
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM $table WHERE $where", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $results;
    }

    protected static function doFindRow(string $table, string $where): ?array
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $table WHERE $where", ARRAY_A);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $row;
    }

    abstract public static function getTableColumns(): array;
    #endregion

    #region Instance
    protected function __construct(int $id)
    {
        $this->id = $id;
    }

    abstract public function getTableRow(): array;

    abstract public function save(): bool;

    public function doSave(string $table, array $data): bool
    {
        global $wpdb;
        $data += [
            'id' => $this->id,
        ];
        $wpdb->replace($table, $data);
        if (!empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
            return false;
        }
        return true;
    }
    #endregion
}
