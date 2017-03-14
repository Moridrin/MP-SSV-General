<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 6-1-17
 * Time: 6:38
 */
class LabelField extends Field
{
    const FIELD_TYPE = 'label';

    /** @var string $text */
    public $text;

    /**
     * TabField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $text
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $text, $class, $style)
    {
        parent::__construct($id, $title, self::FIELD_TYPE, $class, $style);
        $this->text  = $text;
        $this->class = $class;
        $this->style = $style;
    }

    /**
     * @param string $json
     *
     * @return LabelField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        if ($values->field_type != self::FIELD_TYPE) {
            throw new Exception('Incorrect field type');
        }
        return new LabelField(
            $values->id,
            $values->title,
            $values->text,
            $values->class,
            $values->style
        );
    }

    /**
     * @param bool $encode
     *
     * @return string the class as JSON object.
     */
    public function toJSON($encode = true)
    {
        $values = array(
            'id'         => $this->id,
            'title'      => $this->title,
            'field_type' => $this->fieldType,
            'text'       => $this->text,
            'class'      => $this->class,
            'style'      => $this->style,
        );
        if ($encode) {
            $values = json_encode($values);
        }
        return $values;
    }

    /**
     * @param null $overrideRight string with the right needed to override required and disabled.
     *
     * @return string the field as HTML object.
     */
    public function getHTML($overrideRight = null)
    {
        $class = !empty($this->class) ? 'class="' . esc_html($this->class) . '"' : '';
        $style = !empty($this->style) ? 'style="' . esc_html($this->style) . '"' : '';
        ob_start();
        ?>
        <p <?= $class ?> <?= $style ?>><?= esc_html($this->text) ?></p><br/>
        <?php
        return ob_get_clean();
    }
}
