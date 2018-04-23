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

    public static function find(int $id): ?Form
    {
        $row = parent::doFind(self::TABLE, "id = $id");
        if ($row === null) {
            return null;
        } else {
            return new Form($id, $row['bf_name'], $row['bf_properties']);
        }
    }

    public static function findByTag(string $tag): ?Form
    {
        $row = parent::doFind(self::TABLE, "f_tag = $tag");
        if ($row === null) {
            return null;
        } else {
            return new Form($row['id'], $row['bf_name'], $row['bf_properties']);
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
            $fields[] = BaseField::findByName($fieldName);
        }
        return $fields;
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

    public function setFields(array $fields): Form
    {
        $this->fields = $fields;
        return $this;
    }

    #endregion

    public function save(): bool
    {
        return $this->doSave(
            self::TABLE,
            [
                'bf_name'       => $this->title,
                'bf_properties' => $this->fields,
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
