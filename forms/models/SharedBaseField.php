<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

class SharedBaseField extends Field
{
    #region Class
    private const TABLE = 'ssv_shared_base_fields';

    public static function create(string $name, array $properties = []): FormField
    {
        global $wpdb;
        parent::doCreate($wpdb->base_prefix . self::TABLE, ['f_name' => $name, 'f_properties' => $properties]);
    }

    public static function getAll(): array
    {
        global $wpdb;
        $results = parent::doFind($wpdb->prefix . self::TABLE, "1 = 1");
        BaseFunctions::var_export($results);
        if ($results === null) {
            return [];
        }
        $fields = [];
        foreach ($results as $row) {
            $fields[] = new SharedBaseField($row['id'], $row['f_name'], json_decode($row['f_properties'], true));
        }
        return $fields;
    }

    protected static function doFindByName(string $name, ?int $formId = null): ?Field
    {
        global $wpdb;
        $row = parent::doFindRow($wpdb->base_prefix . self::TABLE, "f_name = $name");
        if ($row === null) {
            return null;
        } else {
            return new SharedBaseField($row['id'], $name, $row['f_properties']);
        }
    }
    #endregion

    #region Instance
    public function save(): bool
    {
        global $wpdb;
        return $this->doSave($wpdb->base_prefix . self::TABLE, []);
    }
    #endregion
}
