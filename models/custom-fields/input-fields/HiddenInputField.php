<?php
namespace mp_ssv_general\custom_fields\input_fields;
use DateTime;
use Exception;
use mp_ssv_general\custom_fields\InputField;
use mp_ssv_general\Message;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 10-1-17
 * Time: 12:03
 */
class HiddenInputField extends InputField
{
    const INPUT_TYPE = 'hidden';

    /** @var string $defaultValue */
    public $defaultValue;

    /**
     * HiddenInputField constructor.
     *
     * @param int    $id
     * @param string $title
     * @param string $inputType
     * @param string $name
     * @param string $defaultValue
     * @param string $class
     * @param string $style
     */
    protected function __construct($id, $title, $inputType, $name, $defaultValue, $class, $style)
    {
        parent::__construct($id, $title, $inputType, $name, $class, $style);
        $this->defaultValue = $defaultValue;
        if ($this->defaultValue == 'NOW') {
            $this->value = (new DateTime('NOW'))->format('Y-m-d');
        } else {
            $this->value = $this->defaultValue;
        }
    }

    /**
     * @param string $json
     *
     * @return HiddenInputField
     * @throws Exception
     */
    public static function fromJSON($json)
    {
        $values = json_decode($json);
        return new HiddenInputField(
            $values->id,
            $values->title,
            $values->input_type,
            $values->name,
            $values->default_value,
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
            'id'            => $this->id,
            'title'         => $this->title,
            'field_type'    => $this->fieldType,
            'input_type'    => $this->inputType,
            'name'          => $this->name,
            'default_value' => $this->defaultValue,
            'class'         => $this->class,
            'style'         => $this->style,
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
        $name  = 'name="' . $this->name . '"';
        $value = 'value="' . $this->defaultValue . '"';
        $class = !empty($this->class) ? 'class="' . $this->class . '"' : '';
        $style = !empty($this->style) ? 'style="' . $this->style . '"' : '';

        ob_start();
        if (current_theme_supports('materialize')) {
            ?>
            <input type="hidden" <?= $name ?> <?= $value ?> <?= $class ?> <?= $style ?> />
            <?php
        }

        return trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
    }

    /**
     * @return Message[]|bool array of errors or true if no errors.
     */
    public function isValid()
    {
        return true;
    }
}
