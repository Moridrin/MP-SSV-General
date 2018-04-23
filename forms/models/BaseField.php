<?php

namespace mp_ssv_general\forms\models;

use mp_ssv_general\base\models\Model;

if (!defined('ABSPATH')) {
    exit;
}

class BaseField extends Model
{
    #region Class
    private const TABLE = 'ssv_shared_base_fields';

    public static function find(int $id): BaseField
    {
        $row = parent::doFind(self::TABLE, "id = $id");
        if ($row === null) {
            return null;
        } else {
            return new BaseField($id, $row['bf_name'], $row['bf_properties']);
        }
    }

    public static function findByName(string $name, array $options = []): ?BaseField
    {
        $row = parent::doFind(self::TABLE, "bf_name = $name");
        if ($row === null) {
            return null;
        } else {
            return new BaseField($row['id'], $name, $row['bf_properties']);
        }
    }

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
    private $name;
    /** @var array */
    private $properties;

    private function __construct(int $id, string $name, array $properties)
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

    public function setName(string $name): BaseField
    {
        $this->name = $name;
        return $this;
    }

    public function setProperties(array $properties): BaseField
    {
        $this->properties = $properties;
        return $this;
    }

    public function setProperty(string $key, $value): BaseField
    {
        $this->properties[$key] = $value;
        return $this;
    }

    #endregion

    public function save(): bool
    {
        return $this->doSave(
            self::TABLE,
            [
                'bf_name'       => $this->name,
                'bf_properties' => $this->properties,
            ]
        );
    }

    public function getTableRow(): array
    {
        return [
            $this->name,
            $this->properties['type'],
            $this->properties['value'],
        ];
    }
    #endregion
}
