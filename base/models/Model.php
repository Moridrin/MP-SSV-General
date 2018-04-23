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

    #region static

    /**
     * @param string $table
     * @param mixed  ...$data
     *
     * @return false|int
     */
    protected static function doCreate(string $table, ...$data)
    {
        global $wpdb;
        $id = $wpdb->insert($table, $data);
        if ($id === false && !empty($wpdb->last_error)) {
            $_SESSION['SSV']['errors'][] = $wpdb->last_error;
        }
        return $id;
    }

    public static function doFind(string $table, string $where): ?array
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

    #region public
    protected function __construct(int $id)
    {
        $this->id = $id;
    }

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

    abstract public function getTableRow(): array;
    #endregion
}
