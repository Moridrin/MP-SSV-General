<?php

use mp_ssv_general\base\BaseFunctions;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'customized-form-fields-table.php';

function show_form_editor(array $baseFields)
{
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Add New Form</h1>
        <hr class="wp-header-end">
        <form action="admin.php?page=ssv_forms_forms_manager" method="post">
            <div id="poststuff">
                <div id="post-body" class="columns-2">
                    <div id="post-body-content" style="position: relative;">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label id="title-prompt-text" for="title">Enter title here</label>
                                <input type="text" name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
                            <div id="postfields" class="postbox ">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Fields</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle"><span>Fields</span></h2>
                                <div class="inside">
                                    <ul id="baseFieldsList">
                                        <?php foreach ($baseFields as $baseField): ?>
                                            <li class="baseField" draggable="true" data-field='<?= json_encode($baseField) ?>' data-field-type="Input"><span><strong><?= $baseField->bf_title ?></strong></span><span style="float: right"><?= $baseField->bf_inputType ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-2" class="postbox-container">
                        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                            <div id="postform" class="postbox">
                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Form</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                                <h2 class="hndle ui-sortable-handle"><span>Form</span></h2>
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
        </form>
    </div>
    <?php
}
