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
    public static function create(string $title, string $submitText = 'submit', array $fields = []): ?int
    {
        return parent::_create(['f_title' => $title, 'f_submitText' => $submitText, 'f_fields' => json_encode($fields)]);
    }

    public static function getDummy(): Form
    {
        return new Form(['id' => -1, 'f_title' => '', 'f_submitText' => 'Submit', 'f_fields' => json_encode([])]);
    }

    public static function getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'id'): array
    {
        return parent::_getAll($orderBy, $order);
    }

    /**
     * @param int $id
     * @return Form
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
            'id'       => 'Tag',
            'f_title'  => 'Title',
            'f_fields' => 'Fields',
        ];
    }

    public static function getDatabaseTableName(int $blogId = null): string
    {
        return Database::getPrefixForBlog($blogId) . 'ssv_forms';
    }

    public static function getDatabaseCreateQuery(int $blogId = null): string
    {
        return parent::_getDatabaseCreateQuery($blogId);
    }

    protected static function _getDatabaseFields(): array
    {
        return ['`f_title` VARCHAR(50)', '`f_fields` TEXT NOT NULL', '`f_submitText` VARCHAR(50)'];
    }
    #endregion

    #region Instance
    public function getTitle(): string
    {
        return $this->row['f_title'];
    }

    #region getters & setters
    public function getFieldByName(string $name): Field
    {
        return FormField::findByName($name, $this->getId());
    }

    public function setTitle(string $title): Form
    {
        $this->row['f_title'] = $title;
        return $this;
    }

    public function setSubmitText(string $title): Form
    {
        $this->row['f_submitText'] = $title;
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

    public function getTableRow(): array
    {
        return [
            'id'           => '[ssv-forms-' . $this->row['id'] . ']',
            'f_title'      => $this->row['f_title'],
            'f_submitText' => $this->row['f_submitText'],
            'f_fields'     => $this->row['f_fields'],
        ];
    }

    public function getRowActions(): array
    {
        return [
            [
                'spanClass' => '',
                'href'      => admin_url('admin.php') . '?page=ssv_forms&action=edit&id=' . $this->getId(),
                'linkClass' => 'edit',
                'linkText'  => 'Edit',
            ],
            [
                'spanClass' => 'trash',
                'onclick'   => 'formsManager.deleteRow(\'' . $this->getId() . '\')',
                'linkClass' => 'submitdelete',
                'linkText'  => 'Trash',
            ],
        ];
    }

    #endregion

    public function getData(): array
    {
        return $this->row['f_fields'];
    }

    public function getFields(): array
    {
        $fields = [];
        foreach ($this->row['f_fields'] as $fieldName) {
            $fields[] = FormField::findByName($fieldName, $this->getId());
        }
        return $fields;
    }

    public function getSubmitText(): string
    {
        return $this->row['f_submitText'];
    }

    protected function __init(): void
    {
        $this->row['f_fields'] = json_decode($this->row['f_fields']);
    }

    protected function _beforeSave(): bool
    {
        $this->row['f_fields'] = json_encode($this->row['f_fields']);
        return true;
    }

    public function __toString(): string
    {
        ob_start();
        ?>
        <h2><?= $this->getTitle() ?></h2>
        <?php
        foreach ($this->getFields() as $field) {
            echo $field;
        }
        ?><input name="submit" type="submit" id="submit" class="submit" value="<?= $this->getSubmitText() ?>"><?php
        return ob_get_clean();
    }
    #endregion
}
