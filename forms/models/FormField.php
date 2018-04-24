<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\Database;

if (!defined('ABSPATH')) {
    exit;
}

class FormField extends Field
{
    #region Class
    public static function create(string $name, array $properties = [], int $formId = null): Field
    {
        parent::doCreate(['form_id' => $formId, 'f_name' => $name, 'f_properties' => $properties]);
    }

    protected static function getDatabaseTableName(int $blogId = null): string
    {
        return Database::getPrefixForBlog($blogId).'ssv_form_fields';
    }

    protected static function getDatabaseFields(): array
    {
        return ['`form_id` BIGINT(20) NOT NULL', '`f_name` VARCHAR(50)', '`f_properties` TEXT NOT NULL'];
    }
    #endregion
}
