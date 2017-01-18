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
     */
    protected function __construct($id, $title, $text)
    {
        parent::__construct($id, $title, self::FIELD_TYPE);
        $this->text = $text;
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
            $values->text
        );
    }

    /**
     * @return string the class as JSON object.
     */
    public function toJSON()
    {
        $values = array(
            'id'        => $this->id,
            'title'     => $this->title,
            'field_type' => $this->fieldType,
            'text'      => $this->text,
        );
        return json_encode($values);
    }
}
