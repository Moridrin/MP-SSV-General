<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Field extends Model
{
    #region Class
    final public static function findByName(string $name, int $formId = null): ?Field
    {
        $field = FormField::doFindByName($name, $formId);
        if ($field === null) {
            $field = SiteSpecificBaseField::doFindByName($name, $formId);
        }
        if ($field === null) {
            $field = SharedBaseField::doFindByName($name, $formId);
        }
        return $field;
    }

    abstract protected static function doFindByName(string $name, ?int $formId): ?Field;

    public static function getTableColumns(): array
    {
        return [
            'Name',
            'Input Type',
            'Value',
        ];
    }
    #endregion

    #region Instance
    /** @var string */
    protected $name;
    /** @var array */
    protected $properties;

    protected function __construct(int $id, string $name, array $properties)
    {
        parent::__construct($id);
        $this->name       = $name;
        $this->properties = $properties;
    }

    #region getters & setters
    public function getName(): string
    {
        return $this->name;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getProperty(string $key)
    {
        return $this->properties[$key];
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    public function setProperty(string $key, $value): self
    {
        $this->properties[$key] = $value;
        return $this;
    }

    #endregion

    public function getTableRow(): array
    {
        return [
            $this->name,
            $this->properties['type'],
            $this->properties['value'],
        ];
    }

    public function doSave(string $table, array $data): bool
    {
        $data += [
            'f_name'       => $this->name,
            'f_properties' => $this->properties,
        ];
        return parent::doSave($table, $data);
    }

    public function __toString()
    {
        switch ($this->properties['type'] ?? null) {
            case 'checkbox':
                show_checkbox_input_field($this->properties);
                break;
            case 'checkbox':
                show_checkbox_input_field($this->properties);
                break;
            default:
                show_default_input_field($this->properties);
        }
    }

    #endregion
}
