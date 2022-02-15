<?php
if (!defined('_INCODE')) die('Access Deined...');

function setSession($key, $value)
{
    if (!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    }
    return false;
}

function getSession($key = '')
{
    if (empty($key)) {
        return $_SESSION;
    } else {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    return false;
}

function removeSession($key = '')
{
    if (empty($key)) {
        session_destroy();
        return true;
    } else {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
    }
    return false;
}

function setFalshData($key, $value)
{
    $key = 'falsh_' . $key;
    return setSession($key, $value);
}

function getFalshData($key)
{
    $key = 'falsh_' . $key;
    $data = getSession($key);
    removeSession($key);
    return $data;
}
