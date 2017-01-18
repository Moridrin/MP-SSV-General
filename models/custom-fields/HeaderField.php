<?php

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
*@param int          $id
     * @param string $title
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $class, $style)
    {
        parent::__construct($id, $title, self::FIELD_TYPE, $class, $style);
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
            $values->id,
            $values->title,
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
            'id'         => $this->id,
            'title'      => $this->title,
            'field_type' => $this->fieldType,
            'class'      => $this->class,
            'style'      => $this->style,
        );
        return json_encode($values);
    }

    /**
     * @return string the field as HTML object.
     */
    public function getHTML()
    {
        $class = !empty($this->class) ? 'class="' . $this->class . '"' : '';
        $style = !empty($this->style) ? 'style="' . $this->style . '"' : '';
        ob_start();
        ?>
        <h2 <?= $class ?> <?= $style ?>><?= $this->title ?></h2>
        <?php
        return ob_get_clean();
    }
}
