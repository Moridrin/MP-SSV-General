<?php

namespace mp_ssv_general\forms\models;

use DateTime;
use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\models\Model;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Field extends Model
{
    #region Class
    public static function create(string $name, array $properties = []): ?int
    {
        return parent::_create(['f_name' => $name, 'f_properties' => json_encode($properties)]);
    }

    /**
     * @param string $orderBy
     * @param string $order
     * @return Field[]
     */
    public static function getAll(string $orderBy = 'id', string $order = 'ASC'): array
    {
        BaseFunctions::var_export('### Site Specific ###');
        foreach (SiteSpecificField::getAll($orderBy, $order) as $field) {
            BaseFunctions::var_export($field->getName());
        }
        BaseFunctions::var_export('### Shared ###');
        foreach (SharedField::getAll($orderBy, $order) as $field) {
            BaseFunctions::var_export($field->getName());
        }
        BaseFunctions::var_export('### WordPress ###');
        foreach (WordPressField::getAll($orderBy, $order) as $field) {
            BaseFunctions::var_export($field->getName());
        }
        $fields = SiteSpecificField::getAll($orderBy, $order);
        $fields = array_merge($fields, SharedField::getAllExcept($fields, $orderBy, $order));
        $fields = array_merge($fields, WordPressField::getAllExcept($fields, $orderBy, $order));
        return $fields;
    }

    public static function getAllExcept(array $except, string $orderBy = 'id', string $order = 'ASC'): array
    {
        $names = [];
        foreach ($except as $field) {
            if ($field instanceof Field) {
                $names[] = $field->getName();
            } elseif (is_string($field)) {
                $names[] = $field;
            }
        }
        if (empty($names)) {
            return self::getAll();
        } else {
            $names = implode('\', \'', $names);
            $results = self::_find("f_name NOT IN ('$names')", $orderBy, $order);
            if ($results === null) {
                return [];
            }
            $fields = [];
            foreach ($results as $row) {
                $fields[] = new static($row);
            }
            return $fields;
        }
    }

    final public static function findByName(string $name, int $formId): ?Field
    {
        // Form Field
        $row = FormField::_findRow('f_name = ' . $name . ' AND form_id = ' . $formId);
        if ($row !== null) {
            return new FormField($row);
        }

        // Site Specific Field
        $row = SiteSpecificField::_findRow('f_name = ' . $name);
        if ($row !== null) {
            return new SiteSpecificField($row);
        }

        // Shared Field
        $row = SharedField::_findRow('f_name = ' . $name);
        if ($row !== null) {
            return new SharedField($row);
        }

        // WordPress Field
        $row = WordPressField::_findRow('f_name = ' . $name);
        if ($row !== null) {
            return new WordPressField($row);
        }

        return null;
    }

    public static function getTableColumns(): array
    {
        return [
            'f_name' => 'Name',
            'f_properties/type' => 'Input Type',
            'f_properties/value' => 'Value',
        ];
    }

    protected static function _getDatabaseFields(): array
    {
        return ['`f_name` VARCHAR(50)', '`f_properties` TEXT NOT NULL'];
    }
    #endregion

    #region Instance
    private $oldName = null;

    protected function __init(): void
    {
        $this->row['f_properties'] = json_decode($this->row['f_properties'], true);
    }

    #region getters & setters
    public function getName(): string
    {
        return $this->row['f_name'];
    }

    public function getProperties(): array
    {
        return $this->row['f_properties'];
    }

    public function getProperty(string $key)
    {
        if (!isset($this->row['f_properties'][$key])) {
            $this->row['f_properties'][$key] = null;
        }
        return $this->row['f_properties'][$key];
    }

    public function setName(string $name): self
    {
        if ($this->oldName === null && $name !== $this->row['f_name']) {
            $this->oldName = $this->row['f_name'];
        }
        $this->row['f_name'] = $name;
        $this->setProperty('name', $name);
        return $this;
    }

    public function setProperties(array $properties): self
    {
        $this->row['f_properties'] = $properties;
        return $this;
    }

    public function setProperty(string $key, $value): self
    {
        $this->row['f_properties'][$key] = $value;
        return $this;
    }
    #endregion

    public function getTableRow(): array
    {
        return [
            'f_name' => $this->row['f_name'],
            'f_properties/type' => $this->row['f_properties']['type'],
            'f_properties/value' => $this->row['f_properties']['value'],
        ];
    }

    public function getRowActions(): array
    {
        return [
            [
                'spanClass' => 'inline',
                'onclick' => 'fieldsManager.edit(\'' . $this->getId() . '\')',
                'linkClass' => 'editinline',
                'linkText' => 'Edit',
            ],
            [
                'spanClass' => 'inline',
                'onclick' => 'fieldsManager.customize(\'' . $this->getId() . '\')',
                'linkClass' => 'editinline',
                'linkText' => 'Customize',
            ],
            [
                'spanClass' => 'trash',
                'onclick' => 'fieldsManager.deleteRow(\'' . $this->getId() . '\')',
                'linkClass' => 'submitdelete',
                'linkText' => 'Trash',
            ],
        ];
    }

    protected function _beforeSave(): bool
    {
        $this->row['f_properties'] = json_encode($this->row['f_properties']);
        return true;
    }

    protected function _afterSave(): bool
    {
        if ($this->oldName !== $this->row['f_name']) {
            // TODO Rename
        }
        return true;
    }

    public function __toString(): string
    {
        switch ($this->row['f_properties']['inputType']) {
            case 'hidden':
                return $this->_getHiddenInputFieldHtml();
            case 'select':
                /** @noinspection PhpIncludeInspection */
                require_once SSV_Forms::PATH . 'templates/fields/select.php';
                show_select_input_field($this);
                break;
            case 'checkbox':
                /** @noinspection PhpIncludeInspection */
                require_once SSV_Forms::PATH . 'templates/fields/checkbox.php';
                show_checkbox_input_field($this);
                break;
            case 'datetime':
                /** @noinspection PhpIncludeInspection */
                require_once SSV_Forms::PATH . 'templates/fields/datetime.php';
                show_datetime_input_field($this);
                break;
            default:
                /** @noinspection PhpIncludeInspection */
                require_once SSV_Forms::PATH . 'templates/fields/input.php';
                show_default_input_field($this);
                break;
        }
    }

    private function _getHiddenInputFieldHtml(): string
    {
        $this->row['f_properties'] += [
            'defaultValue' => '',
        ];
        if (strtolower($this->row['f_properties']['defaultValue']) === 'now') {
            $this->row['f_properties']['defaultValue '] = (new DateTime($this->row['f_properties']['defaultValue']))->format('Y-m-d');
        }
        $id                = BaseFunctions::escape('input_' . $this->row['f_properties']['name'], 'attr');
        return '<input '.Field::getElementAttributesString($id, ['type', 'value'], '').'/>';
    }

    private function getElementAttributesString(string $element, array $options = [], string $nameSuffix = null): string
    {
        $properties = $this->row['f_properties'] + [
            'inputType'      => 'text',
            'classes'        => [],
            'styles'         => [],
            'overrideRights' => [],
            'required'       => false,
            'disabled'       => false,
            'checked'        => false,
            'value'          => null,
            'autocomplete'   => null,
            'placeholder'    => null,
            'list'           => null,
            'pattern'        => null,
            'multiple'       => false,
            'selected'       => false,
            'profileField'   => true,
            'size'           => 1,
        ];
        $currentUserCanOverride = $this->_currentUserCanOverride();
        $attributesString       = 'id="' . BaseFunctions::escape($properties['form_id'] . '_' . $element . '_' . $properties['name'], 'attr') . '"';
        if (in_array('type', $options)) {
            $attributesString .= ' type="' . $properties['inputType'] . '"';
        }
        if (isset($properties['classes'][$element])) {
            $attributesString .= ' class="' . BaseFunctions::escape($properties['classes'][$element], 'attr', ' ') . '"';
        }
        if (isset($properties['styles'][$element])) {
            $attributesString .= ' style="' . BaseFunctions::escape($properties['styles'][$element], 'attr', ' ') . '"';
        }
        if ($nameSuffix !== null) {
            $attributesString .= ' name="' . BaseFunctions::escape($properties['name'] . $nameSuffix, 'attr') . '"';
        }
        if (!$currentUserCanOverride && in_array('required', $options) && $properties['required']) {
            $attributesString .= $properties['required'] ? 'required="required"' : '';
        }
        if (!$currentUserCanOverride && in_array('disabled', $options) && $properties['disabled']) {
            $attributesString .= disabled($properties['disabled'], true, false);
        }
        if (in_array('checked', $options) && $properties['checked']) {
            $attributesString .= checked($properties['checked'], true, false);
        }
        if (in_array('value', $options)) {
            $profileValue = User::getCurrent()->getMeta($properties['name']);
            if (!empty($properties['value'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($properties['value'], 'attr') . '"';
            } elseif ($properties['profileField'] && !empty($profileValue)) {
                $attributesString .= ' value="' . BaseFunctions::escape($profileValue, 'attr') . '"';
            } elseif (!empty($properties['defaultValue'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($properties['defaultValue'], 'attr') . '"';
            }
        }
        if (in_array('multiple', $options) && $properties['multiple']) {
            $attributesString .= ' multiple="multiple"';
        }
        if (in_array('size', $options) && $properties['size'] > 1) {
            $attributesString .= ' size="' . BaseFunctions::escape($properties['size'], 'attr') . '"';
        }
        if (in_array('for', $options)) {
            $attributesString .= ' for="' . BaseFunctions::escape($properties['formId'] . '_' . 'input_' . $properties['name'], 'attr') . '"';
        }
        if (in_array('autocomplete', $options) && !empty($properties['autocomplete'])) {
            $attributesString .= ' autocomplete="' . $properties['autocomplete'] . '"';
        }
        if (in_array('placeholder', $options) && !empty($properties['placeholder'])) {
            $attributesString .= ' placeholder="' . $properties['placeholder'] . '"';
        }
        if (in_array('list', $options) && !empty($properties['list'])) {
            $attributesString .= ' list="' . $properties['list'] . '"';
        }
        if (in_array('pattern', $options) && !empty($properties['pattern'])) {
            $attributesString .= ' pattern="' . $properties['pattern'] . '"';
        }
        return $attributesString;
    }

    private function _currentUserCanOverride(): bool
    {
        $overrideRights = $this->row['f_properties']['overrideRights'] ?? [];
        foreach ($overrideRights as $overrideRight) {
            if (current_user_can($overrideRight)) {
                return true;
            }
        }
        return false;
    }
    #endregion
}
