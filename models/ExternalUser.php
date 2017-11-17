<?php

namespace mp_ssv_general;


class ExternalUser extends User
{
    protected $lidnr;

    protected $pendingChanges = [];
    /**
     * User constructor.
     *
     * @param \WP_User $user the WP_User component used as base for the User
     */
    function __construct($user)
    {
        $this->lidnr = get_user_meta($user->ID, 'lidnr', true);
        parent::__construct($user);
    }

    #endregion

    #region update($inputFields)
    /**
     * This method updates all
     *
     * @param InputField[] $inputFields
     */
    public function update($inputFields)
    {
        $updateRequest = [];
        $map = ATMS_API::SSV_ATMS_FIELD_MAP;
        foreach ($inputFields as $field) {
            $name = $field->name;
            if (isset($map[$name])) {
                $name = $map[$name];
            }
            $updateRequest[$name] = $field->value;
        }
        ATMS_API::put('member' . $this->lidnr, $updateRequest);
    }
    #endregion

    #region updateMeta($meta_key, $value)
    /**
     * This function sets the metadata defined by the key (or an alias of that key).
     * The aliases are:
     *  - email, email_address, member_email => user_email
     *  - name => display_name
     *  - login, username, user_name => user_login
     * The function will also update the display name after the first or last name is updated.
     *
     * @param string $meta_key the key that defines which metadata to set.
     * @param string $value    the value to set.
     * @param bool   $sanitize set false if the value is already sanitized.
     *
     * @return bool|Message true if success, else it provides an object consisting of a message and a type (notification or error).
     */
    function updateMeta($meta_key, $value, $sanitize = true)
    {
        if (strpos($meta_key, 'password') !== false || strpos($meta_key, 'pwd') !== false) {
            return true;
        }
        if ($sanitize) {
            $value = SSV_General::sanitize($value, $meta_key);
        }
        if ($this->getMeta($meta_key) == $value) {
            return true;
        }
        if ($meta_key == "email" || $meta_key == "email_address" || $meta_key == "user_email" || $meta_key == "member_email") {
            wp_update_user(array('ID' => $this->ID, 'user_email' => $value));
            update_user_meta($this->ID, 'user_email', $value);
            $this->user_email = $value;
        } elseif ($meta_key == "login" || $meta_key == "username" || $meta_key == "user_name" || $meta_key == "user_login") {
            return new Message('Cannot change the user-login.', Message::ERROR_MESSAGE); //cannot change user_login
        }
        $map = ATMS_API::SSV_ATMS_FIELD_MAP;
        if (isset($map[$meta_key])) {
            $meta_key = $map[$meta_key];
        }
        $this->pendingChanges[$meta_key] = $value;
    }
    #endregion

    #region getMeta($meta_key, $default)
    /**
     * This function returns the metadata associated with the given key (or an alias of that key).
     * The aliases are:
     *  - email, email_address, member_email => user_email
     *  - name => display_name
     *  - login, username, user_name => user_login
     *
     * @param string $meta_key defines which metadata should be returned.
     * @param mixed  $default  is the value returned if there is no value associated with the key.
     *
     * @return string the value associated with the key or the default value if there is no value associated with the key.
     */
    function getMeta($meta_key, $default = '')
    {
        try {
            $userData = ATMS_API::cache('member/' . $this->lidnr . '?with_updates=1');
        } catch (\Exception $e) {
            return $default;
        }
        if ($meta_key == "login" || $meta_key == "username" || $meta_key == "user_name" || $meta_key == "user_login") {
            return $this->user_login;
        }
        if (stristr($meta_key, 'emergency')) {
            $meta_key = str_replace('emergency_', '', $meta_key);
            if (count($userData['emergency_contacts']) === 0) {
                return $default;
            }
            $userData = $userData['emergency_contacts'][0];
        }
        $map = ATMS_API::SSV_ATMS_FIELD_MAP;
        if (isset($map[$meta_key])) {
            $meta_key = ATMS_API::SSV_ATMS_FIELD_MAP[$meta_key];
        }
        if (isset($userData[$meta_key])) {
            return $userData[$meta_key];
        }
        $value = get_user_meta($this->ID, $meta_key, true);
        return $value ?: $default;
    }
    #endregion

    #region commitChanges()
    /**
     * Commit all pending changes to the database or API
     */
    public function commitChanges()
    {
        ATMS_API::put('member/' . $this->lidnr, $this->pendingChanges);
        ATMS_API::deleteCache('member/' . $this->lidnr. '?with_updates=1');
        return true;
    }
    #endregion
}