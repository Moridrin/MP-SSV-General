<?php
namespace mp_ssv_general\custom_fields;
use Exception;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 6-1-17
 * Time: 6:38
 */
class TabField extends Field
{
    const FIELD_TYPE = 'tab';

    public $fields;
    public $name;

    /**
     * TabField constructor.
     *
     * @param int     $id
     * @param string  $title
     * @param string  $class
     * @param string  $style
     * @param Field[] $fields
     */
    protected function __construct($id, $title, $class, $style, $fields = array())
    {
        parent::__construct($id, $title, self::FIELD_TYPE, $class, $style);
        $this->fields = $fields;
        $this->name = strtolower(str_replace(' ', '_', $title));
    }

    /**
     * @param int   $id
     * @param Field $field
     */
    public function addField($id, $field)
    {
        $this->fields[$id] = $field;
    }

    /**
     * @param string $json
     *
     * @return TabField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        if ($values->field_type != self::FIELD_TYPE) {
            throw new Exception('Incorrect field type');
        }
        $fields = array();
        if (isset($values->fields)) {
            foreach ($values->fields as $field) {
                $fields[] = Field::fromJSON(json_encode($field));
            }
        }
        return new TabField(
            $values->id,
            $values->title,
            $values->class,
            $values->style,
            $fields
        );
    }

    /**
     * @param bool $encode
     *
     * @return string the class as JSON object.
     */
    public function toJSON($encode = true)
    {
        $jsonFields = array();
        foreach ($this->fields as $field) {
            $jsonFields[] = $field->toJSON(false);
        }
        $values = array(
            'id'         => $this->id,
            'title'      => $this->title,
            'field_type' => $this->fieldType,
            'class'      => $this->class,
            'style'      => $this->style,
            'fields'     => $jsonFields,
        );
        if ($encode) {
            $values = json_encode($values);
        }
        return $values;
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $activeClass = isset($_POST['tab']) && $_POST['tab'] == $this->id ? 'class="active"' : '';
        $class       = !empty($this->class) ? 'class="tab ' . $this->class . '"' : 'class="tab ' . $activeClass . '"';
        $style       = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        ob_start();
        ?>
        <li <?= $class ?> <?= $style ?>><a href="#<?= $this->name ?>" <?= $activeClass ?>><?= $this->title ?></a></li>
        <?php
        return ob_get_clean();
    }

    public function getFieldsHTML()
    {
        ob_start();
        ?>
        <input type="hidden" name="tab" value="<?= $this->id ?>">
        <div id="<?= $this->name ?>">
            <?php foreach ($this->fields as $field): ?>
                <?= $field->getHTML() ?>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
