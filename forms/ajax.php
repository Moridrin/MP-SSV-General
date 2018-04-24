<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Field;
use mp_ssv_general\forms\models\Form;
use mp_ssv_general\forms\models\FormField;
use mp_ssv_general\forms\models\SharedField;
use mp_ssv_general\forms\models\SiteSpecificField;

function mp_ssv_general_forms_save_field()
{
    $name = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
    $properties = BaseFunctions::sanitize($_POST['properties'], 'text');
    $type = BaseFunctions::sanitize($_POST['type'], 'text');
    switch ($type) {
        case 'shared':
            $field = SharedField::findById(BaseFunctions::sanitize($_POST['id'], 'int'));
            break;
        case 'siteSpecific':
            $field = SiteSpecificField::findById(BaseFunctions::sanitize($_POST['id'], 'int'));
            break;
        case 'formField':
            $field = FormField::findById(BaseFunctions::sanitize($_POST['id'], 'int'));
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
                SharedField::create($name, $properties);
                break;
            case 'siteSpecific':
                SiteSpecificField::create($name, $properties);
                break;
            case 'formField':
                FormField::create($name, $properties);
                break;
        }
    }
}

function mp_ssv_general_forms_save_shared_field()
{
    $name = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
    $properties = BaseFunctions::sanitize($_POST['properties'], 'text');
    $field = SharedField::findById(BaseFunctions::sanitize($_POST['id'], 'int'));
    if ($field instanceof Field) {
        $field
            ->setName($name)
            ->setProperties($properties)
            ->save()
        ;
    } else {
        SharedField::create($name, $properties);
    }
}

function mp_ssv_general_forms_save_site_specific_field()
{
    $name = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
    $properties = BaseFunctions::sanitize($_POST['properties'], 'text');
    $field = SiteSpecificField::findById(BaseFunctions::sanitize($_POST['id'], 'int'));
    if ($field instanceof Field) {
        $field
            ->setName($name)
            ->setProperties($properties)
            ->save()
        ;
    } else {
        SiteSpecificField::create($name, $properties);
    }
}

function mp_ssv_general_forms_save_form_field()
{
    $name = BaseFunctions::sanitize($_POST['properties']['name'], 'text');
    $properties = BaseFunctions::sanitize($_POST['properties'], 'text');
    $field = FormField::findById(BaseFunctions::sanitize($_POST['id'], 'int'));
    if ($field instanceof Field) {
        $field
            ->setName($name)
            ->setProperties($properties)
            ->save()
        ;
    } else {
        FormField::create($name, $properties);
    }
}

add_action('wp_ajax_mp_ssv_general_forms_save_field', 'mp_ssv_general_forms_save_field', 10, 0);

function mp_ssv_general_forms_delete_fields()
{
    $ids = BaseFunctions::sanitize($_POST['ids'], 'int');
    switch (BaseFunctions::sanitize($_POST['type'], 'text')) {
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
}

add_action('wp_ajax_mp_ssv_general_forms_delete_fields', 'mp_ssv_general_forms_delete_fields');

function mp_ssv_general_forms_delete_forms()
{
    Form::deleteByIds(BaseFunctions::sanitize($_POST['formIds'], 'int'));
}

add_action('wp_ajax_mp_ssv_general_forms_delete_forms', 'mp_ssv_general_forms_delete_forms');
