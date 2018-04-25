<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\Database;
use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class Form extends Model
{
    #region Class
    public static function create(string $title, array $fields = []): ?int
    {
        return parent::_create(['f_title' => $title, 'f_fields' => json_encode($fields)]);
    }

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

    public static function findByTag(string $tag): ?Form
    {
        $row = parent::_findRow("f_tag = $tag");
        if ($row === null) {
            return null;
        } else {
            return new Form($row);
        }
    }

    public static function deleteByIds(array $ids): bool
    {
        return parent::_deleteByIds($ids);
    }

    public static function getTableColumns(): array
    {
        return [
            'Tag',
            'Title',
            'Fields',
        ];
    }

    public static function getDatabaseTableName(int $blogId = null): string
    {
        return Database::getPrefixForBlog($blogId).'ssv_forms';
    }

    protected static function _getDatabaseFields(): array
    {
        return ['`f_title` VARCHAR(50)', '`f_fields` TEXT NOT NULL'];
    }

    public static function getDatabaseCreateQuery(): string
    {
        return parent::_getDatabaseCreateQuery();
    }
    #endregion

    #region Instance
    #region getters & setters
    public function getTitle(): string
    {
        return $this->row['f_title'];
    }

    public function getFields(): array
    {
        $fields = [];
        foreach ($this->row['f_fields'] as $fieldName) {
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
        $this->row['f_title'] = $title;
        return $this;
    }

    public function setFields(array $fields): Form
    {
        $this->row['f_fields'] = $fields;
        return $this;
    }

    public function addField(Field $field): Form
    {
        $this->row['f_fields'][] = $field->getName();
        return $this;
    }

    #endregion

    public function getTableRow(): array
    {
        return [
            '[ssv-forms-' . $this->row['id'] . ']',
            $this->row['f_title'],
            $this->row['f_fields'],
        ];
    }
    #endregion
}
