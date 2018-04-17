<?php

use mp_ssv_general\base\BaseFunctions;
use mp_ssv_general\forms\models\Forms;
use mp_ssv_general\forms\SSV_Forms;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'customized-form-fields-table.php';

function show_form_editor(int $id, string $title, array $sharedBaseFields, array $siteSpecificBaseFields, array $formFields = [])
{
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
                                <input type="text" name="form_title" size="30" value="<?= $title ?>" id="title" spellcheck="true" autocomplete="off" required="required">
                                <input type="hidden" name="form_tag" value="[ssv-form-<?= $id ?>]">
                                <input type="hidden" id="form_id" name="form_id" value="<?= $id ?>">
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
                                                <?= BaseFunctions::getAdminFormSecurityFields(SSV_Forms::EDIT_FORM_ADMIN_REFERER, false, false); ?>
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
                                        <?php foreach (Forms::getWordPressBaseFields() as $wordPressBaseField): ?>
                                            <li class="baseField" draggable="true" data-field='<?= json_encode($wordPressBaseField) ?>' data-type="Input" data-list="wordpress">
                                                <span><strong><?= $wordPressBaseField->bf_title ?></strong></span>
                                                <span style="float: right"><?= $wordPressBaseField->bf_inputType ?></span>
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
                                        <?php if (!empty($sharedBaseFields)): ?>
                                            <?php foreach ($sharedBaseFields as $sharedBaseField): ?>
                                                <?php $properties = json_decode($sharedBaseField->bf_properties); ?>
                                                <li class="baseField" draggable="true" data-field='<?= $sharedBaseField->bf_properties ?>' data-type="Input" data-list="shared">
                                                    <span><strong><?= $sharedBaseField->bf_name ?></strong></span>
                                                    <span style="float: right"><?= $properties->type ?></span>
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
                                        <?php if (!empty($siteSpecificBaseFields)): ?>
                                            <?php foreach ($siteSpecificBaseFields as $siteSpecificBaseField): ?>
                                                <li class="baseField" draggable="true" data-field='<?= json_encode($siteSpecificBaseField) ?>' data-type="Input" data-list="siteSpecific">
                                                    <span><strong><?= $siteSpecificBaseField->bf_title ?></strong></span>
                                                    <span style="float: right"><?= $siteSpecificBaseField->bf_inputType ?></span>
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
                                    show_customized_form_fields_table($id, $formFields);
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
