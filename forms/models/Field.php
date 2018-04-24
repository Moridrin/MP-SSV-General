<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Field extends Model
{
    #region Class
    public static function create(string $name, array $properties = []): ?Field
    {
        $id = parent::doCreate(['f_name' => $name, 'f_properties' => json_encode($properties)]);
        if ($id === null) {
            return null;
        }
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return static::findById($id);
    }

    final public static function findByName(string $name, ?int $formId = null): ?Field
    {
        // Form Field
        $row = parent::doFindRow('f_name = '.$name.' AND form_id = '.$formId);
        if ($row !== null) {
            return new FormField($row);
        }

        // Site Specific Field
        $row = parent::doFindRow('f_name = '.$name);
        if ($row !== null) {
            return new SiteSpecificField($row);
        }

        // Shared Field
        $row = parent::doFindRow('f_name = '.$name);
        if ($row !== null) {
            return new SharedField($row);
        }

        return null;
    }

    public static function getTableColumns(): array
    {
        return [
            'Name',
            'Input Type',
            'Value',
        ];
    }

    protected static function getDatabaseFields(): array
    {
        return ['`f_name` VARCHAR(50)', '`f_properties` TEXT NOT NULL'];
    }
    #endregion

    #region Instance
    private $oldName = null;

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
            $this->row['f_name'],
            $this->row['f_properties']['type'],
            $this->row['f_properties']['value'],
        ];
    }
    #endregion
}
