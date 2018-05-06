<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\base\SSV_Global;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\models\Form;
use mp_ssv_general\forms\models\FormField;
use mp_ssv_general\forms\models\SharedField;
use mp_ssv_general\forms\models\SiteSpecificField;

function mp_ssv_general_forms_save_field()
{
    $name       = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
    $properties = BaseFunctions::sanitize($_POST['properties'], 'text');
    $shared     = BaseFunctions::sanitize($_POST['shared'], 'bool');
    $formId     = BaseFunctions::sanitize($_POST['formId'], 'int');
    $type       = $shared ? 'shared' : ($formId === null ? 'siteSpecific' : 'formField');
    $id         = BaseFunctions::sanitize($_POST['id'], 'int');
    if (!array_key_exists('title', $properties)) {
        $properties['title'] = BaseFunctions::toTitle($name);
    }
    if ($id === null) {
        switch ($type) {
            case 'shared':
                $id = SharedField::create($name, $properties);
                break;
            case 'siteSpecific':
                $id = SiteSpecificField::create($name, $properties);
                break;
            case 'formField':
                $id = FormField::create($name, $properties, $formId);
                break;
        }
    }
    switch ($type) {
        case 'shared':
            $field = $id !== null ? SharedField::findById($id) : null;
            break;
        case 'siteSpecific':
            $field = $id !== null ? SiteSpecificField::findById($id) : null;
            break;
        case 'formField':
            $field = $id !== null ? FormField::findById($id) : null;
            break;
        default:
            SSV_Global::addError('Unknown type: ' . $type);
            return;
    }
    if ($field instanceof Field) {
        $field->setName($name)->setProperties($properties)->save();
    } else {
        switch ($type) {
            case 'shared':
                $id = SharedField::create($name, $properties);
                break;
            case 'siteSpecific':
                $id = SiteSpecificField::create($name, $properties);
                break;
            case 'formField':
                $id = FormField::create($name, $properties, $formId);
                break;
        }
    }
    wp_die(json_encode(['id' => $id]));
}

add_action('wp_ajax_mp_ssv_general_forms_save_field', 'mp_ssv_general_forms_save_field', 10, 0);

function mp_ssv_general_forms_delete_field()
{
    $id     = BaseFunctions::sanitize($_POST['id'], 'int');
    $shared = BaseFunctions::sanitize($_POST['shared'], 'bool');
    $formId = BaseFunctions::sanitize($_POST['formId'], 'int');
    $type   = $shared ? 'shared' : ($formId ? 'formField' : 'siteSpecific');
    switch ($type) {
        case 'shared':
            SharedField::deleteByIds([$id]);
            break;
        case 'siteSpecific':
            SiteSpecificField::deleteByIds([$id]);
            break;
        case 'formField':
            FormField::deleteByIds([$id]);
            break;
    }
    wp_die();
}

add_action('wp_ajax_mp_ssv_general_forms_delete_field', 'mp_ssv_general_forms_delete_field');

function mp_ssv_general_forms_delete_form()
{
    $id = BaseFunctions::sanitize($_POST['id'], 'int');
    Form::deleteByIds([$id]);
    wp_die();
}

add_action('wp_ajax_mp_ssv_general_forms_delete_form', 'mp_ssv_general_forms_delete_form');

function mp_ssv_general_forms_delete_forms()
{
    Form::deleteByIds(BaseFunctions::sanitize($_POST['formIds'], 'int'));
}

add_action('wp_ajax_mp_ssv_general_forms_delete_forms', 'mp_ssv_general_forms_delete_forms');
