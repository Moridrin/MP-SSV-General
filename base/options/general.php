<?php
namespace mp_ssv_users\options;

use mp_ssv_general\BaseFunctions;
use mp_ssv_general\User;

if (!defined('ABSPATH')) {
    exit;
}

$columns = array(
    'disabled',
    'required',
    'placeholder',
    'override_right',
    'class',
    'style',
);

if (BaseFunctions::isValidPOST(BaseFunctions::OPTIONS_ADMIN_REFERER)) {
    if (isset($_POST['reset'])) {
        BaseFunctions::resetOptions();
    } else {
        $customFieldFields = isset($_POST['columns']) ? BaseFunctions::sanitize($_POST['columns'], $columns) : array();
        User::getCurrent()->updateMeta(BaseFunctions::USER_OPTION_CUSTOM_FIELD_FIELDS, json_encode($customFieldFields), false);
    }
}
?>
<form method="post" action="#">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="columns">Customizer Columns</label>
            </th>
            <td>
                <?php
                $selected = json_decode(User::getCurrent()->getMeta(BaseFunctions::USER_OPTION_CUSTOM_FIELD_FIELDS, json_encode(array('display', 'default', 'placeholder'))));
                $selected = $selected ?: array();
                ?>
                <select id="columns" size="<?= count($columns) ?>" name="columns[]" multiple>
                    <?php
                    foreach ($columns as $field) {
                        ?>
                        <option value="<?= $field ?>" <?= in_array($field, $selected) ? 'selected' : '' ?>>
                            <?= $field ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <?= BaseFunctions::getFormSecurityFields(BaseFunctions::OPTIONS_ADMIN_REFERER, true, 'Reset Preference'); ?>
</form>

