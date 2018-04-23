<?php

namespace mp_ssv_general\forms\models;

if (!defined('ABSPATH')) {
    exit;
}

class FormField extends Field
{
    #region Class
    private const TABLE = 'ssv_form_fields';

    public static function create(int $formId, string $name, array $properties = []): ?FormField
    {
        global $wpdb;
        $id = parent::doCreate($wpdb->prefix . self::TABLE, ['form_id' => $formId, 'f_name' => $name, 'f_properties' => $properties]);
        if ($id === false) {
            return null;
        }
        return new FormField($id, $formId, $name, $properties);
    }

    public static function getAll(): array
    {
        global $wpdb;
        $results = parent::doFind($wpdb->prefix . self::TABLE, "1 = 1");
        if ($results === null) {
            return [];
        }
        $fields = [];
        foreach ($results as $row) {
            $fields[] = new FormField($row['id'], $row['form_id'], $row['f_name'], json_decode($row['f_properties'], true));
        }
        return $fields;
    }

    protected static function dofindByName(string $name, ?int $formId): ?Field
    {
        global $wpdb;
        $row = parent::doFindRow($wpdb->prefix . self::TABLE, "f_name = $name");
        if ($row === null) {
            return SiteSpecificBaseField::findByName($name);
        } else {
            return new FormField($row['id'], $row['form_id'], $name, $row['f_properties']);
        }
    }
    #endregion

    #region Instance
    /* @var int */
    private $formId;

    public function __construct(int $id, int $formId, string $name, array $properties)
    {
        parent::__construct($id, $name, $properties);
        $this->formId = $formId;
    }

    public function save(): bool
    {
        global $wpdb;
        return $this->doSave($wpdb->prefix . self::TABLE, ['form_id' => $this->formId]);
    }
    #endregion
}
