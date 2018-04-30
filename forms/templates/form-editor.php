<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Form;
use mp_ssv_general\forms\models\Forms;
use mp_ssv_general\forms\models\SharedField;
use mp_ssv_general\forms\models\SiteSpecificField;
use mp_ssv_general\forms\models\WordPressField;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'customized-form-fields-table.php';

function show_form_editor(Form $form)
{
    $sharedFields = SharedField::getAll();
    $siteSpecificFields = SiteSpecificField::getAll();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Add New Form</h1>
        <hr class="wp-header-end">
        <form action="admin.php?page=ssv_forms" method="post">
            <div id="poststuff">
                <div id="post-body" class="columns-2">
                    <div id="post-body-content" style="position: relative;">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label id="title-prompt-text" for="title">Enter title here</label>
                                <input type="text" name="form_title" size="30" value="<?= BaseFunctions::escape($form->getTitle(), 'attr') ?>" id="title" spellcheck="true" autocomplete="off" required="required">
                                <input type="hidden" name="form_tag" value="[ssv-form-<?= BaseFunctions::escape($form->getId(), 'attr') ?>]">
                                <input type="hidden" id="form_id" name="form_id" value="<?= BaseFunctions::escape($form->getId(), 'attr') ?>">
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div class="meta-box-sortables ui-sortable" style="">
                            <div id="submitdiv" class="postbox ">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Publish</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle" style="cursor: pointer;"><span>Publish</span></h2>
                                <div class="inside">
                                    <div class="submitbox" id="submitpost">
                                        <div id="major-publishing-actions" style="background: #ffffff;">
                                            <div id="delete-action">
                                                <a class="submitdelete deletion" href="#">Move to Trash</a>
                                            </div>
                                            <div id="publishing-action">
                                                <?= BaseFunctions::getAdminFormSecurityFields(SSV_Forms::ADMIN_REFERER, false, false); ?>
                                                <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="<?= empty($title) ? 'Publish' : 'Update' ?>">
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="postbox">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: WordPress User Fields</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle" style="cursor: pointer;"><span>WordPress User Fields</span></h2>
                                <div class="inside">
                                    <ul id="wordPressBaseFieldsList">
                                        <?php foreach (WordPressField::getAll() as $wordPressBaseField): ?>
                                            <li class="baseField" draggable="true" data-field='<?= json_encode($wordPressBaseField->getProperties()) ?>' data-type="Input" data-list="wordpress">
                                                <span><strong><?= BaseFunctions::escape($wordPressBaseField->getName(), 'html') ?></strong></span>
                                                <span style="float: right"><?= BaseFunctions::escape($wordPressBaseField->getProperty('type'), 'html') ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="postbox">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: SSV Shared Forms Fields</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle" style="cursor: pointer;"><span>SSV Shared Forms Fields</span></h2>
                                <div class="inside">
                                    <ul id="sharedBaseFieldsList">
                                        <?php if (!empty($sharedFields)): ?>
                                            <?php foreach ($sharedFields as $sharedBaseField): ?>
                                                <li class="baseField" draggable="true" data-field='<?= json_encode($sharedBaseField->getProperties()) ?>' data-type="Input" data-list="wordpress">
                                                    <span><strong><?= BaseFunctions::escape($sharedBaseField->getName(), 'html') ?></strong></span>
                                                    <span style="float: right"><?= BaseFunctions::escape($sharedBaseField->getProperty('type'), 'html') ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li style="margin: 13px;">There are no base fields. <a href="admin.php?page=ssv_forms_fields_manager">Click Here</a> to create one.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="postbox">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: SSV Site Specific Forms Fields</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle" style="cursor: pointer;"><span>SSV Site Specific Forms Fields</span></h2>
                                <div class="inside">
                                    <ul id="siteSpecificBaseFieldsList">
                                        <?php if (!empty($siteSpecificFields)): ?>
                                            <?php foreach ($siteSpecificFields as $siteSpecificBaseField): ?>
                                                <li class="baseField" draggable="true" data-field='<?= json_encode($siteSpecificBaseField->getProperties()) ?>' data-type="Input" data-list="wordpress">
                                                    <span><strong><?= BaseFunctions::escape($siteSpecificBaseField->getName(), 'html') ?></strong></span>
                                                    <span style="float: right"><?= BaseFunctions::escape($siteSpecificBaseField->getProperty('type'), 'html') ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li style="margin: 13px;">There are no base fields. <a href="admin.php?page=ssv_forms_fields_manager">Click Here</a> to create one.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-2" class="postbox-container">
                        <div class="meta-box-sortables ui-sortable">
                            <div id="postform" class="postbox">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Form</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle" style="cursor: pointer;"><span>Form</span></h2>
                                <div class="inside" style="margin: 0; padding: 0;">
                                    <?php
                                    show_customized_form_fields_table($form->getFields());
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br class="clear">
                </div>
            </div>
        </form>
    </div>
    <?php
}
