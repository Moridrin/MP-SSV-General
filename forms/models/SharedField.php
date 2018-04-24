<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\Database;

if (!defined('ABSPATH')) {
    exit;
}

class SharedField extends Field
{
    #region Class
    protected static function getDatabaseTableName(int $blogId = null): string
    {
        return Database::getBasePrefix().'ssv_shared_base_fields';
    }
    #endregion
}
