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
class HeaderField extends Field
{
    const FIELD_TYPE = 'header';

    /**
     * HeaderField constructor.
     *
     * @param int    $order
     * @param string $title
     * @param string $class
     * @param string $style
     */
    protected function __construct($containerID, $order, $title, $class, $style)
    {
        parent::__construct($containerID, $order, $title, self::FIELD_TYPE, $class, $style);
    }

    /**
     * @param string $json
     *
     * @return HeaderField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        if ($values->field_type != self::FIELD_TYPE) {
            throw new Exception('Incorrect field type');
        }
        return new HeaderField(
            $values->container_id,
            $values->order,
            isset($values->title) ? $values->title : 'test',
            $values->class,
            $values->style
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
        );
        $values = json_encode($values);
        return $values;
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $class = !empty($this->class) ? 'class="' . esc_html($this->class) . '"' : '';
        $style = !empty($this->style) ? 'style="' . esc_html($this->style) . '"' : '';
        ob_start();
        ?>
        <h2 <?= $class ?> <?= $style ?>><?= esc_html($this->title) ?></h2>
        <?php
        return ob_get_clean();
    }
}
