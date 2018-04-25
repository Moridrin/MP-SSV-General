<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\Database;
use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class SiteSpecificField extends Field
{
    #region Class
    public static function getAll(): array
    {
        return parent::_getAll();
    }

    public static function findById(int $id): ?Model
    {
        return parent::_findById($id);
    }

    public static function findByIds(array $ids): array
    {
        return parent::_findByIds($ids);
    }

    public static function deleteByIds(array $ids): bool
    {
        return parent::_deleteByIds($ids);
    }

    public static function getDatabaseTableName(int $blogId = null): string
    {
        return Database::getPrefixForBlog($blogId).'ssv_base_fields';
    }

    public static function getDatabaseCreateQuery(): string
    {
        return parent::_getDatabaseCreateQuery();
    }
    #endregion
}
