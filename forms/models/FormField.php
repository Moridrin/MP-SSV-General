<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\Database;
use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class FormField extends Field
{
    #region Class
    public static function create(string $name, array $properties = [], int $formId = null): ?int
    {
        return parent::_create(['form_id' => $formId, 'f_name' => $name, 'f_properties' => json_encode($properties)]);
    }

    public static function getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'f_name'): array
    {
        return parent::_getAll($orderBy, $order);
    }

    /**
     * @param int $id
     * @return FormField
     * @throws \mp_ssv_general\exceptions\NotFoundException
     */
    public static function findById(int $id): Model
    {
        return parent::_findById($id);
    }

    public static function findByIds(array $ids, string $orderBy = 'id', string $order = 'ASC'): array
    {
        return parent::_findByIds($ids, $orderBy, $order);
    }

    final public static function findByName(string $name, int $formId): ?Field
    {
        // Form Field
        $field = null;
        $row   = FormField::_findRow('f_name = "' . $name . '" AND form_id = "' . $formId . '"');
        if ($row !== null) {
            $row['form_id'] = $formId;
            return new FormField($row);
        }

        // Site Specific Field
        $row = SiteSpecificField::_findRow('f_name = "' . $name . '"');
        if ($row !== null) {
            $row['form_id'] = $formId;
            return new SiteSpecificField($row);
        }

        // Shared Field
        $row = SharedField::_findRow('f_name = "' . $name . '"');
        if ($row !== null) {
            $row['form_id'] = $formId;
            return new SharedField($row);
        }

        // WordPress Field
        $row = WordPressField::_findRow('f_name = "' . $name . '"');
        if ($row !== null) {
            $row['form_id'] = $formId;
            return new WordPressField($row);
        }

        return $field;
    }

    public static function deleteByIds(array $ids): bool
    {
        return parent::_deleteByIds($ids);
    }

    public static function getDatabaseTableName(int $blogId = null): string
    {
        return Database::getPrefixForBlog($blogId) . 'ssv_form_fields';
    }

    public static function getDatabaseCreateQuery(int $blogId = null): string
    {
        return parent::_getDatabaseCreateQuery($blogId);
    }

    protected static function _getDatabaseFields(): array
    {
        return ['`form_id` BIGINT(20) NOT NULL', '`f_name` VARCHAR(50)', '`f_properties` TEXT NOT NULL'];
    }

    public function getType(): string
    {
        return 'Form';
    }
    #endregion
}
