<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Created by: Jeroen Berkvens
 * Date: 23-4-2016
 * Time: 14:48
 */
class SSV_User extends \WP_User
{
    #region Get User
    #region construct
    /**
     * SSV_User constructor.
     *
     * @param \WP_User $user the WP_User component used as base for the SSV_User
     */
    function __construct($user)
    {
        parent::__construct($user);
    }
    #endregion

    #region By ID
    /**
     * This function searches for a SSV_User by its ID.
     *
     * @param int $id is the ID used to find the SSV_User
     *
     * @return SSV_User|null returns the SSV_User it found or null if it can't find one.
     */
    public static function getByID($id)
    {
        if ($id == null) {
            return null;
        }
        return new SSV_User(get_user_by('id', $id));
    }
    #endregion

    #region Current
    public static function getCurrent()
    {
        if (!is_user_logged_in()) {
            return null;
        }
        return new SSV_User(wp_get_current_user());
    }
    #endregion
    #endregion

    #region register($user, $password, $email)
    /**
     * @param $username
     * @param $password
     * @param $email
     *
     * @return Message|null|SSV_User
     */
    public static function register($username, $password, $email)
    {
        if (empty($username) || empty($password) || empty($email)) {
            return new Message('You cannot register without Username, Password and Email.', Message::ERROR_MESSAGE);
        }
        if (username_exists($username)) {
            return new Message('This username already exists.', Message::ERROR_MESSAGE);
        }
        if (email_exists($email)) {
            return new Message('This email address already exists. Try resetting your password.', Message::ERROR_MESSAGE);
        }
        $id = wp_create_user(
            sanitize_text_field($username),
            sanitize_text_field($password),
            sanitize_text_field($email)
        );

        return self::getByID($id);
    }
    #endregion

    #region isCurrentUser()
    /**
     * @return bool returns true if this is the current user.
     */
    public function isCurrentUser()
    {
        if ($this->ID == wp_get_current_user()->ID) {
            return true;
        } else {
            return false;
        }
    }
    #endregion

    #region isBoard()
    /**
     * @return bool true if this user has the board role (and can edit other member profiles).
     */
    public function isBoard()
    {
        return in_array(get_option(SSV_General::OPTION_BOARD_ROLE), $this->roles);
    }
    #endregion

    #region checkPassword($password)
    /**
     * @param string $password The plaintext new user password
     *
     * @return bool false, if the $password does not match the member's password
     */
    public function checkPassword($password)
    {
        return wp_check_password($password, $this->data->user_pass, $this->ID);
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
     *
     * @return bool|Message true if success, else it provides an object consisting of a message and a type (notification or error).
     */
    function updateMeta($meta_key, $value)
    {
        $value = sanitize_text_field($value);
        if ($meta_key == "email" || $meta_key == "email_address" || $meta_key == "user_email" || $meta_key == "member_email") {
            wp_update_user(array('ID' => $this->ID, 'user_email' => sanitize_text_field($value)));
            update_user_meta($this->ID, 'user_email', $value);
            $this->user_email = $value;
            return true;
        } elseif ($meta_key == "name" || $meta_key == "display_name") {
            wp_update_user(array('ID' => $this->ID, 'display_name' => sanitize_text_field($value)));
            update_user_meta($this->ID, 'display_name', sanitize_text_field($value));
            $this->display_name = $value;
            return true;
        } elseif ($meta_key == "first_name" || $meta_key == "last_name") {
            update_user_meta($this->ID, $meta_key, sanitize_text_field($value));
            $display_name = $this->getMeta('first_name') . ' ' . $this->getMeta('last_name');
            wp_update_user(array('ID' => $this->ID, 'display_name' => sanitize_text_field($display_name)));
            update_user_meta($this->ID, 'display_name', sanitize_text_field($display_name));
            $this->display_name = $display_name;
            return true;
        } elseif ($meta_key == "login" || $meta_key == "username" || $meta_key == "user_name" || $meta_key == "user_login") {
            return new Message('Cannot change the user-login. Please consider setting the field display to \'disabled\'', Message::NOTIFICATION_MESSAGE); //cannot change user_login
        } elseif ($meta_key == "iban" || $meta_key == "IBAN") {
            if (!SSV_General::isValidIBAN($value)) {
                return new Message('The IBAN is invalid!', Message::ERROR_MESSAGE);
            } else {
                update_user_meta($this->ID, $meta_key, $value);
                return true;
            }
        } else {
            update_user_meta($this->ID, $meta_key, $value);
            return true;
        }
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
        if (!$this->__isset($meta_key)) {
            return $default;
        }

        if ($meta_key == "email" || $meta_key == "email_address" || $meta_key == "user_email" || $meta_key == "member_email") {
            return $this->user_email;
        } elseif ($meta_key == "name" || $meta_key == "display_name") {
            return $this->display_name;
        } elseif ($meta_key == "login" || $meta_key == "username" || $meta_key == "user_name" || $meta_key == "user_login") {
            return $this->user_login;
        } else {
            return stripslashes(get_user_meta($this->ID, $meta_key, true));
        }
    }
    #endregion

    #region getProfileLink($target)
    /**
     * @param string $target
     *
     * @return string of the full <a> tag.
     */
    public function getProfileLink($target = '')
    {
        $href   = esc_url($this->getProfileURL());
        $target = empty($target) ? '' : 'target="' . $target . '"';
        $label  = $this->display_name;
        return "<a href='$href' $target>$label</a>";
    }

    #endregion

    #region getProfileURL()
    /**
     * @return string the url for the users profile
     */
    public function getProfileURL()
    {
        $url = get_edit_user_link();
        $url = apply_filters(SSV_General::HOOK_USER_PROFILE_URL, array('url' => $url));
        return $url;
    }
    #endregion
}