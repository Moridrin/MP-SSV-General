<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class Form extends Model
{
    #region Class
    private const TABLE = 'ssv_forms';

    public static function create(string $title, array $fields = []): Form
    {
        global $wpdb;
        parent::doCreate($wpdb->prefix . self::TABLE, ['f_title' => $title, 'f_fields' => json_encode($fields)]);
    }

    public static function getAll(): array
    {
        global $wpdb;
        $results = parent::doFind($wpdb->prefix . self::TABLE, "1 = 1");
        if ($results === null) {
            return [];
        }
        $forms = [];
        foreach ($results as $row) {
            $forms[] = new Form($row['id'], $row['f_title'], json_decode($row['f_fields']));
        }
        return $forms;
    }

    public static function find(int $id): ?Form
    {
        global $wpdb;
        $row = parent::doFindRow($wpdb->prefix . self::TABLE, "id = $id");
        if ($row === null) {
            return null;
        } else {
            return new Form($id, $row['f_title'], json_decode($row['f_fields']));
        }
    }

    public static function findByTag(string $tag): ?Form
    {
        global $wpdb;
        $row = parent::doFindRow($wpdb->prefix . self::TABLE, "f_tag = $tag");
        if ($row === null) {
            return null;
        } else {
            return new Form($row['id'], $row['f_title'], json_decode($row['f_fields']));
        }
    }

    public static function getTableColumns(): array
    {
        return [
            'Tag',
            'Title',
            'Fields',
        ];
    }
    #endregion

    #region Instance
    /** @var string */
    private $title;
    /** @var array */
    private $fields;

    public function __construct(int $id, string $title, array $fields)
    {
        parent::__construct($id);
        $this->title  = $title;
        $this->fields = $fields;
    }

    #region getters & setters
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getFields(): array
    {
        $fields = [];
        foreach ($this->fields as $fieldName) {
            $fields[] = Field::findByName($fieldName);
        }
        return $fields;
    }

    public function getFieldByName(string $name): Field
    {
        return Field::findByName($name);
    }

    public function setTitle(string $title): Form
    {
        $this->title = $title;
        return $this;
    }

    public function setFields(array $fields): Form
    {
        $this->fields = $fields;
        return $this;
    }

    public function addField(Field $field): Form
    {
        $this->fields[] = $field->getName();
        return $this;
    }

    #endregion

    public function save(): bool
    {
        global $wpdb;
        return $this->doSave(
            $wpdb->prefix . self::TABLE,
            [
                'f_title'  => $this->title,
                'f_fields' => $this->fields,
            ]
        );
    }

    public function getTableRow(): array
    {
        return [
            '[ssv-forms-' . $this->id . ']',
            $this->title,
            $this->fields,
        ];
    }
    #endregion
}
