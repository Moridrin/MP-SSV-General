<?php

namespace mp_general\forms\models;

use DateTime;
use mp_general\base\BaseFunctions;
use mp_general\base\models\Model;
use mp_general\base\models\User;
use mp_general\base\SSV_Global;
use mp_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Field extends Model
{
    #region Class
    private $oldName = null;

    public const INPUT_ATTRIBUTES = [
        'type'         => [
            'type'    => 'text',
            'default' => '',
        ],
        'required'     => [
            'type'    => 'bool',
            'default' => false,
        ],
        'disabled'     => [
            'type'    => 'bool',
            'default' => false,
        ],
        'checked'      => [
            'type'    => 'bool',
            'default' => false,
        ],
        'value'        => [
            'type'    => 'text',
            'default' => '',
        ],
        'multiple'     => [
            'type'    => 'bool',
            'default' => false,
        ],
        'size'         => [
            'type'    => 'int',
            'default' => 1,
        ],
        'autocomplete' => [
            'type'    => 'bool',
            'default' => true,
        ],
        'placeholder'  => [
            'type'    => 'text',
            'default' => '',
        ],
        'pattern'      => [
            'type'    => 'text',
            'default' => '',
        ],
    ];

    public static function create(string $name, array $properties = []): ?int
    {
        return parent::_create(['f_name' => strtolower($name), 'f_properties' => json_encode($properties)]);
    }

    /**
     * @param string $orderBy
     * @param string $order
     * @param string $key
     * @return Field[]
     */
    public static function getAll(string $orderBy = 'id', string $order = 'ASC', string $key = 'f_name'): array
    {
        $fields = SiteSpecificField::getAll($orderBy, $order, $key);
        $fields += SharedField::getAllExcept($fields, $orderBy, $order, $key);
        $fields += WordPressField::getAllExcept($fields, $orderBy, $order, $key);
        ksort($fields);
        return $fields;
    }

    public static function getAllExcept(array $except, string $orderBy = 'id', string $order = 'ASC', string $key = 'f_name'): array
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
            return self::_getAll($orderBy, $order, $key);
        } else {
            $names   = implode('\', \'', $names);
            $results = self::_find("f_name NOT IN ('$names')", $orderBy, $order);
            if ($results === null) {
                return [];
            }
            $fields = [];
            foreach ($results as $row) {
                $fields[$row['f_name']] = new static($row);
            }
            return $fields;
        }
    }
    #endregion

    #region Instance
    public static function getTableColumns(): array
    {
        return [
            'f_name'             => 'Name',
            'f_properties/type'  => 'Input Type',
            'f_properties/value' => 'Value',
        ];
    }

    protected static function _getDatabaseFields(): array
    {
        return ['`f_name` VARCHAR(50)', '`f_properties` TEXT NOT NULL'];
    }

    #region getters & setters
    public function getName(): string
    {
        return $this->row['f_name'];
    }

    public function setName(string $name): self
    {
        $name = strtolower($name);
        if ($this->oldName === null && $name !== $this->row['f_name']) {
            $this->oldName = $this->row['f_name'];
        }
        $this->row['f_name'] = $name;
        return $this;
    }

    public function setProperty(string $key, $value): self
    {
        $this->row['f_properties'][$key] = $value;
        return $this;
    }

    public function setProperties(array $properties): self
    {
        $this->row['f_properties'] = $properties;
        return $this;
    }

    public function getTableRow(): array
    {
        return [
            'f_name'             => $this->row['f_name'],
            'f_properties/type'  => $this->row['f_properties']['type'],
            'f_properties/value' => $this->row['f_properties']['value'],
        ];
    }

    public function getRowActions(): array
    {
        return [
            [
                'spanClass' => 'inline',
                'onclick'   => 'fieldsManager.edit(\'' . $this->getId() . '\')',
                'linkClass' => 'editinline',
                'linkText'  => 'Edit',
            ],
            [
                'spanClass' => 'inline',
                'onclick'   => 'fieldsManager.customize(\'' . $this->getId() . '\')',
                'linkClass' => 'editinline',
                'linkText'  => 'Customize',
            ],
            [
                'spanClass' => 'trash',
                'onclick'   => 'fieldsManager.deleteRow(\'' . $this->getId() . '\')',
                'linkClass' => 'submitdelete',
                'linkText'  => 'Trash',
            ],
        ];
    }

    public function getData(): array
    {
        return $this->getProperties() + ['name' => $this->getName()];
    }

    public function getProperties(): array
    {
        return $this->row['f_properties'];
    }

    #endregion

    public function getElementAttributesString(string $element, array $attributes = [], string $nameSuffix = null): string
    {
        $properties             = $this->getProperties();
        // $properties             += [
        //     'type'      => 'text',
        //     'classes'        => [],
        //     'styles'         => [],
        //     'overrideRights' => [],
        //     'required'       => false,
        //     'disabled'       => false,
        //     'checked'        => false,
        //     'value'          => null,
        //     'autocomplete'   => null,
        //     'placeholder'    => null,
        //     'list'           => null,
        //     'pattern'        => null,
        //     'multiple'       => false,
        //     'selected'       => false,
        //     'profileField'   => true,
        //     'size'           => 1,
        // ];
        $currentUserCanOverride = $this->_currentUserCanOverride();
        $attributesString       = 'id="' . BaseFunctions::escape($properties['form_id'] . '_' . $element . '_' . $this->getName(), 'attr') . '"';
        if (in_array('type', $attributes)) {
            $attributesString .= ' type="' . $properties['type'] . '"';
        }
        if (isset($properties['classes'][$element]) && !empty($properties['classes'][$element])) {
            $attributesString .= ' class="' . BaseFunctions::escape($properties['classes'][$element], 'attr', ' ') . '"';
        }
        if (isset($properties['styles'][$element]) && !empty($properties['styles'][$element])) {
            $attributesString .= ' style="' . BaseFunctions::escape($properties['styles'][$element], 'attr', ' ') . '"';
        }
        if ($nameSuffix !== null) {
            $attributesString .= ' name="' . BaseFunctions::escape($this->getName() . $nameSuffix, 'attr') . '"';
        }
        if (!$currentUserCanOverride && in_array('required', $attributes) && $properties['required']) {
            $attributesString .= $properties['required'] ? 'required="required"' : '';
        }
        if (!$currentUserCanOverride && in_array('disabled', $attributes) && $properties['disabled']) {
            $attributesString .= disabled($properties['disabled'], true, false);
        }
        if (in_array('checked', $attributes) && $properties['checked']) {
            $attributesString .= checked($properties['checked'], true, false);
        }
        if (in_array('value', $attributes)) {
            $profileValue = User::getCurrent()->getMeta($this->getName());
            if (!empty($properties['value'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($properties['value'], 'attr') . '"';
            } elseif (!empty($profileValue)) {
                $attributesString .= ' value="' . BaseFunctions::escape($profileValue, 'attr') . '"';
            } elseif (!empty($properties['defaultValue'])) {
                $attributesString .= ' value="' . BaseFunctions::escape($properties['defaultValue'], 'attr') . '"';
            }
        }
        if (in_array('multiple', $attributes) && $properties['multiple']) {
            $attributesString .= ' multiple="multiple"';
        }
        if (in_array('size', $attributes) && $properties['size'] > 1) {
            $attributesString .= ' size="' . BaseFunctions::escape($properties['size'], 'attr') . '"';
        }
        if (in_array('for', $attributes)) {
            $attributesString .= ' for="' . BaseFunctions::escape($properties['form_id'] . '_' . 'input_' . $this->getName(), 'attr') . '"';
        }
        if (in_array('autocomplete', $attributes) && !empty($properties['autocomplete'])) {
            $attributesString .= ' autocomplete="' . $properties['autocomplete'] . '"';
        }
        if (in_array('placeholder', $attributes) && !empty($properties['placeholder'])) {
            $attributesString .= ' placeholder="' . $properties['placeholder'] . '"';
        }
        if (in_array('list', $attributes) && !empty($properties['list'])) {
            $attributesString .= ' list="' . $properties['list'] . '"';
        }
        if (in_array('pattern', $attributes) && !empty($properties['pattern'])) {
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

    public function equals($object): bool
    {
        if (is_array($object)) {
            foreach ($object as $name => $value) {
                if (!$this->hasProperty($name) || $this->getProperty($name) !== $value) {
                    return false;
                }
            }
            return true;
        } elseif ($object instanceof Field) {
            foreach ($object->row as $name => $value) {
                if (!isset($this->row[$name]) || $this->row[$name] !== $value) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function hasProperty(string $key)
    {
        return isset($this->row['f_properties'][$key]);
    }

    public function getProperty(string $key, string $sanitize = 'text')
    {
        if (!isset($this->row['f_properties'][$key])) {
            $this->row['f_properties'][$key] = null;
        }
        return BaseFunctions::sanitize($this->row['f_properties'][$key], $sanitize);
    }

    abstract public function getType(): string;

    protected function __init(): void
    {
        if (!is_array($this->row['f_properties'])) {
            $this->row['f_properties'] = json_decode($this->row['f_properties'], true);
        }
        if (isset($this->row['form_id'])) {
            $this->row['f_properties']['form_id'] = $this->row['form_id'];
        }
    }

    protected function _beforeSave(): bool
    {
        unset($this->row['f_properties']['name']);
        foreach (self::INPUT_ATTRIBUTES as $attribute => $properties) {
            $this->row['f_properties'][$attribute] = BaseFunctions::sanitize($this->row['f_properties'][$attribute] ?? $properties['default'], $properties['type']);
        }
        $this->row['f_properties'] = json_encode($this->row['f_properties']);
        if ($this->oldName !== null && $this->oldName !== $this->row['f_name']) {
            SSV_Global::addError('Cannot change the name of the field.<br/>Changing the name would disconnect the user data.');
            return false;
        }
        return true;
    }

    public function __toString(): string
    {
        ob_start();
        switch ($this->getProperty('type')) {
            case 'hidden':
                return $this->_getHiddenInputFieldHtml();
            case 'select':
                mp_ssv_show_select_input_field($this);
                break;
            case 'checkbox':
                mp_ssv_show_checkbox_input_field($this);
                break;
            case 'datetime':
                /** @noinspection PhpIncludeInspection */
                require_once SSV_Forms::PATH . 'templates/fields/datetime.php';
                show_datetime_input_field($this);
                break;
            default:
                \mp_ssv_show_default_input_field($this);
                break;
        }
        return ob_get_clean();
    }

    private function _getHiddenInputFieldHtml(): string
    {
        $this->row['f_properties'] += [
            'defaultValue' => '',
        ];
        if (strtolower($this->row['f_properties']['defaultValue']) === 'now') {
            $this->row['f_properties']['defaultValue '] = (new DateTime($this->row['f_properties']['defaultValue']))->format('Y-m-d');
        }
        $id = BaseFunctions::escape('input_' . $this->row['f_properties']['name'], 'attr');
        return '<input ' . Field::getElementAttributesString($id, ['type', 'value'], '') . '/>';
    }
    #endregion
}
