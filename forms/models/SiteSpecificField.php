<?php

namespace mp_general\forms\models;

use mp_general\base\Database;
use mp_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class SiteSpecificField extends Field
{
    #region Class
    /**
     * @param string $orderBy
     * @param string $order
     * @param string $key
     * @return SiteSpecificField[]
     */
    public static function getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'f_name'): array
    {
        return parent::_getAll($orderBy, $order, $key);
    }

    /**
     * @param int $id
     * @return Model
     * @throws \mp_general\exceptions\NotFoundException
     */
    public static function findById(int $id): Model
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
        return Database::getPrefixForBlog($blogId) . 'ssv_site_specific_fields';
    }

    public static function getDatabaseCreateQuery(int $blogId = null): string
    {
        return parent::_getDatabaseCreateQuery($blogId);
    }

    public function getType(): string
    {
        return 'Site';
    }
    #endregion
}
