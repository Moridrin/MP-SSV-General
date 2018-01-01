<?php

use mp_ssv_forms\models\SSV_Forms;
use mp_ssv_forms\options\Forms;
use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'customized-form-fields-table.php';

function show_form_editor(int $id, array $baseFields)
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
                                <input type="text" name="form_title" size="30" value="" id="title" spellcheck="true" autocomplete="off">
                                <input type="hidden" name="form_tag" value="[ssv-form-<?= $id ?>]">
                                <input type="hidden" name="form_id" value="<?= $id ?>">
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
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
                                                <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Publish">
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
                                        <?php foreach (Forms::getWordPressBaseFields() as $baseField): ?>
                                            <li class="baseField" draggable="true" data-field='<?= json_encode($baseField) ?>' data-field-type="Input">
                                                <span><strong><?= $baseField->bf_title ?></strong></span>
                                                <span style="float: right"><?= $baseField->bf_inputType ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="postbox">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: SSV Forms Fields</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle" style="cursor: pointer;"><span>SSV Forms Fields</span></h2>
                                <div class="inside">
                                    <ul id="baseFieldsList">
                                        <?php if (!empty($baseFields)): ?>
                                            <?php foreach ($baseFields as $baseField): ?>
                                                <li class="baseField" draggable="true" data-field='<?= json_encode($baseField) ?>' data-field-type="Input">
                                                    <span><strong><?= $baseField->bf_title ?></strong></span>
                                                    <span style="float: right"><?= $baseField->bf_inputType ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li style="margin: 13px;">There are no base fields. <a href="admin.php?page=ssv_forms_base_fields_manager">Click Here</a> to create one.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-2" class="postbox-container">
                        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                            <div id="postform" class="postbox">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Form</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle" style="cursor: pointer;"><span>Form</span></h2>
                                <div class="inside" style="margin: 0; padding: 0;">
                                    <?php
                                    show_customized_form_fields_table([]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br class="clear">
                </div>
            </div>
            <?= BaseFunctions::getFormSecurityFields(SSV_Forms::EDIT_FORM_ADMIN_REFERER, false, false); ?>
        </form>
    </div>
    <?php
}
