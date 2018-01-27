<?php

namespace mp_ssv_general\base;

if (!defined('ABSPATH')) {
    exit;
}

class Database extends \wpdb
{
    public function __construct()
    {
        global $wpdb;
        parent::__construct($wpdb->dbuser, $wpdb->dbpassword, $wpdb->dbname, $wpdb->dbhost);
    }

    public function query($query)
    {
        $result = parent::query($query);
        $this->checkForError();
        return $result;
    }

    private function checkForError() {
        if (!empty($this->last_error) && $this->last_error !== 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'\' at line 1') {
            $_SESSION['SSV']['errors'][] = $this->last_error;
        }
    }
}
