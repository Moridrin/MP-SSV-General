<?php

/**
 * Created by: Jeroen Berkvens
 * Date: 23-4-2016
 * Time: 14:48
 */
class FrontendMember extends \WP_User
{
	/**
	 * FrontendMember constructor.
	 *
	 * @param \WP_User $user the WP_User component used as base for the FrontendMember
	 */
	function __construct($user)
	{
		parent::__construct($user);
	}

	public static function get_by_id($id)
	{
		return new FrontendMember(get_user_by('id', $id));
	}

	public static function registerFromPOST()
	{
		$parent_id = wp_create_user($_POST['username'], $_POST['password'], $_POST['email']);
		unset($_POST['username']);
		unset($_POST['password']);
		unset($_POST['email']);

		return new FrontendMember(get_user_by('ID', $parent_id));
	}

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

	/**
	 * This function sets the metadata defined by the key (or an alias of that key).
	 * The aliases are:
	 *  - email, email_address, member_email => user_email
	 *  - name => display_name
	 *  - login, username, user_name => user_login
	 * If the key contains "_role" or "_role_select" this function will also add, remove or change the role.
	 *
	 * @param string $meta_key the key that defines which metadata to set.
	 * @param string $value    the value to set.
	 *
	 * @return bool is only false if the key is user_login (or an alias).
	 */
	function updateMeta($meta_key, $value)
	{
		if ($meta_key == "email" || $meta_key == "email_address" || $meta_key == "user_email" || $meta_key == "member_email") {
			wp_update_user(array('ID' => $this->ID, 'user_email' => $value));
			update_user_meta($this->ID, 'user_email', $value);
			$this->user_email = $value;

			return true;
		} else if ($meta_key == "name" || $meta_key == "display_name") {
			wp_update_user(array('ID' => $this->ID, 'display_name' => $value));
			update_user_meta($this->ID, 'display_name', $value);
			$this->display_name = $value;

			return true;
		} else if ($meta_key == "login" || $meta_key == "username" || $meta_key == "user_name" || $meta_key == "user_login") {
			return false; //cannot change user_login
		} else if (strpos($meta_key, "_role_select") !== false) {
			$old_role = $this->getMeta($meta_key, true);
			if ($old_role == $value) {
				return true;
			}
			parent::remove_role($old_role);
			parent::add_role($value);

			update_user_meta($this->ID, $meta_key, $value);
			$to = 'mp.berkvens@gmail.com';
			$subject = "Member Role Changed";
			$message = 'Hello,<br/><br/>' . $this->display_name . ' has changed his role from ' . $old_role . ' to ' . $value . '.<br/><a href="http://allterrain.nl/profile/?user_id=' . $this->ID . '" target="_blank">View User</a><br/><br/>Greetings, Jeroen Berkvens.';
			$headers = "From: webmaster@AllTerrain.nl" . "\r\n";
			add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
			if (!isset($_POST['register'])) {
				wp_mail($to, $subject, $message, $headers);
			}

			return true;
		} else if (strpos($meta_key, "_role") !== false) {
			$role = str_replace("_role", "", $meta_key);
			$old_value = $this->getMeta($role, true);
			$to = 'mp.berkvens@gmail.com';
			if ($old_value == $value) {
				return true;
			}
			if ($value == "yes") {
				parent::add_role($role);
				$subject = "Member Joined " . $role;
				$message = 'Hello,<br/><br/>' . $this->display_name . ' has joined ' . $role . '.<br/><a href="http://allterrain.nl/profile/?user_id=' . $this->ID . '" target="_blank">View User</a><br/><br/>Greetings, Jeroen Berkvens.';
			} else {
				parent::remove_role($role);
				$subject = "Member Left " . $role;
				$message = 'Hello,<br/><br/>' . $this->display_name . ' has left ' . $role . '.<br/><a href="http://allterrain.nl/profile/?user_id=' . $this->ID . '" target="_blank">View User</a><br/><br/>Greetings, Jeroen Berkvens.';
			}
			update_user_meta($this->ID, $role, $value);
			$headers = "From: webmaster@AllTerrain.nl" . "\r\n";
			add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
			if (!isset($_POST['register'])) {
				wp_mail($to, $subject, $message, $headers);
			}

			return true;
		} else {
			update_user_meta($this->ID, $meta_key, $value);

			return true;
        }
    }

    /**
     * This function returns the metadata associated with the given key (or an alias of that key).
     * The aliases are:
     *  - email, email_address, member_email => user_email
     *  - name => display_name
     *  - login, username, user_name => user_login
     * If the key contains "_role" this function will return if the FrontendMember is part of that role.
     *
     * @param string $meta_key defines which metadata should be returned.
     * @param bool   $single   defines if it should return a single value or an array of values. Default it will return
     *                         a single value.
     *
     * @return string the value associated with the key.
     */
    function getMeta($meta_key, $single = true)
    {
        if ($meta_key == "email" || $meta_key == "email_address" || $meta_key == "user_email" || $meta_key == "member_email") {
            return $this->user_email;
        } else if ($meta_key == "name" || $meta_key == "display_name") {
            return $this->display_name;
        } else if ($meta_key == "login" || $meta_key == "username" || $meta_key == "user_name" || $meta_key == "user_login") {
            return $this->user_login;
        } else if (strpos($meta_key, "_role_select") !== false) {
            return get_user_meta($this->ID, $meta_key, $single);
        } else if (strpos($meta_key, "_role") !== false) {
            return in_array(str_replace("_role", "", $meta_key), $this->roles);
        } else {
            return stripslashes(get_user_meta($this->ID, $meta_key, $single));
		}
	}
}