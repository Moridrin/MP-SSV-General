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
     * @param int     $order
     * @param string  $title
     * @param string  $class
     * @param string  $style
     * @param Field[] $fields
     */
    protected function __construct($containerID, $order, $title, $class, $style, $fields = array())
    {
        parent::__construct($containerID, $order, $title, self::FIELD_TYPE, $class, $style);
        $this->fields = $fields;
        $this->name   = strtolower(str_replace(' ', '_', $title));
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
        for ($i = 1; $i <= $values->fieldCount; $i++) {
            $fields[] = Field::getByOrder($values->container_id, ($values->order + $i));
        }
        return new TabField(
            $values->container_id,
            $values->order,
            $values->title,
            $values->class,
            $values->style,
            $fields
        );
    }

    /**
     * @return string the class as JSON object.
     */
    public function toJSON()
    {
        $values = array(
            'container_id' => $this->containerID,
            'order'        => $this->order,
            'title'        => $this->title,
            'field_type'   => $this->fieldType,
            'class'        => $this->class,
            'style'        => $this->style,
            'fieldCount'   => count($this->fields),
        );
        $values = json_encode($values);
        return $values;
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $activeClass = isset($_POST['tab']) && $_POST['tab'] == $this->order ? 'active' : '';
        $class       = !empty($this->class) ? 'class="tab ' . esc_html($this->class) . '"' : 'class="tab ' . esc_html($activeClass) . '"';
        $style       = !empty($this->style) ? 'style="' . esc_html($this->style) . '"' : '';
        ob_start();
        ?>
        <li <?= $class ?> <?= $style ?>><a href="#<?= esc_html($this->name) ?>"><?= esc_html($this->title) ?></a></li>
        <?php
        return ob_get_clean();
    }
}
