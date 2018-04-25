<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\models\Form;
use mp_ssv_general\forms\models\FormField;
use mp_ssv_general\forms\models\SharedField;
use mp_ssv_general\forms\models\SiteSpecificField;

function mp_ssv_general_forms_save_field()
{
    ob_start();
    $name = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
    $properties = BaseFunctions::sanitize($_POST['properties'], 'text');
    $shared = BaseFunctions::sanitize($_POST['shared'], 'bool');
    $formId = BaseFunctions::sanitize($_POST['formId'], 'int');
    $type = $shared ? 'shared' : ($formId === null ? 'siteSpecific' : 'formField');
    $id = BaseFunctions::sanitize($_POST['id'], 'int');
    switch ($type) {
        case 'shared':
            $field = SharedField::findById($id);
            break;
        case 'siteSpecific':
            $field = SiteSpecificField::findById($id);
            break;
        case 'formField':
            $field = FormField::findById($id);
            break;
        default:
            $_SESSION['SSV']['errors'][] = 'Unknown type: '.$type;
            return;
    }
    if ($field instanceof Field) {
        $field
            ->setName($name)
            ->setProperties($properties)
            ->save()
        ;
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

function mp_ssv_general_forms_delete_fields()
{
    ob_start();
    $ids = BaseFunctions::sanitize($_POST['ids'], 'int');
    $shared = BaseFunctions::sanitize($_POST['shared'], 'bool');
    $formId = BaseFunctions::sanitize($_POST['formId'], 'int');
    $type = $shared ? 'shared' : ($formId ? 'formField' : 'siteSpecific');
    switch ($type) {
        case 'shared':
            SharedField::deleteByIds($ids);
            break;
        case 'siteSpecific':
            SiteSpecificField::deleteByIds($ids);
            break;
        case 'formField':
            FormField::deleteByIds($ids);
            break;
    }
    wp_die();
}

add_action('wp_ajax_mp_ssv_general_forms_delete_fields', 'mp_ssv_general_forms_delete_fields');

function mp_ssv_general_forms_delete_forms()
{
    Form::deleteByIds(BaseFunctions::sanitize($_POST['formIds'], 'int'));
}

add_action('wp_ajax_mp_ssv_general_forms_delete_forms', 'mp_ssv_general_forms_delete_forms');
