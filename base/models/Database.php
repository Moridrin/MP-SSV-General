<?php

namespace mp_ssv_general\base;

if (!defined('ABSPATH')) {
    exit;
}

class Database
{
    public function init_charset()
    {
        global $wpdb;
        $wpdb->init_charset();
        $this->checkForError();
    }

    public function determine_charset($charset, $collate)
    {
        global $wpdb;
        $return = $wpdb->determine_charset($charset, $collate);
        $this->checkForError();
        return $return;
    }

    public function set_charset($dbh, $charset = null, $collate = null)
    {
        global $wpdb;
        $wpdb->set_charset($dbh, $charset, $collate);
        $this->checkForError();
    }

    public function set_sql_mode($modes = [])
    {
        global $wpdb;
        $wpdb->set_sql_mode($modes);
        $this->checkForError();
    }

    public function set_prefix($prefix, $set_table_names = true)
    {
        global $wpdb;
        $return = $wpdb->set_prefix($prefix, $set_table_names);
        $this->checkForError();
        return $return;
    }

    public function set_blog_id($blog_id, $network_id = 0)
    {
        global $wpdb;
        $return = $wpdb->set_blog_id($blog_id, $network_id);
        $this->checkForError();
        return $return;
    }

    public function get_blog_prefix($blog_id = null)
    {
        global $wpdb;
        $return = $wpdb->get_blog_prefix($blog_id);
        $this->checkForError();
        return $return;
    }

    public function tables($scope = 'all', $prefix = true, $blog_id = 0)
    {
        global $wpdb;
        $return = $wpdb->tables($scope, $prefix, $blog_id);
        $this->checkForError();
        return $return;
    }

    public function select($db, $dbh = null)
    {
        global $wpdb;
        $wpdb->select($db, $dbh);
        $this->checkForError();
    }

    function _real_escape($string)
    {
        global $wpdb;
        $return = $wpdb->_real_escape($string);
        $this->checkForError();
        return $return;
    }

    public function _escape($data)
    {
        global $wpdb;
        $return = $wpdb->_escape($data);
        $this->checkForError();
        return $return;
    }

    public function escape_by_ref(&$string)
    {
        global $wpdb;
        $wpdb->escape_by_ref($string);
        $this->checkForError();
    }

    public function prepare($query, $args)
    {
        global $wpdb;
        $return = $wpdb->prepare($query, $args);
        $this->checkForError();
        return $return;
    }

    public function esc_like($text)
    {
        global $wpdb;
        $return = $wpdb->esc_like($text);
        $this->checkForError();
        return $return;
    }

    public function print_error($str = '')
    {
        global $wpdb;
        $return = $wpdb->print_error($str);
        $this->checkForError();
        return $return;
    }

    public function show_errors($show = true)
    {
        global $wpdb;
        $return = $wpdb->show_errors($show);
        $this->checkForError();
        return $return;
    }

    public function hide_errors()
    {
        global $wpdb;
        $return = $wpdb->hide_errors();
        $this->checkForError();
        return $return;
    }

    public function suppress_errors($suppress = true)
    {
        global $wpdb;
        $return = $wpdb->suppress_errors($suppress);
        $this->checkForError();
        return $return;
    }

    public function flush()
    {
        global $wpdb;
        $wpdb->flush();
        $this->checkForError();
    }

    public function db_connect($allow_bail = true)
    {
        global $wpdb;
        $return = $wpdb->db_connect($allow_bail);
        $this->checkForError();
        return $return;
    }

    public function parse_db_host($host)
    {
        global $wpdb;
        $return = $wpdb->parse_db_host($host);
        $this->checkForError();
        return $return;
    }

    public function check_connection($allow_bail = true)
    {
        global $wpdb;
        $return = $wpdb->check_connection($allow_bail);
        $this->checkForError();
        return $return;
    }

    public function placeholder_escape()
    {
        global $wpdb;
        $return = $wpdb->placeholder_escape();
        $this->checkForError();
        return $return;
    }

    public function add_placeholder_escape($query)
    {
        global $wpdb;
        $return = $wpdb->add_placeholder_escape($query);
        $this->checkForError();
        return $return;
    }

    public function remove_placeholder_escape($query)
    {
        global $wpdb;
        $return = $wpdb->remove_placeholder_escape($query);
        $this->checkForError();
        return $return;
    }

    public function insert($table, $data, $format = null)
    {
        global $wpdb;
        $return = $wpdb->insert($table, $data, $format);
        $this->checkForError();
        return $return;
    }

    public function replace($table, $data, $format = null)
    {
        global $wpdb;
        $return = $wpdb->replace($table, $data, $format);
        $this->checkForError();
        return $return;
    }

    function _insert_replace_helper($table, $data, $format = null, $type = 'INSERT')
    {
        global $wpdb;
        $return = $wpdb->_insert_replace_helper($table, $data, $format, $type);
        $this->checkForError();
        return $return;
    }

    public function update($table, $data, $where, $format = null, $where_format = null)
    {
        global $wpdb;
        $return = $wpdb->update($table, $data, $where, $format, $where_format);
        $this->checkForError();
        return $return;
    }

    public function delete($table, $where, $where_format = null)
    {
        global $wpdb;
        $return = $wpdb->delete($table, $where, $where_format);
        $this->checkForError();
        return $return;
    }

    public function get_var($query = null, $x = 0, $y = 0)
    {
        global $wpdb;
        $return = $wpdb->get_var($query, $x, $y);
        $this->checkForError();
        return $return;
    }

    public function get_row($query = null, $output = OBJECT, $y = 0)
    {
        global $wpdb;
        $return = $wpdb->get_row($query, $output, $y);
        $this->checkForError();
        return $return;
    }

    public function get_col($query = null, $x = 0)
    {
        global $wpdb;
        $return = $wpdb->get_col($query, $x);
        $this->checkForError();
        return $return;
    }

    public function get_results($query = null, $output = OBJECT)
    {
        global $wpdb;
        $return = $wpdb->get_results($query, $output);
        $this->checkForError();
        return $return;
    }

    public function get_col_charset($table, $column)
    {
        global $wpdb;
        $return = $wpdb->get_col_charset($table, $column);
        $this->checkForError();
        return $return;
    }

    public function get_col_length($table, $column)
    {
        global $wpdb;
        $return = $wpdb->get_col_length($table, $column);
        $this->checkForError();
        return $return;
    }

    public function strip_invalid_text_for_column($table, $column, $value)
    {
        global $wpdb;
        $return = $wpdb->strip_invalid_text_for_column($table, $column, $value);
        $this->checkForError();
        return $return;
    }

    public function get_col_info($info_type = 'name', $col_offset = -1)
    {
        global $wpdb;
        $return = $wpdb->get_col_info($info_type, $col_offset);
        $this->checkForError();
        return $return;
    }

    public function timer_start()
    {
        global $wpdb;
        $return = $wpdb->timer_start();
        $this->checkForError();
        return $return;
    }

    public function timer_stop()
    {
        global $wpdb;
        $return = $wpdb->timer_stop();
        $this->checkForError();
        return $return;
    }

    public function bail($message, $error_code = '500')
    {
        global $wpdb;
        $return = $wpdb->bail($message, $error_code);
        $this->checkForError();
        return $return;
    }

    public function close()
    {
        global $wpdb;
        $return = $wpdb->close();
        $this->checkForError();
        return $return;
    }

    public function check_database_version()
    {
        global $wpdb;
        $return = $wpdb->check_database_version();
        $this->checkForError();
        return $return;
    }

    public function supports_collation()
    {
        global $wpdb;
        $return = $wpdb->supports_collation();
        $this->checkForError();
        return $return;
    }

    public function get_charset_collate()
    {
        global $wpdb;
        $return = $wpdb->get_charset_collate();
        $this->checkForError();
        return $return;
    }

    public function has_cap($db_cap)
    {
        global $wpdb;
        $return = $wpdb->has_cap($db_cap);
        $this->checkForError();
        return $return;
    }

    public function get_caller()
    {
        global $wpdb;
        $return = $wpdb->get_caller();
        $this->checkForError();
        return $return;
    }

    public function db_version()
    {
        global $wpdb;
        $return = $wpdb->db_version();
        $this->checkForError();
        return $return;
    }

    public function query($query)
    {
        global $wpdb;
        $return = $wpdb->query($query);
        $this->checkForError();
        return $return;
    }

    private function checkForError()
    {
        if (!empty($this->last_error) && $this->last_error !== 'You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near \'\' at line 1') {
            $_SESSION['SSV']['errors'][] = $this->last_error;
        }
    }

    public function getBlogsTable()
    {
        global $wpdb;
        return $wpdb->blogs;
    }

    public function getUsersTable()
    {
        global $wpdb;
        return $wpdb->users;
    }
}
