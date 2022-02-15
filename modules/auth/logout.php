<?php
if (!defined('_INCODE')) die('Access Deined...');

if (isLogin()) {
    $token = getSession('loginToken');
    echo '<br />';
    echo $token;
    delete('login_token', "token='$token'");
    removeSession('loginToken');
    redirect('?module=auth&action=login');
}
